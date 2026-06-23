<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Question extends Model {
    protected $fillable = [
        'test_section_id','statement','option_a','option_b','option_c',
        'option_d','option_e','correct_answer','marks','explanation',
        'is_active','in_question_bank',
    ];

    public function section() { return $this->belongsTo(TestSection::class); }

    public function getOptionsAttribute(): array {
        $opts = ['a'=>$this->option_a,'b'=>$this->option_b,
                 'c'=>$this->option_c,'d'=>$this->option_d];
        if ($this->option_e) $opts['e'] = $this->option_e;
        return $opts;
    }
}