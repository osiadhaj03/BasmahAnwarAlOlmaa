<?php
echo "=== فحص التوقيت السريع ===\n";

// إعداد التوقيت
date_default_timezone_set('Asia/Amman');

echo "الوقت الحالي: " . date('Y-m-d H:i:s A') . "\n";
echo "اليوم: " . date('l') . "\n";
echo "التوقيت: " . date_default_timezone_get() . "\n";

// فحص ملف .env
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    if (strpos($env, 'APP_TIMEZONE=Asia/Amman') !== false) {
        echo "✅ APP_TIMEZONE مضبوط على Asia/Amman\n";
    } else {
        echo "❌ APP_TIMEZONE غير مضبوط\n";
    }
} else {
    echo "❌ ملف .env غير موجود\n";
}

// تجربة Laravel
echo "\n=== تجربة Laravel ===\n";
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Laravel now(): " . now()->format('Y-m-d H:i:s A l') . "\n";
    echo "Laravel timezone: " . config('app.timezone') . "\n";
    echo "✅ Laravel يعمل بشكل صحيح\n";
} catch (Exception $e) {
    echo "❌ خطأ في Laravel: " . $e->getMessage() . "\n";
}

echo "\n=== اختبار منطق الحضور ===\n";
$now = date('H:i');
$today = date('l');

echo "الوقت الآن: $now في يوم $today\n";

// محاكاة درس
$lessonDay = 'Wednesday';
$lessonStart = '10:00';
$lessonEnd = '12:00';
$lateThreshold = '10:15';

echo "درس تجريبي: $lessonDay من $lessonStart إلى $lessonEnd\n";

if ($today === $lessonDay) {
    if ($now >= $lessonStart && $now <= $lessonEnd) {
        if ($now <= $lateThreshold) {
            echo "✅ حاضر (ضمن أول 15 دقيقة)\n";
        } else {
            echo "⏰ متأخر (بعد 15 دقيقة)\n";
        }
    } else {
        echo "❌ الدرس غير متاح (خارج الوقت)\n";
    }
} else {
    echo "❌ الدرس غير متاح (يوم مختلف)\n";
}
?>
