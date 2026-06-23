<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TestAttempt extends Model {
    protected $fillable = [
        'test_id','user_id','status','obtained_marks','total_marks','percentage',
        'rank','violation_count','submission_reason','time_taken_seconds',
        'started_at','submitted_at',
    ];
    protected $casts = ['started_at'=>'datetime','submitted_at'=>'datetime'];

    public function test()    { return $this->belongsTo(Test::class); }
    public function user()    { return $this->belongsTo(User::class); }
    public function answers() { return $this->hasMany(Answer::class); }
}