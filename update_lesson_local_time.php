<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use Carbon\Carbon;

echo "=== تعديل وقت الدرس للوقت المحلي ===\n\n";

$lesson = Lesson::first();
if (!$lesson) {
    echo "❌ لا توجد دروس في قاعدة البيانات\n";
    exit;
}

// الوقت المحلي الحالي في عمان
$localTime = now()->addHours(3);
echo "🏠 الوقت المحلي الحالي في عمان: " . $localTime->format('H:i') . "\n";

// تعديل الدرس ليبدأ قبل 10 دقائق من الوقت الحالي
$newStartTime = $localTime->copy()->subMinutes(10)->format('H:i');
$newEndTime = $localTime->copy()->addMinutes(50)->format('H:i');

$lesson->start_time = $newStartTime . ':00';
$lesson->end_time = $newEndTime . ':00';
$lesson->save();

echo "✅ تم تحديث الدرس:\n";
echo "   📝 الاسم: " . $lesson->name . "\n";
echo "   📅 اليوم: " . $lesson->day_of_week . "\n";
echo "   ⏰ الوقت الجديد: " . $newStartTime . " - " . $newEndTime . "\n\n";

// اختبار الحالة الجديدة
$localTimeString = $localTime->format('H:i');
$localDayString = strtolower($localTime->format('l'));
$status = $lesson->getAttendanceStatusWithLocalTime($localTimeString, $localDayString);

echo "🔍 اختبار الحالة الجديدة:\n";
echo "   ⏰ الوقت المحلي: " . $localTimeString . "\n";
echo "   📅 اليوم المحلي: " . $localDayString . "\n";
echo "   📊 حالة الحضور: " . $status . "\n";

$minutesFromStart = ($localTime->hour * 60 + $localTime->minute) - 
                   (intval(explode(':', $newStartTime)[0]) * 60 + intval(explode(':', $newStartTime)[1]));

echo "   ⏱️ مضى من بداية الدرس: " . $minutesFromStart . " دقيقة\n";

if ($status === 'present') {
    echo "   ✅ الطالب سيُسجل كـ 'حاضر'\n";
} elseif ($status === 'late') {
    echo "   ⚠️ الطالب سيُسجل كـ 'متأخر'\n";
} else {
    echo "   ❌ الدرس غير متاح\n";
}

echo "\n💡 الآن يمكن اختبار النظام بالوقت المحلي الصحيح!\n";
