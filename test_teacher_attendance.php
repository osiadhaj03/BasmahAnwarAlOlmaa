<?php

/**
 * اختبار تسجيل الحضور للمعلم - نسخة جديدة
 */

echo "=== اختبار نظام تسجيل الحضور للمعلم ===\n\n";

// 1. فحص route students
echo "1. فحص routes:\n";
if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    
    if (strpos($routesContent, 'teacher.lessons.students') !== false) {
        echo "✅ Route teacher.lessons.students موجود\n";
    } else {
        echo "❌ Route teacher.lessons.students غير موجود\n";
    }
    
    if (strpos($routesContent, 'TeacherLessonController@getStudents') !== false || 
        strpos($routesContent, 'TeacherLessonController::class, \'getStudents\'') !== false) {
        echo "✅ Route يستدعي TeacherLessonController\n";
    } else {
        echo "❌ Route لا يستدعي TeacherLessonController\n";
    }
}

// 2. فحص TeacherLessonController
echo "\n2. فحص TeacherLessonController:\n";
if (file_exists('app/Http/Controllers/Teacher/TeacherLessonController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/Teacher/TeacherLessonController.php');
    
    if (strpos($controllerContent, 'function getStudents') !== false) {
        echo "✅ Method getStudents موجود في TeacherLessonController\n";
        
        // فحص response JSON
        if (strpos($controllerContent, 'response()->json') !== false) {
            echo "✅ Method يرجع JSON response\n";
        } else {
            echo "❌ Method لا يرجع JSON response\n";
        }
        
        // فحص التحقق من الصلاحيات
        if (strpos($controllerContent, '$lesson->teacher_id !== $teacher->id') !== false) {
            echo "✅ فحص الصلاحيات موجود\n";
        } else {
            echo "❌ فحص الصلاحيات غير موجود\n";
        }
    } else {
        echo "❌ Method getStudents غير موجود في TeacherLessonController\n";
    }
} else {
    echo "❌ TeacherLessonController غير موجود\n";
}

// 3. فحص واجهة تسجيل الحضور
echo "\n3. فحص واجهة تسجيل الحضور:\n";
if (file_exists('resources/views/admin/attendances/create.blade.php')) {
    $viewContent = file_get_contents('resources/views/admin/attendances/create.blade.php');
    
    if (strpos($viewContent, 'auth()->user()->role === \'teacher\'') !== false) {
        echo "✅ JavaScript يحدد المسار حسب دور المستخدم\n";
    } else {
        echo "❌ JavaScript لا يحدد المسار حسب دور المستخدم\n";
    }
    
    if (strpos($viewContent, '/teacher') !== false && strpos($viewContent, '/admin') !== false) {
        echo "✅ JavaScript يدعم كلاً من المعلم والمدير\n";
    } else {
        echo "❌ JavaScript لا يدعم كلاً من المعلم والمدير\n";
    }
    
    if (strpos($viewContent, 'teacher.attendances.store') !== false) {
        echo "✅ Form action يدعم المعلم\n";
    } else {
        echo "❌ Form action لا يدعم المعلم\n";
    }
} else {
    echo "❌ واجهة تسجيل الحضور غير موجودة\n";
}

// 4. فحص AttendanceController للمعلم
echo "\n4. فحص AttendanceController:\n";
if (file_exists('app/Http/Controllers/Admin/AttendanceController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/Admin/AttendanceController.php');
    
    // فحص create method للمعلم
    if (strpos($controllerContent, 'where(\'teacher_id\', $user->id)') !== false) {
        echo "✅ Create method يجلب دروس المعلم فقط\n";
    } else {
        echo "❌ Create method لا يجلب دروس المعلم فقط\n";
    }
    
    // فحص store method redirect
    if (strpos($controllerContent, 'teacher.attendances.index') !== false) {
        echo "✅ Store method يوجه المعلم للمكان الصحيح\n";
    } else {
        echo "❌ Store method لا يوجه المعلم للمكان الصحيح\n";
    }
    
    // فحص التحقق من الطلاب
    if (strpos($controllerContent, 'student_id') !== false) {
        echo "✅ التحقق من تسجيل الطالب في الدرس موجود\n";
    } else {
        echo "❌ التحقق من تسجيل الطالب في الدرس غير موجود\n";
    }
} else {
    echo "❌ AttendanceController غير موجود\n";
}

echo "\n=== تشخيص المشكلة المحتملة ===\n";
echo "إذا كان الخطأ 500 ما زال موجوداً، تحقق من:\n";
echo "1. أن الدرس المختار يحتوي على طلاب مسجلين\n";
echo "2. أن المعلم هو مالك الدرس\n";
echo "3. أن جدول lesson_student يحتوي على بيانات\n";
echo "4. أن relationship في Lesson model صحيح\n\n";

echo "=== خطوات الاختبار العملي ===\n";
echo "1. سجل دخول كمعلم\n";
echo "2. اذهب إلى /teacher/attendances/create\n";
echo "3. افتح Developer Tools (F12)\n";
echo "4. اذهب إلى Network tab\n";
echo "5. اختر درساً من القائمة\n";
echo "6. راقب طلب AJAX في Network tab\n";
echo "7. تحقق من response للطلب\n\n";

echo "=== انتهى الاختبار ===\n";
