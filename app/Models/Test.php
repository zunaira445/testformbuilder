<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Test extends Model {
    protected $fillable = [
        'user_id','category_id','title','description','instructions','test_code',
        'mode','duration_minutes','max_attempts','random_questions','random_options',
        'anti_cheat','violation_limit','negative_marking','negative_marks',
        'result_visibility','is_open','result_published','start_at','end_at',
    ];
    protected $casts = ['start_at'=>'datetime','end_at'=>'datetime'];

    protected static function boot() {
        parent::boot();
        static::creating(function ($test) {
            $test->test_code = strtoupper(Str::random(8));
        });
    }

    public function user()     { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function sections() { return $this->hasMany(TestSection::class)->orderBy('order'); }
    public function attempts() { return $this->hasMany(TestAttempt::class); }

    public function questions() {
        return $this->hasManyThrough(Question::class, TestSection::class);
    }

    public function getTotalMarksAttribute() {
        return $this->questions()->where('is_active', true)->sum('marks');
    }

    public function getShareLinkAttribute() {
        return url('/test/join/' . $this->test_code);
    }
}