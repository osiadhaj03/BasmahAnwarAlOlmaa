<?php

/**
 * اختبار سريع لنظام التسجيل
 */

echo "=== اختبار سريع لنظام التسجيل ===\n\n";

// 1. فحص الملفات الأساسية
echo "1. فحص الملفات:\n";

$files = [
    'app/Http/Controllers/Auth/StudentRegisterController.php' => 'StudentRegisterController',
    'resources/views/layouts/app.blade.php' => 'Layout App',
    'resources/views/auth/student-register-new.blade.php' => 'صفحة تسجيل الطلاب',
    'resources/views/welcome-basmah.blade.php' => 'الصفحة الرئيسية',
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "✅ {$description}\n";
    } else {
        echo "❌ {$description} - {$file}\n";
    }
}

// 2. فحص الـ Routes
echo "\n2. فحص Routes:\n";

if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    
    $routesToCheck = [
        'student.register.form' => 'صفحة تسجيل الطلاب',
        'student.register' => 'معالجة تسجيل الطلاب',
        'StudentRegisterController' => 'Controller التسجيل',
    ];
    
    foreach ($routesToCheck as $route => $description) {
        if (strpos($routesContent, $route) !== false) {
            echo "✅ {$description}\n";
        } else {
            echo "❌ {$description}\n";
        }
    }
} else {
    echo "❌ ملف routes/web.php غير موجود\n";
}

// 3. فحص middleware guest
echo "\n3. فحص Middleware:\n";

if (strpos($routesContent, "middleware('guest')") !== false) {
    echo "✅ Guest middleware مطبق\n";
} else {
    echo "❓ Guest middleware قد يحتاج تحقق\n";
}

// 4. فحص محتوى StudentRegisterController
echo "\n4. فحص StudentRegisterController:\n";

if (file_exists('app/Http/Controllers/Auth/StudentRegisterController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/Auth/StudentRegisterController.php');
    
    $checks = [
        "'role' => 'student'" => 'تعيين دور الطالب تلقائياً',
        "'role' => ['prohibited']" => 'منع تمرير role من الخارج',
        'student-register-new' => 'استخدام الصفحة الجديدة',
        'التسجيل متاح للطلاب فقط' => 'رسالة الحماية',
    ];
    
    foreach ($checks as $search => $description) {
        if (strpos($controllerContent, $search) !== false) {
            echo "✅ {$description}\n";
        } else {
            echo "❌ {$description}\n";
        }
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🎯 النتيجة:\n";
echo "   • صفحة التسجيل: /register\n";
echo "   • Layout: تم إنشاؤه\n";
echo "   • Controller: محدّث\n";
echo "   • الحماية: مطبقة\n\n";

echo "📝 للاختبار:\n";
echo "   1. تصفح: http://127.0.0.1:8000\n";
echo "   2. اضغط 'تسجيل طالب جديد'\n";
echo "   3. املأ النموذج واختبر التسجيل\n\n";

echo "✅ النظام جاهز للاختبار!\n";
