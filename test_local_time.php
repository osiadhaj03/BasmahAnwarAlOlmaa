<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use Carbon\Carbon;

echo "=== مقارنة الأوقات: الخادم مقابل الوقت المحلي ===\n\n";

$lesson = Lesson::first();
if (!$lesson) {
    echo "❌ لا توجد دروس في قاعدة البيانات\n";
    exit;
}

// وقت الخادم (UTC)
$serverTime = now();
echo "🖥️ وقت الخادم (UTC):\n";
echo "   📅 التاريخ: " . $serverTime->format('Y-m-d') . "\n";
echo "   ⏰ الوقت: " . $serverTime->format('H:i:s') . "\n";
echo "   📍 اليوم: " . $serverTime->format('l') . "\n\n";

// محاكاة الوقت المحلي في عمان (UTC+3)
$localTime = $serverTime->copy()->addHours(3);
echo "🏠 الوقت المحلي المتوقع في عمان (UTC+3):\n";
echo "   📅 التاريخ: " . $localTime->format('Y-m-d') . "\n";
echo "   ⏰ الوقت: " . $localTime->format('H:i:s') . "\n";
echo "   📍 اليوم: " . $localTime->format('l') . "\n\n";

echo "📚 معلومات الدرس:\n";
echo "   📝 الاسم: " . $lesson->name . "\n";
echo "   📅 اليوم: " . $lesson->day_of_week . "\n";
echo "   ⏰ الوقت: " . $lesson->start_time->format('H:i') . " - " . $lesson->end_time->format('H:i') . "\n\n";

// اختبار حالة الحضور باستخدام وقت الخادم
echo "🔍 اختبار بوقت الخادم:\n";
$serverStatus = $lesson->getAttendanceStatus();
echo "   📊 حالة الحضور: " . $serverStatus . "\n\n";

// اختبار حالة الحضور باستخدام الوقت المحلي
echo "🔍 اختبار بالوقت المحلي:\n";
$localTimeString = $localTime->format('H:i');
$localDayString = strtolower($localTime->format('l'));
$localStatus = $lesson->getAttendanceStatusWithLocalTime($localTimeString, $localDayString);
echo "   ⏰ الوقت المحلي: " . $localTimeString . "\n";
echo "   📅 اليوم المحلي: " . $localDayString . "\n";
echo "   📊 حالة الحضور: " . $localStatus . "\n\n";

// تحليل الفرق
echo "📊 تحليل الفرق:\n";
if ($serverStatus !== $localStatus) {
    echo "   ⚠️ يوجد فرق في النتائج!\n";
    echo "   🖥️ نتيجة الخادم: " . $serverStatus . "\n";
    echo "   🏠 نتيجة الوقت المحلي: " . $localStatus . "\n";
    echo "   💡 هذا يؤكد أهمية استخدام الوقت المحلي\n";
} else {
    echo "   ✅ النتائج متطابقة في كلا الحالتين\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "💡 توصية: استخدم الوقت المحلي من المتصفح للحصول على نتائج دقيقة\n";
