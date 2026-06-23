<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model {
    protected $fillable = ['user_id','subscription_plan_id','payment_id','expires_at','is_active'];
    protected $casts    = ['expires_at'=>'datetime'];
    public function user()    { return $this->belongsTo(User::class); }
    public function plan()    { return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id'); }
    public function payment() { return $this->belongsTo(Payment::class); }
}