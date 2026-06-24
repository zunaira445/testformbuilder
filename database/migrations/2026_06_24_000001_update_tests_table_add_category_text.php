<?php
// FILE PATH: database/migrations/2026_06_24_000001_update_tests_table_add_category_text.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            // Add free-text category column (replaces category_id FK)
            // We keep category_id for backward compat but add 'category' text field
            $table->string('category', 100)->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};