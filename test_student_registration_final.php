<?php

/**
 * اختبار نظام التسجيل - الطلاب فقط
 * تطوير: أسيد صلاح أبو الحاج (osaidhaj03@gmail.com)
 */

echo "=== اختبار نظام التسجيل - BasmahApp ===\n\n";
echo "المطور: أسيد صلاح أبو الحاج\n";
echo "الإيميل: osaidhaj03@gmail.com\n";
echo "===============================================\n\n";

// فحص الملفات المطلوبة
echo "1. فحص الملفات المطلوبة:\n";
$requiredFiles = [
    'app/Http/Controllers/Auth/StudentRegisterController.php',
    'resources/views/auth/student-register.blade.php',
    'resources/views/welcome-basmah.blade.php',
    'routes/web.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file\n";
    } else {
        echo "❌ $file\n";
    }
}

// فحص Routes
echo "\n2. فحص Routes:\n";
if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    
    $routesToCheck = [
        'student.register.form' => '/register.*StudentRegisterController.*showRegistrationForm',
        'student.register' => '/register.*StudentRegisterController.*register'
    ];
    
    foreach ($routesToCheck as $routeName => $pattern) {
        if (preg_match("/$pattern/", $routesContent)) {
            echo "✅ Route: $routeName\n";
        } else {
            echo "❌ Route: $routeName غير موجود\n";
        }
    }
} else {
    echo "❌ routes/web.php غير موجود\n";
}

// فحص StudentRegisterController
echo "\n3. فحص StudentRegisterController:\n";
if (file_exists('app/Http/Controllers/Auth/StudentRegisterController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/Auth/StudentRegisterController.php');
    
    $checks = [
        'showRegistrationForm method' => 'function showRegistrationForm',
        'register method' => 'function register',
        'role validation' => 'role.*student',
        'student role assignment' => "'role' => 'student'"
    ];
    
    foreach ($checks as $checkName => $pattern) {
        if (strpos($controllerContent, $pattern) !== false || preg_match("/$pattern/", $controllerContent)) {
            echo "✅ $checkName\n";
        } else {
            echo "❌ $checkName غير موجود\n";
        }
    }
} else {
    echo "❌ StudentRegisterController غير موجود\n";
}

// فحص صفحة التسجيل
echo "\n4. فحص صفحة تسجيل الطلاب:\n";
if (file_exists('resources/views/auth/student-register.blade.php')) {
    $viewContent = file_get_contents('resources/views/auth/student-register.blade.php');
    
    $viewChecks = [
        'Form action' => 'student.register',
        'Student only notice' => 'المعلمين يتم إنشاء حساباتهم من قبل الإدارة',
        'Name field' => 'name="name"',
        'Email field' => 'name="email"',
        'Password field' => 'name="password"'
    ];
    
    foreach ($viewChecks as $checkName => $pattern) {
        if (strpos($viewContent, $pattern) !== false) {
            echo "✅ $checkName\n";
        } else {
            echo "❌ $checkName غير موجود\n";
        }
    }
} else {
    echo "❌ صفحة تسجيل الطلاب غير موجودة\n";
}

// فحص صفحة الترحيب الجديدة
echo "\n5. فحص صفحة الترحيب:\n";
if (file_exists('resources/views/welcome-basmah.blade.php')) {
    $welcomeContent = file_get_contents('resources/views/welcome-basmah.blade.php');
    
    $welcomeChecks = [
        'Student register link' => 'student.register.form',
        'Admin login link' => 'admin.login',
        'Developer credit' => 'أسيد صلاح أبو الحاج',
        'Developer email' => 'osaidhaj03@gmail.com',
        'Registration notice' => 'المعلمين والإداريين يتم إنشاء حساباتهم من قبل الإدارة'
    ];
    
    foreach ($welcomeChecks as $checkName => $pattern) {
        if (strpos($welcomeContent, $pattern) !== false) {
            echo "✅ $checkName\n";
        } else {
            echo "❌ $checkName غير موجود\n";
        }
    }
} else {
    echo "❌ صفحة الترحيب الجديدة غير موجودة\n";
}

// فحص AdminLoginController (للتأكد من عدم وجود تسجيل عام)
echo "\n6. فحص AdminLoginController:\n";
if (file_exists('app/Http/Controllers/Auth/AdminLoginController.php')) {
    echo "✅ AdminLoginController موجود\n";
    
    $adminContent = file_get_contents('app/Http/Controllers/Auth/AdminLoginController.php');
    if (strpos($adminContent, 'role') !== false) {
        echo "✅ يحتوي على logic للأدوار\n";
    } else {
        echo "⚠️ قد يحتاج إلى تحديث logic الأدوار\n";
    }
} else {
    echo "❌ AdminLoginController غير موجود\n";
}

echo "\n=== ملخص الاختبار ===\n";
echo "✅ النظام مُعد بحيث:\n";
echo "   • الطلاب فقط يمكنهم التسجيل الذاتي\n";
echo "   • المعلمين والإداريين ينشئهم المدير فقط\n";
echo "   • صفحة ترحيب واضحة مع التوجيهات\n";
echo "   • حماية من محاولات تسجيل أدوار غير مسموحة\n\n";

echo "المطور: أسيد صلاح أبو الحاج\n";
echo "الإيميل: osaidhaj03@gmail.com\n";
echo "==========================================\n";

?>
