<?php
// FILE PATH: app/Models/EmailOtpToken.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtpToken extends Model
{
    protected $fillable = ['email', 'otp', 'expires_at', 'used'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used'       => 'boolean',
    ];

    // Check karo kya OTP valid hai
    public function isValid(string $otp): bool
    {
        return !$this->used
            && $this->expires_at->isFuture()
            && $this->otp === $otp;
    }
}