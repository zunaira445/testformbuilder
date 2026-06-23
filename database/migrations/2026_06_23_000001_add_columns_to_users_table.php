<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['student', 'instructor', 'admin'])->default('student')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('institution')->nullable()->after('phone');
            $table->string('city')->nullable()->after('institution');
            $table->string('roll_number')->nullable()->after('city');
            $table->boolean('is_active')->default(true)->after('roll_number');
            $table->boolean('dark_mode')->default(false)->after('is_active');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role','phone','institution','city','roll_number','is_active','dark_mode']);
        });
    }
};