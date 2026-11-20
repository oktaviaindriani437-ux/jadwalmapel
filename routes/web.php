<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentManagementController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubstituteManagementController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }

    return app(AuthController::class)->showLogin();
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::match(['GET', 'POST'], '/login/quick/{role}', [AuthController::class, 'quickLogin'])->name('login.quick');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Direct & Permanent Admin Portal Access
Route::get('/admin', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login', ['portal' => 'admin']);
})->name('admin.entry');

Route::get('/admin/login', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login', ['portal' => 'admin']);
})->name('admin.login.direct');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('schedules');
    Route::post('/jadwal', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/jadwal/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/jadwal/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('/mata-pelajaran', [SubjectController::class, 'index'])->name('subjects');
    Route::post('/mata-pelajaran', [SubjectController::class, 'store'])->name('subjects.store');
    Route::put('/mata-pelajaran/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/mata-pelajaran/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    Route::get('/guru', [TeacherController::class, 'index'])->name('teachers');
    Route::post('/guru', [TeacherController::class, 'store'])->name('teachers.store');
    Route::put('/guru/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/guru/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    Route::get('/kelas', [ClassController::class, 'index'])->name('classes');
    Route::post('/kelas', [ClassController::class, 'store'])->name('classes.store');
    Route::put('/kelas/{class}', [ClassController::class, 'update'])->name('classes.update');
    Route::delete('/kelas/{class}', [ClassController::class, 'destroy'])->name('classes.destroy');

    Route::get('/siswa', [StudentManagementController::class, 'index'])->name('students');
    Route::post('/siswa', [StudentManagementController::class, 'store'])->name('students.store');
    Route::put('/siswa/{student}', [StudentManagementController::class, 'update'])->name('students.update');
    Route::delete('/siswa/{student}', [StudentManagementController::class, 'destroy'])->name('students.destroy');

    Route::get('/profil', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profil', [AdminController::class, 'updateProfile'])->name('profile.update');

    Route::get('/guru-inval', function () {
        return redirect()->route('admin.dashboard');
    })->name('substitutes');
    Route::post('/guru-inval', [SubstituteManagementController::class, 'store'])->name('substitutes.store');
    Route::put('/guru-inval/{substitute}', [SubstituteManagementController::class, 'update'])->name('substitutes.update');
    Route::put('/guru-inval/{substitute}/status', [SubstituteManagementController::class, 'updateStatus'])->name('substitutes.update-status');
    Route::delete('/guru-inval/{substitute}', [SubstituteManagementController::class, 'destroy'])->name('substitutes.destroy');
});

Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [StudentController::class, 'schedule'])->name('schedule');
    Route::get('/mata-pelajaran', [StudentController::class, 'subjects'])->name('subjects');
    Route::get('/guru', [StudentController::class, 'teachers'])->name('teachers');
    Route::get('/profil', [StudentController::class, 'profile'])->name('profile');
    Route::put('/profil', [StudentController::class, 'updateProfile'])->name('profile.update');
});
