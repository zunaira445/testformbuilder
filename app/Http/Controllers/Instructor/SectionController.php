<?php
namespace App\Http\Controllers\Instructor;
use App\Http\Controllers\Controller;
use App\Models\{Test, TestSection};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller {
    public function store(Request $request, Test $test) {
        if ($test->user_id !== Auth::id()) abort(403);
        $request->validate(['title'=>'required|string|max:255']);
        TestSection::create([
            'test_id'     => $test->id,
            'title'       => $request->title,
            'description' => $request->description,
            'order'       => $test->sections()->count(),
        ]);
        return back()->with('success', 'Section add ho gayi.');
    }

    public function destroy(TestSection $section) {
        if ($section->test->user_id !== Auth::id()) abort(403);
        $section->delete();
        return back()->with('success', 'Section delete ho gayi.');
    }
}