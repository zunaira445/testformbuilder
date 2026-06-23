<?php
// FILE PATH: app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'institution',
        'city',
        'roll_number',
        'is_active',
        'dark_mode',
        'is_email_verified',
        'is_payment_approved',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at'   => 'datetime',
        'is_active'           => 'boolean',
        'dark_mode'           => 'boolean',
        'is_email_verified'   => 'boolean',
        'is_payment_approved' => 'boolean',
    ];

    // ── Role Helpers ─────────────────────────────────────────
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ── Subscription ──────────────────────────────────────────
    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->latest();
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}