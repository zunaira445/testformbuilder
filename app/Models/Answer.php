<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model {
    protected $fillable = [
        'test_attempt_id','question_id','selected_option',
        'is_correct','marks_awarded','is_marked_review',
    ];
    public function question() { return $this->belongsTo(Question::class); }
    public function attempt()  { return $this->belongsTo(TestAttempt::class); }
}