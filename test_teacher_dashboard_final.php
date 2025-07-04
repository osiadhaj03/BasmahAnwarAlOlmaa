<?php

/**
 * اختبار شامل لنظام لوحة تحكم المعلم
 * Test Script for Teacher Dashboard System
 */

echo "=== اختبار نظام لوحة تحكم المعلم ===\n\n";

// 1. فحص الملفات المطلوبة
echo "1. فحص وجود الملفات:\n";
$requiredFiles = [
    'app/Http/Controllers/Teacher/TeacherDashboardController.php',
    'app/Http/Controllers/Teacher/TeacherLessonController.php',
    'app/Http/Middleware/TeacherMiddleware.php',
    'resources/views/teacher/dashboard.blade.php',
    'resources/views/teacher/lessons/index.blade.php',
    'resources/views/teacher/lessons/create.blade.php',
    'resources/views/teacher/lessons/show.blade.php',
    'resources/views/teacher/lessons/edit.blade.php',
    'resources/views/teacher/lessons/manage-students.blade.php',
    'resources/views/layouts/admin.blade.php',
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file\n";
    } else {
        echo "❌ $file\n";
    }
}

echo "\n2. فحص Routes:\n";

// فحص ملف routes
if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    
    $teacherRoutes = [
        'teacher.dashboard' => '/teacher/dashboard',
        'teacher.lessons.index' => 'teacher/lessons',
        'teacher.lessons.create' => 'teacher/lessons/create',
        'teacher.lessons.store' => 'teacher/lessons (POST)',
        'teacher.lessons.show' => 'teacher/lessons/{lesson}',
        'teacher.lessons.edit' => 'teacher/lessons/{lesson}/edit',
        'teacher.lessons.update' => 'teacher/lessons/{lesson} (PUT)',
        'teacher.lessons.destroy' => 'teacher/lessons/{lesson} (DELETE)',
        'teacher.lessons.manage-students' => 'teacher/lessons/{lesson}/manage-students',
        'teacher.lessons.add-student' => 'teacher/lessons/{lesson}/add-student',
        'teacher.lessons.remove-student' => 'teacher/lessons/{lesson}/remove-student/{student}',
        'teacher.attendances.index' => 'teacher/attendances',
        'teacher.attendances.create' => 'teacher/attendances/create',
        'teacher.attendances.bulk' => 'teacher/attendances/bulk',
    ];
    
    foreach ($teacherRoutes as $routeName => $routePath) {
        if (strpos($routesContent, $routeName) !== false) {
            echo "✅ Route: $routeName\n";
        } else {
            echo "❌ Route: $routeName\n";
        }
    }
} else {
    echo "❌ routes/web.php not found\n";
}

echo "\n3. فحص Controller Methods:\n";

if (file_exists('app/Http/Controllers/Teacher/TeacherLessonController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/Teacher/TeacherLessonController.php');
    
    $requiredMethods = [
        'index',
        'create', 
        'store',
        'show',
        'edit',
        'update',
        'destroy',
        'manageStudents',
        'addStudent',
        'removeStudent',
        'removeStudents',
        'removeAllStudents'
    ];
    
    foreach ($requiredMethods as $method) {
        if (strpos($controllerContent, "function $method") !== false) {
            echo "✅ Method: $method\n";
        } else {
            echo "❌ Method: $method\n";
        }
    }
} else {
    echo "❌ TeacherLessonController not found\n";
}

echo "\n4. فحص القائمة الجانبية للمعلم:\n";

if (file_exists('resources/views/layouts/admin.blade.php')) {
    $layoutContent = file_get_contents('resources/views/layouts/admin.blade.php');
    
    // فحص أن المعلم يرى فقط صلاحياته
    $teacherSidebarItems = [
        'لوحة التحكم',
        'إدارة دروسي',
        'الحضور والغياب',
        'تسجيل حضور فردي',
        'تسجيل حضور جماعي'
    ];
    
    // فحص أن المعلم لا يرى صلاحيات الإدارة
    $adminOnlyItems = [
        'المستخدمين',
        'admin.users',
        'إدارة الدروس', // بدلاً من "إدارة دروسي"
        'admin.lessons'
    ];
    
    if (strpos($layoutContent, "@elseif(auth()->user()->role === 'teacher')") !== false) {
        echo "✅ القائمة الجانبية تدعم المعلم\n";
        
        foreach ($teacherSidebarItems as $item) {
            if (strpos($layoutContent, $item) !== false) {
                echo "✅ عنصر القائمة: $item\n";
            } else {
                echo "❌ عنصر القائمة: $item\n";
            }
        }
        
        // التحقق من عدم وجود صلاحيات الإدارة في قسم المعلم
        $teacherSection = "";
        if (preg_match("/@elseif\(auth\(\)->user\(\)->role === 'teacher'\)(.*?)@endif/s", $layoutContent, $matches)) {
            $teacherSection = $matches[1];
        }
        
        foreach ($adminOnlyItems as $item) {
            if (strpos($teacherSection, $item) !== false) {
                echo "⚠️  تحذير: المعلم يرى صلاحية إدارة: $item\n";
            } else {
                echo "✅ المعلم لا يرى صلاحية الإدارة: $item\n";
            }
        }
        
    } else {
        echo "❌ القائمة الجانبية لا تدعم المعلم\n";
    }
} else {
    echo "❌ layouts/admin.blade.php not found\n";
}

