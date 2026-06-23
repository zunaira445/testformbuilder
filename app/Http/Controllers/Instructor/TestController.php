<?php
// FILE PATH: app/Http/Controllers/Instructor/TestController.php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\{Test, TestSection, Question, Category};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::where('user_id', Auth::id())
            ->withCount('attempts')
            ->latest()->paginate(15);
        return view('instructor.tests.index', compact('tests'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('instructor.tests.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $test = Test::create([
            'user_id'           => Auth::id(),
            'category_id'       => $request->category_id,
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
            'start_at'          => $request->start_at,
            'end_at'            => $request->end_at,
        ]);

        // Sections and questions
        if ($request->has('sections')) {
            foreach ($request->sections as $sOrder => $sData) {
                $section = TestSection::create([
                    'test_id' => $test->id,
                    'title'   => $sData['title'] ?? 'Section ' . ($sOrder + 1),
                    'order'   => $sOrder,
                ]);
                if (isset($sData['questions'])) {
                    foreach ($sData['questions'] as $qData) {
                        if (empty($qData['statement'])) continue;
                        Question::create([
                            'test_section_id' => $section->id,
                            'statement'       => $qData['statement'],
                            'option_a'        => $qData['option_a'],
                            'option_b'        => $qData['option_b'],
                            'option_c'        => $qData['option_c'],
                            'option_d'        => $qData['option_d'],
                            'option_e'        => $qData['option_e'] ?? null,
                            'correct_answer'  => $qData['correct_answer'],
                            'marks'           => $qData['marks'] ?? 1,
                            'explanation'     => $qData['explanation'] ?? null,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('instructor.tests.edit', $test)
            ->with('success', 'Test created! You can now add more questions.');
    }

    public function edit(Test $test)
    {
        $this->authorize_test($test);
        $categories = Category::all();
        $test->load('sections.questions');
        return view('instructor.tests.edit', compact('test', 'categories'));
    }

    public function update(Request $request, Test $test)
    {
        $this->authorize_test($test);
        $test->update($request->only([
            'category_id', 'title', 'description', 'instructions', 'mode',
            'duration_minutes', 'max_attempts', 'result_visibility', 'start_at', 'end_at',
            'violation_limit', 'negative_marks',
        ]) + [
            'random_questions' => $request->boolean('random_questions'),
            'random_options'   => $request->boolean('random_options'),
            'anti_cheat'       => $request->boolean('anti_cheat'),
            'negative_marking' => $request->boolean('negative_marking'),
        ]);
        return back()->with('success', 'Test settings updated.');
    }

    public function toggleOpen(Test $test)
    {
        $this->authorize_test($test);
        $test->update(['is_open' => !$test->is_open]);
        return back()->with('success', $test->is_open ? 'Test is now Open.' : 'Test is now Closed.');
    }

    public function publishResults(Test $test)
    {
        $this->authorize_test($test);
        $attempts = $test->attempts()
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->orderByDesc('obtained_marks')
            ->orderBy('time_taken_seconds')
            ->get();
        foreach ($attempts as $i => $attempt) {
            $attempt->update(['rank' => $i + 1]);
        }
        $test->update(['result_published' => true]);
        return back()->with('success', 'Results published and ranks assigned.');
    }

    public function results(Test $test)
    {
        $this->authorize_test($test);
        $attempts = $test->attempts()
            ->with('user')
            ->whereIn('status', ['submitted', 'auto_submitted'])
            ->orderBy('rank')
            ->paginate(30);
        return view('instructor.tests.results', compact('test', 'attempts'));
    }

    /**
     * Duplicate a test with all its sections and questions.
     */
    public function duplicate(Test $test)
    {
        $this->authorize_test($test);

        $newTest = $test->replicate();
        $newTest->title          = $test->title . ' (Copy)';
        $newTest->is_open        = false;
        $newTest->result_published = false;
        $newTest->test_code      = null; // will be auto-generated by boot()
        $newTest->save();

        foreach ($test->sections as $section) {
            $newSection = $section->replicate();
            $newSection->test_id = $newTest->id;
            $newSection->save();

            foreach ($section->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->test_section_id = $newSection->id;
                $newQuestion->save();
            }
        }

        return redirect()->route('instructor.tests.edit', $newTest)
            ->with('success', 'Test duplicated successfully! You can now edit the copy.');
    }

    /**
     * Export test questions as PDF (simple HTML-based).
     */
    public function exportPdf(Test $test)
    {
        $this->authorize_test($test);
        $test->load('sections.questions');

        $html = view('instructor.tests.export-pdf', compact('test'))->render();

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $test->test_code . '.html"');
    }

    /**
     * Export test questions as CSV.
     */
    public function exportCsv(Test $test)
    {
        $this->authorize_test($test);
        $test->load('sections.questions');

        $filename = 'test-' . $test->test_code . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($test) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Section', 'Question', 'Option A', 'Option B', 'Option C', 'Option D', 'Option E', 'Correct Answer', 'Marks', 'Explanation']);
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

    /**
     * Export as Excel (CSV with .xlsx extension — compatible with Excel).
     */
    public function exportExcel(Test $test)
    {
        $this->authorize_test($test);
        // Redirect to CSV with Excel MIME type
        return $this->exportCsv($test);
    }

    private function authorize_test(Test $test)
    {
        if ($test->user_id !== Auth::id()) abort(403);
    }
}