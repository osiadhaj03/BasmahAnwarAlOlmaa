<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Log;

echo "🔍 فحص نظام حذف المستخدمين\n";
echo "==================================\n\n";

// 1. فحص أن الجدول موجود ويحتوي على مستخدمين
try {
    $totalUsers = User::count();
    echo "✅ إجمالي المستخدمين: {$totalUsers}\n";
    
    // عرض المستخدمين مع أدوارهم
    $users = User::take(5)->get();
    echo "\n📋 عينة من المستخدمين:\n";
    foreach ($users as $user) {
        echo "- ID: {$user->id} | الاسم: {$user->name} | الدور: {$user->role}\n";
    }
    
} catch (Exception $e) {
    echo "❌ خطأ في الوصول لجدول المستخدمين: " . $e->getMessage() . "\n";
    exit;
}

// 2. فحص وجود علاقات
echo "\n🔗 فحص العلاقات:\n";
try {
    $teacherWithLessons = User::where('role', 'teacher')->withCount('teachingLessons')->first();
    if ($teacherWithLessons) {
        echo "✅ معلم مع {$teacherWithLessons->teaching_lessons_count} دروس\n";
    }
    
    $studentWithLessons = User::where('role', 'student')->withCount('lessons')->first();
    if ($studentWithLessons) {
        echo "✅ طالب مع {$studentWithLessons->lessons_count} دروس\n";
    }
    
} catch (Exception $e) {
    echo "⚠️ خطأ في فحص العلاقات: " . $e->getMessage() . "\n";
}

// 3. فحص Route
echo "\n🛣️ فحص الـ Routes:\n";
$routes = app('router')->getRoutes();
$deleteRoute = null;
foreach ($routes as $route) {
    if ($route->getName() === 'admin.users.destroy') {
        $deleteRoute = $route;
        break;
    }
}

if ($deleteRoute) {
    echo "✅ مسار الحذف موجود: " . $deleteRoute->uri() . "\n";
    echo "✅ طرق الوصول: " . implode(', ', $deleteRoute->methods()) . "\n";
} else {
    echo "❌ مسار الحذف غير موجود!\n";
}

// 4. محاولة إنشاء مستخدم تجريبي وحذفه
echo "\n🧪 اختبار الحذف:\n";
try {
    // إنشاء مستخدم تجريبي
    $testUser = User::create([
        'name' => 'Test User for Delete',
        'email' => 'test_delete_' . time() . '@example.com',
        'password' => bcrypt('password'),
        'role' => 'student'
    ]);
    
    echo "✅ تم إنشاء مستخدم تجريبي: ID {$testUser->id}\n";
    
    // محاولة حذفه
    $deleted = $testUser->delete();
    
    if ($deleted) {
        echo "✅ تم حذف المستخدم التجريبي بنجاح\n";
    } else {
        echo "❌ فشل في حذف المستخدم التجريبي\n";
    }
    
} catch (Exception $e) {
    echo "❌ خطأ في اختبار الحذف: " . $e->getMessage() . "\n";
}

echo "\n📝 النصائح لحل المشكلة:\n";
echo "1. تأكد من أن JavaScript يعمل في المتصفح\n";
echo "2. تحقق من console في المتصفح للأخطاء\n";
echo "3. تأكد من أن CSRF token يتم إرساله\n";
echo "4. تحقق من network tab في developer tools\n";
echo "5. تحقق من أن المستخدم له صلاحية admin\n";

echo "\n✅ اكتمل فحص النظام\n";
