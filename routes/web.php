<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{StudentAuthController, InstructorAuthController};
use App\Http\Controllers\Instructor\{TestController as InstructorTestController, SectionController, QuestionController};
use App\Http\Controllers\Student\{TestController as StudentTestController, SubscriptionController};
use App\Http\Controllers\Admin\{DashboardController, UserController, PaymentController, PlanController};
use App\Http\Controllers\ProfileController;

// ── Home ─────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ── Auth Routes ──────────────────────────────────────────────
Route::get('/login/student',        [StudentAuthController::class,    'showLogin'])->name('student.login');
Route::post('/login/student',       [StudentAuthController::class,    'login'])->name('login');
Route::get('/register/student',     [StudentAuthController::class,    'showRegister'])->name('student.register');
Route::post('/register/student',    [StudentAuthController::class,    'register'])->name('student.register.post');
Route::get('/login/instructor',     [InstructorAuthController::class, 'showLogin'])->name('instructor.login');
Route::post('/login/instructor',    [InstructorAuthController::class, 'login'])->name('instructor.login.post');
Route::get('/register/instructor',  [InstructorAuthController::class, 'showRegister'])->name('instructor.register');
Route::post('/register/instructor', [InstructorAuthController::class, 'register'])->name('instructor.register.post');
Route::post('/logout',              [StudentAuthController::class,    'logout'])->name('logout');

// ── Pricing (public) ─────────────────────────────────────────
Route::get('/pricing', [SubscriptionController::class, 'index'])->name('pricing');
Route::post('/payment/submit/{planId}', [SubscriptionController::class, 'submit'])
    ->name('payment.submit')->middleware('auth');

// ── Test Join (public) ────────────────────────────────────────
Route::get('/test/join/{code}', [StudentTestController::class, 'join'])->name('student.test.join');

// ── Profile (all authenticated users) ────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',           [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ── Instructor Routes ─────────────────────────────────────────
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', fn() => view('instructor.dashboard'))->name('dashboard');

    // Tests resource
    Route::resource('tests', InstructorTestController::class)->except(['show', 'destroy']);
    Route::post('tests/{test}/toggle-open',  [InstructorTestController::class, 'toggleOpen'])->name('tests.toggle-open');
    Route::post('tests/{test}/publish',      [InstructorTestController::class, 'publishResults'])->name('tests.publish');
    Route::get('tests/{test}/results',       [InstructorTestController::class, 'results'])->name('tests.results');
    Route::post('tests/{test}/duplicate',    [InstructorTestController::class, 'duplicate'])->name('tests.duplicate');

    // Export routes
    Route::get('tests/{test}/export/pdf',   [InstructorTestController::class, 'exportPdf'])->name('tests.export.pdf');
    Route::get('tests/{test}/export/excel', [InstructorTestController::class, 'exportExcel'])->name('tests.export.excel');
    Route::get('tests/{test}/export/csv',   [InstructorTestController::class, 'exportCsv'])->name('tests.export.csv');

    // Sections & Questions
    Route::post('tests/{test}/sections',        [SectionController::class,   'store'])->name('sections.store');
    Route::delete('sections/{section}',         [SectionController::class,   'destroy'])->name('sections.destroy');
    Route::post('sections/{section}/questions', [QuestionController::class,  'store'])->name('questions.store');
    Route::put('questions/{question}',          [QuestionController::class,  'update'])->name('questions.update');
    Route::post('questions/{question}/toggle',  [QuestionController::class,  'toggle'])->name('questions.toggle');
    Route::delete('questions/{question}',       [QuestionController::class,  'destroy'])->name('questions.destroy');

    // Extras
    Route::get('/analytics',     fn() => view('instructor.analytics'))->name('analytics');
    Route::get('/question-bank', fn() => view('instructor.question-bank'))->name('question-bank');
});

// ── Student Routes ────────────────────────────────────────────
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', fn() => view('student.dashboard'))->name('dashboard');
    Route::get('/my-tests',  [StudentTestController::class, 'myTests'])->name('my-tests');
    Route::get('/test/{test}/instructions',   [StudentTestController::class, 'instructions'])->name('test.instructions');
    Route::post('/test/{test}/start',         [StudentTestController::class, 'start'])->name('test.start');
    Route::post('/attempt/{attempt}/answer',  [StudentTestController::class, 'saveAnswer'])->name('test.answer');
    Route::post('/attempt/{attempt}/violation',[StudentTestController::class,'violation'])->name('test.violation');
    Route::post('/attempt/{attempt}/submit',  [StudentTestController::class, 'submit'])->name('test.submit');
    Route::get('/result/{attempt}',           [StudentTestController::class, 'result'])->name('result');
    Route::get('/subscription',               [SubscriptionController::class,'index'])->name('subscription');
});

// ── Admin Routes ──────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                    [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users',                        [UserController::class,     'index'])->name('users');
    Route::post('/users/{user}/toggle',         [UserController::class,     'toggle'])->name('users.toggle');
    Route::delete('/users/{user}',              [UserController::class,     'destroy'])->name('users.delete');
    Route::get('/payments',                     [PaymentController::class,  'index'])->name('payments');
    Route::post('/payments/{payment}/approve',  [PaymentController::class,  'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject',   [PaymentController::class,  'reject'])->name('payments.reject');
    Route::get('/tests',    fn() => view('admin.tests.index'))->name('tests');
    Route::get('/settings', fn() => view('admin.settings'))->name('settings');

    // Plans CRUD
    Route::get('/plans',                [PlanController::class, 'index'])->name('plans');
    Route::post('/plans',               [PlanController::class, 'store'])->name('plans.store');
    Route::put('/plans/{plan}',         [PlanController::class, 'update'])->name('plans.update');
    Route::post('/plans/{plan}/toggle', [PlanController::class, 'toggle'])->name('plans.toggle');
});