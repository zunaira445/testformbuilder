<?php
// FILE PATH: database/migrations/2026_06_23_100002_add_verification_columns_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Email OTP verified flag
            $table->boolean('is_email_verified')->default(false)->after('email');
            // Admin payment approval flag (only needed for instructors)
            $table->boolean('is_payment_approved')->default(false)->after('is_email_verified');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_email_verified', 'is_payment_approved']);
        });
    }
};