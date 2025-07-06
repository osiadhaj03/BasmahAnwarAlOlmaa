<?php
// فحص النظام مع Laravel
require_once 'vendor/autoload.php';

// إعداد Laravel Bootstrap
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== فحص Laravel now() الفعلي ===\n";
echo "التكوين الحالي: " . config('app.timezone') . "\n";
echo "Laravel now(): " . now()->format('Y-m-d H:i:s A l') . "\n";
echo "Laravel now() (UTC): " . now()->utc()->format('Y-m-d H:i:s A') . "\n";

// فحص Carbon
echo "\n=== فحص Carbon ===\n";
echo "Carbon::now(): " . \Carbon\Carbon::now()->format('Y-m-d H:i:s A l') . "\n";
echo "Carbon::now('Asia/Amman'): " . \Carbon\Carbon::now('Asia/Amman')->format('Y-m-d H:i:s A l') . "\n";

// فحص مع قاعدة البيانات
echo "\n=== فحص قاعدة البيانات ===\n";
try {
    $dbTime = DB::select('SELECT NOW() as current_time')[0]->current_time;
    echo "وقت قاعدة البيانات: $dbTime\n";
} catch (Exception $e) {
    echo "خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
}

// محاكاة سيناريو الحضور
echo "\n=== اختبار منطق الحضور ===\n";
$currentDay = now()->format('l');
$currentTime = now()->format('H:i');

echo "اليوم: $currentDay\n";
echo "الوقت: $currentTime\n";

// محاكاة درس الأربعاء 10:00-12:00
$lessonDay = 'Wednesday';
$lessonStartTime = '10:00';
$lessonEndTime = '12:00';

if ($currentDay === $lessonDay) {
    if ($currentTime >= $lessonStartTime && $currentTime <= $lessonEndTime) {
        $lateThreshold = '10:15';
        if ($currentTime <= $lateThreshold) {
            echo "الحالة: حاضر ✅\n";
        } else {
            echo "الحالة: متأخر ⏰\n";
        }
    } else {
        echo "الحالة: الدرس غير متاح (خارج وقت الدرس) ❌\n";
    }
} else {
    echo "الحالة: الدرس غير متاح (يوم مختلف) ❌\n";
}
?>
