<?php
namespace App\Http\Controllers\Instructor;
use App\Http\Controllers\Controller;
use App\Models\{TestSection, Question};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller {
    public function store(Request $request, TestSection $section) {
        if ($section->test->user_id !== Auth::id()) abort(403);
        $request->validate([
            'statement'      => 'required',
            'option_a'       => 'required',
            'option_b'       => 'required',
            'option_c'       => 'required',
            'option_d'       => 'required',
            'correct_answer' => 'required|in:a,b,c,d,e',
            'marks'          => 'required|numeric|min:0.25',
        ]);
        Question::create($request->all() + ['test_section_id' => $section->id]);
        return back()->with('success', 'Question add ho gaya.');
    }

    public function update(Request $request, Question $question) {
        if ($question->section->test->user_id !== Auth::id()) abort(403);
        $question->update($request->only([
            'statement','option_a','option_b','option_c','option_d',
            'option_e','correct_answer','marks','explanation','in_question_bank',
        ]) + ['is_active' => $request->boolean('is_active')]);
        return back()->with('success', 'Question update ho gaya.');
    }

    public function toggle(Question $question) {
        if ($question->section->test->user_id !== Auth::id()) abort(403);
        $question->update(['is_active' => !$question->is_active]);
        return back();
    }

    public function destroy(Question $question) {
        if ($question->section->test->user_id !== Auth::id()) abort(403);
        $question->delete();
        return back()->with('success', 'Question delete ho gaya.');
    }
}