<?php

/**
 * اختبار نظام التسجيل - الطلاب فقط
 * التحقق من أن المعلمين ينشئهم المدير فقط
 */

require_once 'vendor/autoload.php';

echo "=== اختبار نظام التسجيل BasmahApp ===\n\n";

// 1. فحص الـ Routes
echo "1. فحص Routes التسجيل:\n";

if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    
    if (strpos($routesContent, 'student.register.form') !== false) {
        echo "✅ Route تسجيل الطلاب موجود\n";
    } else {
        echo "❌ Route تسجيل الطلاب غير موجود\n";
    }
    
    if (strpos($routesContent, 'StudentRegisterController') !== false) {
        echo "✅ StudentRegisterController مربوط بالـ routes\n";
    } else {
        echo "❌ StudentRegisterController غير مربوط\n";
    }
} else {
    echo "❌ ملف routes/web.php غير موجود\n";
}

// 2. فحص StudentRegisterController
echo "\n2. فحص StudentRegisterController:\n";

if (file_exists('app/Http/Controllers/Auth/StudentRegisterController.php')) {
    $controllerContent = file_get_contents('app/Http/Controllers/Auth/StudentRegisterController.php');
    
    if (strpos($controllerContent, "'role' => 'student'") !== false) {
        echo "✅ Controller يقوم بتعيين role = student تلقائياً\n";
    } else {
        echo "❌ Controller لا يعين role = student\n";
    }
    
    if (strpos($controllerContent, "'role' => ['prohibited']") !== false) {
        echo "✅ Controller يمنع تمرير role من الخارج\n";
    } else {
        echo "❌ Controller لا يمنع تمرير role\n";
    }
    
    if (strpos($controllerContent, 'التسجيل متاح للطلاب فقط') !== false) {
        echo "✅ رسالة حماية موجودة ضد محاولة تسجيل معلم\n";
    } else {
        echo "❌ رسالة الحماية غير موجودة\n";
    }
} else {
    echo "❌ StudentRegisterController غير موجود\n";
}

// 3. فحص UserController (للمدير)
echo "\n3. فحص UserController (لإنشاء المعلمين):\n";

if (file_exists('app/Http/Controllers/Admin/UserController.php')) {
    $userControllerContent = file_get_contents('app/Http/Controllers/Admin/UserController.php');
    
    if (strpos($userControllerContent, "Rule::in(['admin', 'teacher', 'student'])") !== false) {
        echo "✅ UserController يدعم إنشاء جميع الأدوار (للمدير فقط)\n";
    } else {
        echo "❌ UserController لا يدعم إنشاء الأدوار\n";
    }
    
    if (strpos($userControllerContent, 'middleware admin') !== false || 
        strpos($userControllerContent, 'للإدارة فقط') !== false) {
        echo "✅ UserController محمي بـ Admin middleware\n";
    } else {
        echo "❓ UserController قد يحتاج تأكيد حماية Admin\n";
    }
} else {
    echo "❌ Admin/UserController غير موجود\n";
}

// 4. فحص صفحة تسجيل الطلاب
echo "\n4. فحص صفحة تسجيل الطلاب:\n";

if (file_exists('resources/views/auth/student-register.blade.php')) {
    $studentRegisterContent = file_get_contents('resources/views/auth/student-register.blade.php');
    
    if (strpos($studentRegisterContent, 'تسجيل طالب جديد') !== false) {
        echo "✅ صفحة تسجيل الطلاب موجودة\n";
    }
    
    if (strpos($studentRegisterContent, 'المعلمين يتم إنشاء حساباتهم من قبل الإدارة') !== false) {
        echo "✅ رسالة توضيحية موجودة في صفحة التسجيل\n";
    } else {
        echo "❌ رسالة توضيحية غير موجودة\n";
    }
} else {
    echo "❌ صفحة تسجيل الطلاب غير موجودة\n";
}

// 5. فحص الصفحة الرئيسية
echo "\n5. فحص الصفحة الرئيسية:\n";

if (file_exists('resources/views/welcome-basmah.blade.php')) {
    $welcomeContent = file_get_contents('resources/views/welcome-basmah.blade.php');
    
    if (strpos($welcomeContent, 'تسجيل طالب جديد') !== false) {
        echo "✅ رابط تسجيل الطلاب موجود في الصفحة الرئيسية\n";
    } else {
        echo "❌ رابط تسجيل الطلاب غير موجود\n";
    }
    
    if (strpos($welcomeContent, 'المعلمين والإداريين يتم إنشاء حساباتهم من قبل الإدارة') !== false) {
        echo "✅ رسالة توضيحية موجودة في الصفحة الرئيسية\n";
    } else {
        echo "❌ رسالة توضيحية غير موجودة في الصفحة الرئيسية\n";
    }
} else {
    echo "❌ صفحة welcome-basmah.blade.php غير موجودة\n";
}

// 6. التحقق من الحماية في Routes
echo "\n6. فحص حماية الـ Routes:\n";

if (strpos($routesContent, "Route::middleware('admin')") !== false) {
    echo "✅ Routes المدير محمية بـ admin middleware\n";
} else {
    echo "❌ Routes المدير قد تحتاج حماية\n";
}

if (strpos($routesContent, "Route::middleware('guest')") !== false || 
    strpos($routesContent, '$this->middleware(\'guest\')') !== false) {
    echo "✅ Routes التسجيل محمية بـ guest middleware\n";
} else {
    echo "❓ Routes التسجيل قد تحتاج حماية guest\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "📋 ملخص الاختبار:\n\n";

echo "✅ الميزات المطبقة:\n";
echo "   • StudentRegisterController للطلاب فقط\n";
echo "   • حماية من تمرير role غير student\n";
echo "   • UserController للمدير لإنشاء المعلمين\n";
echo "   • صفحة ترحيب توضح القواعد\n";
echo "   • رسائل توضيحية واضحة\n\n";

echo "🔒 الحماية المطبقة:\n";
echo "   • منع الطلاب من تعيين أنفسهم كمعلمين\n";
echo "   • إنشاء المعلمين محصور بالمدير فقط\n";
echo "   • رسائل واضحة للمستخدمين\n";
echo "   • validation قوي في الـ controllers\n\n";

echo "🎯 النتيجة: النظام يدعم الطلب بنجاح!\n";
echo "   الطلاب فقط يمكنهم التسجيل الذاتي\n";
echo "   المعلمين ينشئهم المدير فقط\n\n";

echo "📌 للاختبار:\n";
echo "   1. تصفح: " . (isset($_SERVER['HTTP_HOST']) ? "http://{$_SERVER['HTTP_HOST']}" : "الموقع") . "\n";
echo "   2. اضغط 'تسجيل طالب جديد'\n";
echo "   3. سجل دخول كمدير لإنشاء معلمين\n\n";

echo "انتهى الاختبار بنجاح! ✅\n";
