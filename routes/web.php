<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else if (auth()->user()->role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware(['auth', 'verified', 'role:admin'])->name('admin.')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('students', StudentController::class);
        Route::resource('plans', PlanController::class);
        Route::resource('instructors', InstructorController::class);
        Route::patch('instructors/{instructor}/toggle-active', [InstructorController::class, 'toggleActive'])->name('instructors.toggle-active');
        Route::resource('payments', PaymentController::class);
        Route::resource('check-ins', CheckInController::class);
    });

    // Shared routes for both admins and instructors
    Route::middleware(['auth', 'instructor'])->group(function () {
        // Student Management
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/', [StudentController::class, 'store'])->name('store');
            Route::get('/{student}', [StudentController::class, 'show'])->name('show');
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [StudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
            
            // Student Payments
            Route::get('/{student}/payments', [PaymentController::class, 'studentPayments'])->name('payments');
            Route::get('/{student}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
            Route::post('/{student}/payments', [PaymentController::class, 'store'])->name('payments.store');
        });

        // Plans (view only for instructors)
        Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/{plan}', [PlanController::class, 'show'])->name('plans.show');

        // Check-ins
        Route::resource('check-ins', CheckInController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
