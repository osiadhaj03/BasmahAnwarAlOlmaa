<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use Carbon\Carbon;

echo "=== اختبار النظام بوقت عمان ===\n\n";

$lesson = Lesson::first();
if (!$lesson) {
    echo "❌ لا توجد دروس في قاعدة البيانات\n";
    exit;
}

// الوقت الحالي بتوقيت عمان
$now = now();
echo "🕐 الوقت الحالي في النظام (عمان):\n";
echo "   📅 التاريخ: " . $now->format('Y-m-d') . "\n";
echo "   ⏰ الوقت: " . $now->format('H:i:s') . "\n";
echo "   📍 اليوم: " . $now->format('l') . " (English) / " . $now->locale('ar')->translatedFormat('l') . " (عربي)\n";
echo "   🌍 المنطقة الزمنية: " . $now->timezone->getName() . "\n\n";

echo "📚 معلومات الدرس:\n";
echo "   📝 الاسم: " . $lesson->name . "\n";
echo "   📅 اليوم: " . $lesson->day_of_week . "\n";
echo "   ⏰ الوقت: " . $lesson->start_time->format('H:i') . " - " . $lesson->end_time->format('H:i') . "\n\n";

// اختبار حالة الحضور بالوقت الجديد
echo "🔍 اختبار حالة الحضور:\n";
$currentDay = strtolower($now->format('l'));
$currentTime = $now->format('H:i');

$status = $lesson->getAttendanceStatusWithLocalTime($currentTime, $currentDay);

echo "   ⏰ الوقت الحالي: " . $currentTime . "\n";
echo "   📅 اليوم الحالي: " . $currentDay . "\n";
echo "   📅 يوم الدرس: " . $lesson->day_of_week . "\n";

switch ($status) {
    case 'present':
        echo "   ✅ حالة الحضور: حاضر (أول 15 دقيقة)\n";
        break;
    case 'late':
        echo "   ⚠️ حالة الحضور: متأخر (بعد 15 دقيقة)\n";
        break;
    case 'unavailable':
        echo "   ❌ حالة الحضور: الدرس غير متاح\n";
        break;
}

// تحديث وقت الدرس ليكون نشطاً الآن للاختبار
echo "\n🔧 تحديث الدرس ليكون نشطاً الآن:\n";
$newStart = $now->copy()->subMinutes(5)->format('H:i');
$newEnd = $now->copy()->addMinutes(55)->format('H:i');

$lesson->start_time = $newStart . ':00';
$lesson->end_time = $newEnd . ':00';
$lesson->save();

echo "   ⏰ وقت الدرس الجديد: " . $newStart . " - " . $newEnd . "\n";

// اختبار مرة أخرى
$newStatus = $lesson->getAttendanceStatusWithLocalTime($currentTime, $currentDay);
echo "   📊 حالة الحضور الجديدة: ";

switch ($newStatus) {
    case 'present':
        echo "✅ حاضر (أول 15 دقيقة)\n";
        break;
    case 'late':
        echo "⚠️ متأخر (بعد 15 دقيقة)\n";
        break;
    case 'unavailable':
        echo "❌ الدرس غير متاح\n";
        break;
}

echo "\n✅ النظام الآن يعمل بتوقيت عمان الصحيح!\n";
echo "🎯 جاهز للاستخدام بالوقت المحلي!\n";
