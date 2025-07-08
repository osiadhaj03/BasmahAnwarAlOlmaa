<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceSessionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Attendance Sessions API Routes
Route::middleware('auth:sanctum')->group(function () {
    // Teacher/Admin routes for managing attendance sessions
    Route::post('lessons/{lesson}/attendance/start-session', [AttendanceSessionController::class, 'startSession'])
        ->name('api.lessons.attendance.start-session');
    
    Route::get('lessons/{lesson}/attendance/current-session', [AttendanceSessionController::class, 'getCurrentSession'])
        ->name('api.lessons.attendance.current-session');
    
    Route::post('lessons/{lesson}/attendance/stop-session', [AttendanceSessionController::class, 'stopSession'])
        ->name('api.lessons.attendance.stop-session');
    
    // Student route for submitting attendance code
    Route::post('lessons/{lesson}/attendance/submit-code', [AttendanceSessionController::class, 'submitCode'])
        ->name('api.lessons.attendance.submit-code');
});