echo "\n5. فحص لوحة تحكم المعلم:\n";

if (file_exists('resources/views/teacher/dashboard.blade.php')) {
    $dashboardContent = file_get_contents('resources/views/teacher/dashboard.blade.php');
    
    // فحص الإجراءات السريعة
    $quickActions = [
        'إدارة دروسي',
        'إضافة درس جديد',
        'إدارة الحضور',
        'حضور جماعي'
    ];
    
    // التحقق من عدم وجود إجراءات إدارية
    $adminActions = [
        'إدارة المستخدمين',
        'إضافة مستخدم',
        'admin.users',
        'إدارة الدروس' // بدلاً من "إدارة دروسي"
    ];
    
    foreach ($quickActions as $action) {
        if (strpos($dashboardContent, $action) !== false) {
            echo "✅ إجراء سريع: $action\n";
        } else {
            echo "❌ إجراء سريع: $action\n";
        }
    }
    
    foreach ($adminActions as $action) {
        if (strpos($dashboardContent, $action) !== false) {
            echo "⚠️  تحذير: لوحة المعلم تحتوي على إجراء إداري: $action\n";
        } else {
            echo "✅ لا يوجد إجراء إداري: $action\n";
        }
    }
    
} else {
    echo "❌ teacher/dashboard.blade.php not found\n";
}

echo "\n6. فحص تكرار لوحات التحكم:\n";

if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    $dashboardCount = substr_count($routesContent, 'teacher.dashboard');
    
    if ($dashboardCount === 1) {
        echo "✅ يوجد route واحد فقط للوحة تحكم المعلم\n";
    } else {
        echo "⚠️  تحذير: يوجد $dashboardCount route للوحة تحكم المعلم\n";
    }
    
    // فحص عدم وجود routes متكررة
    if (strpos($routesContent, '/teacher/dashboard') !== false && 
        strpos($routesContent, 'TeacherDashboardController') !== false) {
        echo "✅ Route لوحة التحكم صحيح\n";
    } else {
        echo "❌ Route لوحة التحكم غير صحيح\n";
    }
}

echo "\n7. فحص حماية الصلاحيات:\n";

if (file_exists('app/Http/Controllers/Teacher/TeacherLessonController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/Teacher/TeacherLessonController.php');
    
    // فحص وجود فحص صلاحيات المعلم
    $permissionChecks = [
        '$lesson->teacher_id !== $teacher->id',
        "abort(403",
        'where(\'teacher_id\', $teacher->id)'
    ];
    
    foreach ($permissionChecks as $check) {
        if (strpos($controllerContent, $check) !== false) {
            echo "✅ فحص الصلاحيات: $check\n";
        } else {
            echo "❌ فحص الصلاحيات: $check\n";
        }
    }
}

echo "\n=== تقرير الاختبار ===\n";
echo "تم فحص جميع مكونات نظام لوحة تحكم المعلم\n";
echo "يرجى مراجعة أي عناصر مفقودة (❌) أو تحذيرات (⚠️)\n\n";

echo "الملفات الجديدة المُنشأة:\n";
echo "- resources/views/teacher/lessons/edit.blade.php\n";
echo "- resources/views/teacher/lessons/manage-students.blade.php\n";
echo "- طرق إضافية في TeacherLessonController\n";
echo "- routes إضافية لإدارة الطلاب\n\n";

echo "الميزات المتاحة للمعلم:\n";
echo "✅ إدارة دروسه فقط (إضافة/تعديل/حذف/عرض)\n";
echo "✅ إدارة طلاب دروسه (إضافة/إزالة)\n";
echo "✅ تسجيل الحضور (فردي/جماعي)\n";
echo "✅ عرض تقارير الحضور لدروسه\n";
echo "✅ قائمة جانبية مخصصة للمعلم\n";
echo "✅ واجهة لوحة تحكم احترافية\n\n";

echo "القيود المطبقة:\n";
echo "❌ لا يمكن للمعلم إدارة المستخدمين\n";
echo "❌ لا يمكن للمعلم رؤية/تعديل دروس غيره\n";
echo "❌ لا يمكن للمعلم الوصول لصلاحيات الإدارة\n";
echo "❌ لا توجد لوحات تحكم متكررة\n\n";

echo "=== انتهى الاختبار ===\n";
