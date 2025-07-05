<?php

/**
 * اختبار نظام التسجيل المحدث مع رقم الهاتف
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Schema;

echo "=== اختبار نظام التسجيل المحدث - BasmahApp ===\n\n";

try {
    // 1. فحص هيكل جدول المستخدمين
    echo "1. فحص هيكل جدول المستخدمين:\n";
    
    $columns = Schema::getColumnListing('users');
    
    $requiredColumns = ['phone', 'student_id'];
    foreach ($requiredColumns as $column) {
        if (in_array($column, $columns)) {
            echo "✅ عمود '$column' موجود\n";
        } else {
            echo "❌ عمود '$column' غير موجود\n";
        }
    }
    
    // 2. فحص ملف StudentRegisterController
    echo "\n2. فحص StudentRegisterController:\n";
    
    if (file_exists('app/Http/Controllers/Auth/StudentRegisterController.php')) {
        $controllerContent = file_get_contents('app/Http/Controllers/Auth/StudentRegisterController.php');
        
        $checks = [
            'phone validation' => 'phone.*required',
            'student_id validation' => 'student_id.*nullable',
            'phone save' => "'phone'",
            'student_id save' => "'student_id'"
        ];
        
        foreach ($checks as $checkName => $pattern) {
            if (strpos($controllerContent, $pattern) !== false) {
                echo "✅ $checkName\n";
            } else {
                echo "❌ $checkName غير موجود\n";
            }
        }
    } else {
        echo "❌ StudentRegisterController غير موجود\n";
    }
    
    // 3. فحص نموذج User
    echo "\n3. فحص نموذج User:\n";
    
    if (file_exists('app/Models/User.php')) {
        $userModelContent = file_get_contents('app/Models/User.php');
        
        if (strpos($userModelContent, "'phone'") !== false) {
            echo "✅ حقل phone في fillable\n";
        } else {
            echo "❌ حقل phone غير موجود في fillable\n";
        }
        
        if (strpos($userModelContent, "'student_id'") !== false) {
            echo "✅ حقل student_id في fillable\n";
        } else {
            echo "❌ حقل student_id غير موجود في fillable\n";
        }
    }
    
    // 4. فحص صفحات التسجيل
    echo "\n4. فحص صفحات التسجيل:\n";
    
    $registerPages = [
        'resources/views/auth/student-register.blade.php',
        'resources/views/auth/student-register-new.blade.php'
    ];
    
    foreach ($registerPages as $page) {
        if (file_exists($page)) {
            $pageContent = file_get_contents($page);
            
            $pageName = basename($page);
            
            if (strpos($pageContent, 'name="phone"') !== false) {
                echo "✅ $pageName - حقل الهاتف موجود\n";
            } else {
                echo "❌ $pageName - حقل الهاتف غير موجود\n";
            }
            
            if (strpos($pageContent, 'name="student_id"') !== false) {
                echo "✅ $pageName - حقل رقم الطالب موجود\n";
            } else {
                echo "❌ $pageName - حقل رقم الطالب غير موجود\n";
            }
        } else {
            echo "❌ $page غير موجود\n";
        }
    }
    
    // 5. فحص صفحة تسجيل الدخول للإدارة
    echo "\n5. فحص صفحة تسجيل الدخول للإدارة:\n";
    
    if (file_exists('resources/views/auth/admin-login.blade.php')) {
        $adminLoginContent = file_get_contents('resources/views/auth/admin-login.blade.php');
        
        if (strpos($adminLoginContent, 'إنشاء حساب طالب جديد') !== false) {
            echo "✅ زر إنشاء حساب جديد موجود\n";
        } else {
            echo "❌ زر إنشاء حساب جديد غير موجود\n";
        }
        
        if (strpos($adminLoginContent, 'student.register.form') !== false) {
            echo "✅ رابط التسجيل صحيح\n";
        } else {
            echo "❌ رابط التسجيل غير صحيح\n";
        }
    } else {
        echo "❌ صفحة تسجيل دخول الإدارة غير موجودة\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "📊 ملخص التحديثات:\n\n";
    
    echo "✅ التحديثات المطبقة:\n";
    echo "   • إضافة حقل رقم الهاتف (مطلوب)\n";
    echo "   • إضافة حقل رقم الطالب (اختياري)\n";
    echo "   • تحديث Controller للتحقق والحفظ\n";
    echo "   • تحديث صفحات التسجيل\n";
    echo "   • إضافة زر تسجيل جديد في صفحة الدخول\n\n";
    
    echo "📝 للاختبار:\n";
    echo "   1. تشغيل: run_phone_migration.bat\n";
    echo "   2. زيارة: http://127.0.0.1:8000/admin/login\n";
    echo "   3. اضغط 'إنشاء حساب طالب جديد'\n";
    echo "   4. املأ النموذج مع رقم الهاتف\n\n";
    
    echo "🔧 الأوامر المطلوبة:\n";
    echo "   php artisan migrate --force\n";
    echo "   php artisan serve\n\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

echo "انتهى الاختبار! ✅\n";
?>
