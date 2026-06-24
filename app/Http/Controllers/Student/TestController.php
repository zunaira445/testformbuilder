<?php
// FILE PATH: app/Http/Controllers/Student/TestController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\{Test, TestAttempt, Answer, Question};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    // ── Join by Code ──────────────────────────────────────────
    public function join($code)
    {
        $test = Test::where('test_code', strtoupper($code))->firstOrFail();
        if (!$test->is_open) {
            return back()->with('error', 'This test is not open yet. Please check with your instructor.');
        }
        return redirect()->route('student.test.instructions', $test);
    }

    // ── Instructions Page ─────────────────────────────────────
    public function instructions(Test $test)
    {
        return view('student.test-instructions', compact('test'));
    }

    // ── Start or Resume Test ──────────────────────────────────
    public function start(Request $request, Test $test)
    {
        if (!$test->is_open) abort(403, 'This test is not open.');

        // Resume existing in-progress attempt
        $existing = TestAttempt::where('test_id', $test->id)
            ->where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            return $this->loadEngine($existing, $test);
        }

        // Max attempts check
        $doneCount = TestAttempt::where('test_id', $test->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->count();

        if ($doneCount >= $test->max_attempts) {
            return back()->with('error', 'You have used all your allowed attempts for this test.');
        }

        $attempt = TestAttempt::create([
            'test_id'     => $test->id,
            'user_id'     => Auth::id(),
            'status'      => 'in_progress',
            'total_marks' => $test->total_marks,
            'started_at'  => now(),
        ]);

        // Create blank answers for all active questions
        $questions = $test->questions()->where('is_active', true)->get();
        foreach ($questions as $q) {
            Answer::create([
                'test_attempt_id' => $attempt->id,
                'question_id'     => $q->id,
            ]);
        }

        return $this->loadEngine($attempt, $test);
    }

    // ── Load Test Engine ──────────────────────────────────────
    private function loadEngine(TestAttempt $attempt, Test $test)
    {
        $elapsed   = $attempt->started_at
            ? now()->diffInSeconds($attempt->started_at)
            : 0;
        $remaining = max(0, ($test->duration_minutes * 60) - $elapsed);

        // Load existing answers keyed by question_id
        $answers = Answer::where('test_attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');

        // ── FIXED: Shuffle WITHIN each section only ───────────
        $test->load('sections.activeQuestions');

        $orderedQuestions = collect();
        foreach ($test->sections as $section) {
            $sectionQuestions = $section->activeQuestions;

            // Shuffle only within this section if enabled
            if ($test->random_questions) {
                $sectionQuestions = $sectionQuestions->shuffle();
            }

            // Shuffle options within each question if enabled
            if ($test->random_options) {
                $sectionQuestions = $sectionQuestions->map(function ($q) {
                    $options = collect([
                        'a' => $q->option_a,
                        'b' => $q->option_b,
                        'c' => $q->option_c,
                        'd' => $q->option_d,
                    ])->filter()->shuffle();

                    $keys   = $options->keys()->values();
                    $values = $options->values();

                    // Map original correct answer to new position
                    $originalCorrect = $q->correct_answer;
                    $originalValue   = $q->{'option_' . $originalCorrect};
                    $newCorrectKey   = null;

                    foreach ($keys as $i => $originalKey) {
                        if ($originalKey === $originalCorrect) {
                            // Find which new position this maps to
                            $newCorrectKey = ['a','b','c','d'][$i] ?? $originalCorrect;
                            break;
                        }
                    }

                    // Re-assign shuffled options
                    $shuffledLetters = ['a','b','c','d'];
                    foreach ($shuffledLetters as $i => $letter) {
                        $q->{'option_' . $letter} = $values->get($i, '');
                    }

                    // Update correct answer to new position
                    if ($newCorrectKey) {
                        $q->correct_answer = $newCorrectKey;
                    }

                    return $q;
                });
            }

            $orderedQuestions = $orderedQuestions->merge($sectionQuestions);
        }

        return view('student.test-engine', compact(
            'test', 'attempt', 'answers', 'remaining', 'orderedQuestions'
        ));
    }

    // ── Save Answer (AJAX) ────────────────────────────────────
    public function saveAnswer(Request $request, TestAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) abort(403);
        if ($attempt->status !== 'in_progress') {
            return response()->json(['ok' => false, 'message' => 'Attempt already submitted.']);
        }

        Answer::updateOrCreate(
            [
                'test_attempt_id' => $attempt->id,
                'question_id'     => $request->question_id,
            ],
            [
                'selected_option'  => $request->selected_option,
                'is_marked_review' => $request->boolean('is_marked_review'),
            ]
        );

        return response()->json(['ok' => true]);
    }

    // ── Record Violation (AJAX) ───────────────────────────────
    public function violation(Request $request, TestAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) abort(403);
        if ($attempt->status !== 'in_progress') {
            return response()->json(['ok' => false]);
        }

        $count = $attempt->violation_count + 1;
        $attempt->update(['violation_count' => $count]);

        $autoSubmit = $count >= $attempt->test->violation_limit;
        if ($autoSubmit) {
            $this->doSubmit($attempt, 'Auto-submitted: violation limit reached.');
        }

        return response()->json([
            'warning_number' => $count,
            'remaining'      => max(0, $attempt->test->violation_limit - $count),
            'auto_submitted' => $autoSubmit,
        ]);
    }

    // ── Submit Test (AJAX) ────────────────────────────────────
    public function submit(Request $request, TestAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) abort(403);

        if ($attempt->status !== 'in_progress') {
            return response()->json([
                'redirect' => route('student.result', $attempt),
            ]);
        }

        $reason = $request->reason ?? $request->input('submission_reason') ?? 'Manual submission.';
        $this->doSubmit($attempt, $reason);

        return response()->json([
            'redirect' => route('student.result', $attempt),
        ]);
    }

    // ── Core Submit Logic ─────────────────────────────────────
    private function doSubmit(TestAttempt $attempt, string $reason): void
    {
        $test    = $attempt->test;
        $answers = $attempt->answers()->with('question')->get();

        $obtained = 0;

        foreach ($answers as $answer) {
            if (!$answer->selected_option) continue;

            $q         = $answer->question;
            $isCorrect = ($answer->selected_option === $q->correct_answer);

            // ── FIXED negative marking logic ──────────────────
            if ($isCorrect) {
                $marks = $q->marks;
            } elseif ($test->negative_marking) {
                $marks = -abs($test->negative_marks); // always negative
            } else {
                $marks = 0;
            }

            $answer->update([
                'is_correct'    => $isCorrect,
                'marks_awarded' => $marks,
            ]);

            $obtained += $marks;
        }

        $total   = $test->total_marks ?? 0;
        $pct     = $total > 0 ? round(($obtained / $total) * 100, 2) : 0;
        $elapsed = $attempt->started_at
            ? now()->diffInSeconds($attempt->started_at)
            : 0;

        $attempt->update([
            'status'             => str_contains($reason, 'Auto') ? 'auto_submitted' : 'submitted',
            'obtained_marks'     => max(0, $obtained),
            'percentage'         => max(0, $pct),
            'submission_reason'  => $reason,
            'time_taken_seconds' => $elapsed,
            'submitted_at'       => now(),
        ]);
    }

    // ── Result Page ───────────────────────────────────────────
    public function result(TestAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) abort(403);
        $test = $attempt->test;
        $attempt->load('answers.question');
        return view('student.result', compact('attempt', 'test'));
    }

    // ── My Tests ──────────────────────────────────────────────
    public function myTests()
    {
        $attempts = TestAttempt::where('user_id', Auth::id())
            ->with('test')
            ->latest()
            ->paginate(15);
        return view('student.my-tests', compact('attempts'));
    }
}