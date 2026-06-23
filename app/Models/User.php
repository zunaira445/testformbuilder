<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','role','phone','institution',
        'city','roll_number','is_active','dark_mode',
    ];
    protected $hidden = ['password','remember_token'];
    protected function casts(): array {
        return ['email_verified_at'=>'datetime','password'=>'hashed'];
    }

    public function tests() { return $this->hasMany(Test::class); }
    public function attempts() { return $this->hasMany(TestAttempt::class); }
    public function subscriptions() { return $this->hasMany(UserSubscription::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    public function activeSubscription() {
        return $this->hasOne(UserSubscription::class)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->latest();
    }

    public function isAdmin()      { return $this->role === 'admin'; }
    public function isInstructor() { return $this->role === 'instructor'; }
    public function isStudent()    { return $this->role === 'student'; }
}