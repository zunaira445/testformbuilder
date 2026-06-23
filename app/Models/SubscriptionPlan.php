<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model {
    protected $fillable = ['name','price','is_active'];
    public function subscriptions() { return $this->hasMany(UserSubscription::class); }
    public function payments()      { return $this->hasMany(Payment::class); }
}