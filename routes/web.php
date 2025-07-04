<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\StudentRegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherLessonController;
use App\Http\Controllers\QRCodeController;

Route::get('/', function () {
    return view('welcome-premium');
})->name('welcome.premium');

Route::get('/welcome-simple', function () {
    return view('welcome-simple');
})->name('welcome.simple');

Route::get('/welcome-premium', function () {
    return view('welcome-premium');
})->name('welcome.premium');

// Legacy route for old welcome page
Route::get('/welcome-basmah', function () {
    return view('welcome-basmah');
});

// Student Registration Routes (Public - Students Only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [StudentRegisterController::class, 'showRegistrationForm'])->name('student.register.form');
    Route::post('/register', [StudentRegisterController::class, 'register'])->name('student.register');
});

// Default login route redirects to admin login
Route::get('/login', function() {
    return redirect()->route('admin.login');
})->name('login');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    
    // Protected Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // User Management Routes
        Route::resource('users', UserController::class)->names('admin.users');
        Route::get('teachers', [UserController::class, 'teachers'])->name('admin.users.teachers');
        Route::get('students', [UserController::class, 'students'])->name('admin.users.students');
        
        // Lesson Management Routes
        Route::resource('lessons', LessonController::class)->names('admin.lessons');
        
        // Attendance Routes (view and edit only - no creation)
        Route::get('attendances', [AttendanceController::class, 'index'])->name('admin.attendances.index');
        Route::get('attendances/{attendance}', [AttendanceController::class, 'show'])->name('admin.attendances.show');
        Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('admin.attendances.edit');
        Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('admin.attendances.update');
        Route::delete('attendances/{attendance}', [AttendanceController::class, 'destroy'])->name('admin.attendances.destroy');
        Route::get('attendances-reports', [AttendanceController::class, 'reports'])->name('admin.attendances.reports');
        Route::get('lessons/{lesson}/students', [AttendanceController::class, 'getStudents'])
            ->name('admin.lessons.students');
        Route::get('students/{student}/lessons', [AttendanceController::class, 'getStudentLessons'])
            ->name('admin.students.lessons');
        
        // QR Code Routes for Teachers/Admins
        Route::get('lessons/{lesson}/qr-display', [QRCodeController::class, 'displayQR'])
            ->name('admin.lessons.qr.display');
        Route::get('lessons/{lesson}/qr-info', [QRCodeController::class, 'getTokenInfo'])
            ->name('admin.lessons.qr.info');
        Route::post('lessons/{lesson}/qr-refresh', [QRCodeController::class, 'refreshToken'])
            ->name('admin.lessons.qr.refresh');
    });
});

// Teacher Routes
Route::middleware('teacher')->group(function () {
    Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'dashboard'])->name('teacher.dashboard');
    
    // Teacher Lesson Management
    Route::resource('/teacher/lessons', TeacherLessonController::class, [
        'as' => 'teacher'
    ]);
    Route::get('/teacher/lessons/{lesson}/manage-students', [TeacherLessonController::class, 'manageStudents'])
        ->name('teacher.lessons.manage-students');
    Route::post('/teacher/lessons/{lesson}/add-student', [TeacherLessonController::class, 'addStudent'])
        ->name('teacher.lessons.add-student');
    Route::delete('/teacher/lessons/{lesson}/remove-student/{student}', [TeacherLessonController::class, 'removeStudent'])
        ->name('teacher.lessons.remove-student');
    Route::delete('/teacher/lessons/{lesson}/remove-students', [TeacherLessonController::class, 'removeStudents'])
        ->name('teacher.lessons.remove-students');
    Route::delete('/teacher/lessons/{lesson}/remove-all-students', [TeacherLessonController::class, 'removeAllStudents'])
        ->name('teacher.lessons.remove-all-students');
    
    // Teacher Attendance Management (view and edit only)
    Route::get('/teacher/attendances', [AttendanceController::class, 'index'])->name('teacher.attendances.index');
    Route::get('/teacher/attendances/lesson/{lesson}', [AttendanceController::class, 'lessonAttendance'])
        ->name('teacher.attendances.lesson');
    Route::get('/teacher/attendances/lesson/{lesson}/student/{student}', [AttendanceController::class, 'studentAttendance'])
        ->name('teacher.attendances.student');
    Route::put('/teacher/attendances/{attendance}', [AttendanceController::class, 'update'])->name('teacher.attendances.update');
    Route::get('/teacher/lessons/{lesson}/students', [TeacherLessonController::class, 'getStudents'])
        ->name('teacher.lessons.students');
    Route::get('/teacher/students/{student}/lessons', [TeacherLessonController::class, 'getStudentLessons'])
        ->name('teacher.students.lessons');
        
    // Teacher QR Code Routes (using admin routes)
    Route::get('/teacher/lessons/{lesson}/qr-display', [QRCodeController::class, 'displayQR'])
        ->name('teacher.lessons.qr.display');
    Route::get('/teacher/lessons/{lesson}/qr-info', [QRCodeController::class, 'getTokenInfo'])
        ->name('teacher.lessons.qr.info');
    Route::post('/teacher/lessons/{lesson}/qr-refresh', [QRCodeController::class, 'refreshToken'])
        ->name('teacher.lessons.qr.refresh');
});

// Student Routes
Route::middleware('student')->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/check-in', [StudentController::class, 'checkIn'])->name('student.checkin');
    
    // QR Code Scanner for Students
    Route::get('/qr-scanner', [QRCodeController::class, 'scanner'])->name('student.qr.scanner');
});

// Public QR Code Routes (accessible by both students and teachers)
Route::get('/qr-generate/{lesson}', [QRCodeController::class, 'generateQR'])->name('qr.generate');
Route::get('/attendance/scan', [QRCodeController::class, 'scanAttendance'])->name('attendance.scan');

// API Routes
Route::get('/api/lessons-simple', function() {
        return \App\Models\Lesson::select('id', 'name', 'subject', 'day_of_week', 'start_time')
            ->limit(20)
            ->get()
            ->map(function($lesson) {
                return [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'subject' => $lesson->subject,
                    'day_of_week' => $lesson->day_of_week,
                    'start_time' => $lesson->start_time ? $lesson->start_time->format('H:i') : 'غير محدد'
                ];
            });
    });
    
    Route::get('/qr-test-page', function() {
        return view('qr-test');
    })->name('qr.test.page');

// Testing Routes for QR Code (only in development)
if (env('APP_ENV') === 'local') {
    Route::get('/test-qr/{lesson}', function(\App\Models\Lesson $lesson) {
        try {
            // إجبار توليد QR Token
            $qrToken = $lesson->forceGenerateQRToken();
            
            // إنشاء QR Code
            $scanUrl = url("/attendance/scan?token=" . urlencode($qrToken->token));
            
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($scanUrl);

            return response($qrCode)
                ->header('Content-Type', 'image/svg+xml');
                
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    })->name('test.qr');
    
    Route::get('/quick-qr/{lesson}', [\App\Http\Controllers\QRCodeController::class, 'quickGenerate'])
        ->name('quick.qr');
}
