{{-- FILE PATH: resources/views/instructor/tests/partials/question-form.blade.php --}}
{{-- Used in both Add and Edit modals --}}

<div class="mb-3">
    <label class="form-label fw-semibold">Question Statement <span class="text-danger">*</span></label>
    <textarea name="statement" class="form-control" rows="3" required placeholder="Enter the question here...">{{ $q?->statement }}</textarea>
</div>

<div class="row g-2 mb-3">
    @foreach(['a'=>'Option A','b'=>'Option B','c'=>'Option C','d'=>'Option D'] as $key=>$label)
    <div class="col-md-6">
        <label class="form-label fw-semibold small">{{ $label }} <span class="text-danger">*</span></label>
        <input type="text" name="option_{{ $key }}" class="form-control form-control-sm" value="{{ $q?->{'option_'.$key} }}" placeholder="{{ $label }}..." required>
    </div>
    @endforeach
    <div class="col-md-6">
        <label class="form-label fw-semibold small">Option E (Optional)</label>
        <input type="text" name="option_e" class="form-control form-control-sm" value="{{ $q?->option_e }}" placeholder="5th option (optional)">
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Correct Answer <span class="text-danger">*</span></label>
        <select name="correct_answer" class="form-select form-select-sm" required>
            @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D','e'=>'E'] as $val=>$label)
            <option value="{{ $val }}" {{ $q?->correct_answer==$val?'selected':'' }}>Option {{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Marks <span class="text-danger">*</span></label>
        <input type="number" name="marks" class="form-control form-control-sm" value="{{ $q?->marks ?? 1 }}" step="0.25" min="0.25" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">Explanation (Shown after result)</label>
    <textarea name="explanation" class="form-control form-control-sm" rows="2" placeholder="Explain the correct answer...">{{ $q?->explanation }}</textarea>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $q===null || $q->is_active ? 'checked' : '' }}>
            <label class="form-check-label small">Active (visible to students)</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-check form-switch">
            <input type="hidden" name="in_question_bank" value="0">
            <input class="form-check-input" type="checkbox" name="in_question_bank" value="1" {{ $q?->in_question_bank ? 'checked' : '' }}>
            <label class="form-check-label small">Save to Question Bank</label>
        </div>
    </div>
</div>