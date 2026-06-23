<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->string('test_code', 10)->unique();
            $table->enum('mode', ['A', 'B'])->default('A');
            $table->integer('duration_minutes')->default(60);
            $table->integer('max_attempts')->default(1);
            $table->boolean('random_questions')->default(false);
            $table->boolean('random_options')->default(false);
            $table->boolean('anti_cheat')->default(true);
            $table->integer('violation_limit')->default(3);
            $table->boolean('negative_marking')->default(false);
            $table->decimal('negative_marks', 5, 2)->default(0.25);
            $table->enum('result_visibility', ['hidden', 'marks_only', 'detailed'])->default('hidden');
            $table->boolean('is_open')->default(false);
            $table->boolean('result_published')->default(false);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('test_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_section_id')->constrained()->cascadeOnDelete();
            $table->text('statement');
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            $table->text('option_e')->nullable();
            $table->enum('correct_answer', ['a','b','c','d','e']);
            $table->decimal('marks', 5, 2)->default(1);
            $table->text('explanation')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('in_question_bank')->default(false);
            $table->timestamps();
        });

        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['in_progress','submitted','auto_submitted'])->default('in_progress');
            $table->decimal('obtained_marks', 8, 2)->nullable();
            $table->decimal('total_marks', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->integer('rank')->nullable();
            $table->integer('violation_count')->default(0);
            $table->text('submission_reason')->nullable();
            $table->integer('time_taken_seconds')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->enum('selected_option', ['a','b','c','d','e'])->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('marks_awarded', 5, 2)->nullable();
            $table->boolean('is_marked_review')->default(false);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained()->cascadeOnDelete();
            $table->string('method');
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id');
            $table->string('screenshot')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('expires_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('test_attempts');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('test_sections');
        Schema::dropIfExists('tests');
    }
};