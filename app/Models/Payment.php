<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = [
        'user_id','subscription_plan_id','method','amount',
        'transaction_id','screenshot','notes','status','admin_note','approved_at',
    ];
    protected $casts = ['approved_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function plan() { return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id'); }
}