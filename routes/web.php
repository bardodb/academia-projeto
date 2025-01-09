<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\StudentController as StaffStudentController;
use App\Http\Controllers\Staff\WorkoutController;
use App\Http\Controllers\Staff\ExerciseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
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
        $user = auth()->user();
        
        // Check role and profile existence
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'instructor':
                if ($user->instructor) {
                    return redirect()->route('instructor.dashboard');
                }
                break;
            case 'staff':
                return redirect()->route('staff.dashboard');
            case 'student':
                if ($user->student) {
                    return redirect()->route('student.dashboard');
                }
                break;
        }

        // If no valid role or missing profile, show default dashboard with error
        return view('dashboard')->with('error', 'Profile not found. Please contact support.');
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

    // Instructor routes
    Route::middleware(['auth', 'verified', 'role:instructor'])->name('instructor.')->prefix('instructor')->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/students', [StaffStudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [StaffStudentController::class, 'show'])->name('students.show');
        Route::get('/students/{student}/workouts', [WorkoutController::class, 'studentWorkouts'])->name('students.workouts');
        Route::post('/students/{student}/workouts', [WorkoutController::class, 'assignWorkout'])->name('students.assign-workout');
        Route::get('/students/{student}/progress', [StaffStudentController::class, 'progress'])->name('students.progress');
        
        // Workouts management
        Route::resource('workouts', WorkoutController::class);
        Route::resource('exercises', ExerciseController::class);
        
        // Check-ins
        Route::get('/check-ins', [CheckInController::class, 'index'])->name('check-ins.index');
        Route::post('/check-ins', [CheckInController::class, 'store'])->name('check-ins.store');
    });

    // Student routes
    Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        
        // Workouts
        Route::get('/workouts', [StudentDashboardController::class, 'workouts'])->name('workouts');
        Route::post('/workouts/{workout}/complete', [StudentDashboardController::class, 'completeWorkout'])->name('workouts.complete');
        
        // Progress tracking
        Route::get('/progress', [StudentDashboardController::class, 'progress'])->name('progress');
        
        // Payments
        Route::get('/payments', [StudentDashboardController::class, 'payments'])->name('payments');
        
        // Check-ins
        Route::post('/check-in', [StudentDashboardController::class, 'checkIn'])->name('check-in');
        Route::get('/check-in-history', [StudentDashboardController::class, 'checkInHistory'])->name('check-in-history');
        
        // Profile
        Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [StudentDashboardController::class, 'updateProfile'])->name('profile.update');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', StaffStudentController::class);
    Route::resource('workouts', WorkoutController::class);
    Route::resource('exercises', ExerciseController::class);
    
    // Student monitoring
    Route::get('/students/{student}/workouts', [WorkoutController::class, 'studentWorkouts'])->name('students.workouts');
    Route::get('/students/{student}/progress', [StaffStudentController::class, 'progress'])->name('students.progress');
    Route::post('/students/{student}/workouts', [WorkoutController::class, 'assignWorkout'])->name('students.assign-workout');
});

require __DIR__.'/auth.php';
