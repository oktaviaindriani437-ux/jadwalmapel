<?php

use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\SubstitutionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Schedule Timetable Grid Endpoints
    Route::get('/schedules/timetable/class/{class_id}', [ScheduleController::class, 'timetableByClass'])->name('api.v1.schedules.timetable.class');
    Route::get('/schedules/timetable/teacher/{teacher_id}', [ScheduleController::class, 'timetableByTeacher'])->name('api.v1.schedules.timetable.teacher');

    // Term / Semester Schedule Cloning
    Route::post('/schedules/clone-term', [ScheduleController::class, 'cloneTerm'])->name('api.v1.schedules.clone-term');

    // Export Endpoints
    Route::get('/schedules/export/pdf', [ScheduleController::class, 'exportPdf'])->name('api.v1.schedules.export.pdf');
    Route::get('/schedules/export/excel', [ScheduleController::class, 'exportExcel'])->name('api.v1.schedules.export.excel');

    // CRUD Schedule Endpoints
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('api.v1.schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('api.v1.schedules.store');
    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('api.v1.schedules.show');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('api.v1.schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('api.v1.schedules.destroy');

    // Substitutions (Inval) Endpoints
    Route::get('/substitutions/available-teachers', [SubstitutionController::class, 'availableTeachers'])->name('api.v1.substitutions.available-teachers');
    Route::get('/substitutions', [SubstitutionController::class, 'index'])->name('api.v1.substitutions.index');
    Route::post('/substitutions', [SubstitutionController::class, 'store'])->name('api.v1.substitutions.store');
    Route::get('/substitutions/{substitution}', [SubstitutionController::class, 'show'])->name('api.v1.substitutions.show');
    Route::match(['put', 'patch'], '/substitutions/{substitution}/status', [SubstitutionController::class, 'updateStatus'])->name('api.v1.substitutions.update-status');
});
