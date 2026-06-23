<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\{Test, TestAttempt, Answer, Question};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller {
    // Test code se join karo
    public function join($code) {
        $test = Test::where('test_code', strtoupper($code))->firstOrFail();
        if (!$test->is_open) {
            return back()->with('error', 'Yeh test abhi open nahi hai.');
        }
        return redirect()->route('student.test.instructions', $test);
    }

    public function instructions(Test $test) {
        return view('student.test-instructions', compact('test'));
    }

    public function start(Request $request, Test $test) {
        if (!$test->is_open) abort(403, 'Test open nahi hai.');

        // Existing attempt check karo
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
            ->whereIn('status', ['submitted','auto_submitted'])
            ->count();
        if ($doneCount >= $test->max_attempts) {
            return back()->with('error', 'Aapne maximum attempts use kar liye hain.');
        }

        $attempt = TestAttempt::create([
            'test_id'    => $test->id,
            'user_id'    => Auth::id(),
            'status'     => 'in_progress',
            'total_marks'=> $test->total_marks,
            'started_at' => now(),
        ]);

        // Blank answers create karo
        $questions = $test->questions()->where('is_active', true)->get();
        foreach ($questions as $q) {
            Answer::create([
                'test_attempt_id' => $attempt->id,
                'question_id'     => $q->id,
            ]);
        }

        return $this->loadEngine($attempt, $test);
    }

    private function loadEngine(TestAttempt $attempt, Test $test) {
        $elapsed  = $attempt->started_at
            ? now()->diffInSeconds($attempt->started_at)
            : 0;
        $remaining = max(0, ($test->duration_minutes * 60) - $elapsed);

        $answers = Answer::where('test_attempt_id', $attempt->id)
            ->get()->keyBy('question_id');

        $test->load('sections.activeQuestions');
        $orderedQuestions = $test->sections->flatMap->activeQuestions;
        if ($test->random_questions) {
            $orderedQuestions = $orderedQuestions->shuffle();
        }

        return view('student.test-engine', compact('test','attempt','answers','remaining','orderedQuestions'));
    }

    // AJAX: answer save karo
    public function saveAnswer(Request $request, TestAttempt $attempt) {
        if ($attempt->user_id !== Auth::id()) abort(403);
        if ($attempt->status !== 'in_progress') return response()->json(['ok'=>false]);

        Answer::updateOrCreate(
            ['test_attempt_id'=>$attempt->id,'question_id'=>$request->question_id],
            [
                'selected_option'  => $request->selected_option,
                'is_marked_review' => $request->boolean('is_marked_review'),
            ]
        );
        return response()->json(['ok'=>true]);
    }

    // AJAX: violation record karo
    public function violation(Request $request, TestAttempt $attempt) {
        if ($attempt->user_id !== Auth::id()) abort(403);
        if ($attempt->status !== 'in_progress') return response()->json(['ok'=>false]);

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

    // Submit karo
    public function submit(Request $request, TestAttempt $attempt) {
        if ($attempt->user_id !== Auth::id()) abort(403);
        if ($attempt->status !== 'in_progress') {
            return response()->json(['redirect' => route('student.result', $attempt)]);
        }

        $reason = $request->reason ?? 'Manual submission.';
        $this->doSubmit($attempt, $reason);

        return response()->json(['redirect' => route('student.result', $attempt)]);
    }

    private function doSubmit(TestAttempt $attempt, string $reason) {
        $test    = $attempt->test;
        $answers = $attempt->answers()->with('question')->get();

        $obtained = 0;
        foreach ($answers as $answer) {
            if (!$answer->selected_option) continue;
            $q = $answer->question;
            $isCorrect = $answer->selected_option === $q->correct_answer;
            $marks = $isCorrect ? $q->marks
                : ($test->negative_marking ? -$test->negative_marks : 0);
            $answer->update([
                'is_correct'    => $isCorrect,
                'marks_awarded' => $marks,
            ]);
            $obtained += $marks;
        }

        $total = $test->total_marks;
        $pct   = $total > 0 ? round(($obtained / $total) * 100, 2) : 0;
        $elapsed = $attempt->started_at ? now()->diffInSeconds($attempt->started_at) : 0;

        $attempt->update([
            'status'             => str_contains($reason, 'Auto') ? 'auto_submitted' : 'submitted',
            'obtained_marks'     => max(0, $obtained),
            'percentage'         => max(0, $pct),
            'submission_reason'  => $reason,
            'time_taken_seconds' => $elapsed,
            'submitted_at'       => now(),
        ]);
    }

    public function result(TestAttempt $attempt) {
        if ($attempt->user_id !== Auth::id()) abort(403);
        $test = $attempt->test;
        $attempt->load('answers.question');
        return view('student.result', compact('attempt','test'));
    }

    public function myTests() {
        $attempts = TestAttempt::where('user_id', Auth::id())
            ->with('test')->latest()->paginate(15);
        return view('student.my-tests', compact('attempts'));
    }
}