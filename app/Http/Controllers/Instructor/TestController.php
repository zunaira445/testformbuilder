<?php
// FILE PATH: app/Http/Controllers/Instructor/TestController.php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\{Test, TestSection, Question, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TestController extends Controller
{
    // ── List Tests ────────────────────────────────────────────
    public function index()
    {
        $tests = Test::where('user_id', Auth::id())
            ->withCount('attempts')
            ->latest()
            ->paginate(15);
        return view('instructor.tests.index', compact('tests'));
    }

    // ── Show Create Form ──────────────────────────────────────
    public function create()
    {
        return view('instructor.tests.create');
    }

    // ── Store New Test ────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'negative_marks'   => 'nullable|numeric|min:0|max:10',
        ]);

        $isDraft = $request->input('action') === 'draft';

        $test = Test::create([
            'user_id'           => Auth::id(),
            'category'          => $request->category ?? null,   // free text now
            'title'             => $request->title,
            'description'       => $request->description,
            'instructions'      => $request->instructions,
            'mode'              => $request->mode ?? 'A',
            'duration_minutes'  => $request->duration_minutes,
            'max_attempts'      => $request->max_attempts ?? 1,
            'random_questions'  => $request->boolean('random_questions'),
            'random_options'    => $request->boolean('random_options'),
            'anti_cheat'        => $request->boolean('anti_cheat'),
            'violation_limit'   => $request->violation_limit ?? 3,
            'negative_marking'  => $request->boolean('negative_marking'),
            'negative_marks'    => $request->negative_marks ?? 0.25,
            'result_visibility' => $request->result_visibility ?? 'hidden',
            'is_open'           => false,
            'start_at'          => $request->start_at ?: null,
            'end_at'            => $request->end_at   ?: null,
        ]);

        // Build sections + questions from wizard
        if ($request->has('sections')) {
            foreach ($request->sections as $sOrder => $sData) {
                if (empty($sData['title'])) continue;

                $section = TestSection::create([
                    'test_id' => $test->id,
                    'title'   => $sData['title'],
                    'order'   => $sOrder,
                ]);

                if (!empty($sData['questions'])) {
                    foreach ($sData['questions'] as $qData) {
                        if (empty($qData['statement'])) continue;
                        Question::create([
                            'test_section_id' => $section->id,
                            'statement'       => $qData['statement'],
                            'option_a'        => $qData['option_a']       ?? '',
                            'option_b'        => $qData['option_b']       ?? '',
                            'option_c'        => $qData['option_c']       ?? '',
                            'option_d'        => $qData['option_d']       ?? '',
                            'option_e'        => $qData['option_e']       ?? null,
                            'correct_answer'  => $qData['correct_answer'] ?? 'a',
                            'marks'           => $qData['marks']          ?? 1,
                            'explanation'     => $qData['explanation']    ?? null,
                            'is_active'       => true,
                        ]);
                    }
                }
            }
        }

        if ($isDraft) {
            return redirect()->route('instructor.tests.edit', $test)
                ->with('success', 'Test saved as draft. You can continue editing.');
        }

        return redirect()->route('instructor.tests.edit', $test)
            ->with('success', 'Test created successfully! Add more questions below.');
    }

    // ── Edit Form ─────────────────────────────────────────────
    public function edit(Test $test)
    {
        $this->authorizeTest($test);
        $test->load('sections.questions');
        return view('instructor.tests.edit', compact('test'));
    }

    // ── Update Test Settings ──────────────────────────────────
    public function update(Request $request, Test $test)
    {
        $this->authorizeTest($test);

        $request->validate([
            'title'            => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'negative_marks'   => 'nullable|numeric|min:0|max:10',
        ]);

        $test->update([
            'category'          => $request->category ?? null,
            'title'             => $request->title,
            'description'       => $request->description,
            'instructions'      => $request->instructions,
            'mode'              => $request->mode,
            'duration_minutes'  => $request->duration_minutes,
            'max_attempts'      => $request->max_attempts ?? 1,
            'result_visibility' => $request->result_visibility,
            'start_at'          => $request->start_at ?: null,
            'end_at'            => $request->end_at   ?: null,
            'violation_limit'   => $request->violation_limit ?? 3,
            'negative_marks'    => $request->negative_marks ?? 0.25,
            'random_questions'  => $request->boolean('random_questions'),
            'random_options'    => $request->boolean('random_options'),
            'anti_cheat'        => $request->boolean('anti_cheat'),
            'negative_marking'  => $request->boolean('negative_marking'),
        ]);

        return back()->with('success', 'Test settings updated successfully.');
    }

    // ── Toggle Open / Close ───────────────────────────────────
    public function toggleOpen(Test $test)
    {
        $this->authorizeTest($test);
        $test->update(['is_open' => !$test->is_open]);
        return back()->with('success', $test->is_open ? 'Test is now Open.' : 'Test is now Closed.');
    }

    // ── Publish Results & Assign Ranks ────────────────────────
    public function publishResults(Test $test)
    {
        $this->authorizeTest($test);

        $attempts = $test->attempts()
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->orderByDesc('obtained_marks')
            ->orderBy('time_taken_seconds')
            ->get();

        foreach ($attempts as $i => $attempt) {
            $attempt->update(['rank' => $i + 1]);
        }

        $test->update(['result_published' => true]);
        return back()->with('success', 'Results published and ranks assigned successfully.');
    }

    // ── View Results ──────────────────────────────────────────
    public function results(Test $test)
    {
        $this->authorizeTest($test);
        $attempts = $test->attempts()
            ->with('user')
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->orderBy('rank')
            ->paginate(30);
        return view('instructor.tests.results', compact('test', 'attempts'));
    }

    // ── Duplicate Test ────────────────────────────────────────
    public function duplicate(Test $test)
    {
        $this->authorizeTest($test);

        $newTest                    = $test->replicate();
        $newTest->title             = $test->title . ' (Copy)';
        $newTest->is_open           = false;
        $newTest->result_published  = false;
        $newTest->test_code         = null;
        $newTest->save();

        foreach ($test->sections as $section) {
            $newSection          = $section->replicate();
            $newSection->test_id = $newTest->id;
            $newSection->save();

            foreach ($section->questions as $q) {
                $newQ                    = $q->replicate();
                $newQ->test_section_id   = $newSection->id;
                $newQ->save();
            }
        }

        return redirect()->route('instructor.tests.edit', $newTest)
            ->with('success', 'Test duplicated successfully!');
    }

    // ── Export PDF ────────────────────────────────────────────
    public function exportPdf(Test $test)
    {
        $this->authorizeTest($test);
        $test->load('sections.questions');
        $html = view('instructor.tests.export-pdf', compact('test'))->render();
        return response($html)
            ->header('Content-Type',        'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $test->test_code . '.html"');
    }

    // ── Export CSV ────────────────────────────────────────────
    public function exportCsv(Test $test)
    {
        $this->authorizeTest($test);
        $test->load('sections.questions');

        $filename = 'test-' . $test->test_code . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($test) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Section','Question','Option A','Option B','Option C','Option D','Option E','Correct Answer','Marks','Explanation']);
            foreach ($test->sections as $section) {
                foreach ($section->questions as $q) {
                    fputcsv($handle, [
                        $section->title,
                        $q->statement,
                        $q->option_a,
                        $q->option_b,
                        $q->option_c,
                        $q->option_d,
                        $q->option_e ?? '',
                        strtoupper($q->correct_answer),
                        $q->marks,
                        $q->explanation ?? '',
                    ]);
                }
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Export Excel (CSV with xlsx extension) ────────────────
    public function exportExcel(Test $test)
    {
        $this->authorizeTest($test);
        return $this->exportCsv($test);
    }

    // ── Private: Authorize ────────────────────────────────────
    private function authorizeTest(Test $test): void
    {
        if ($test->user_id !== Auth::id()) abort(403, 'Access denied.');
    }
}