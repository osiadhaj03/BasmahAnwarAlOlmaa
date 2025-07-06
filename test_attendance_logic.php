<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use Carbon\Carbon;

echo "=== اختبار منطق الحضور ===\n\n";

$lesson = Lesson::first();
if (!$lesson) {
    echo "❌ لا توجد دروس في قاعدة البيانات\n";
    exit;
}

$now = now();
echo "🕐 الوقت الحالي: " . $now->format('Y-m-d H:i:s') . " (" . $now->format('l') . ")\n";
echo "📚 الدرس: " . $lesson->name . " - " . $lesson->day_of_week . " (" . $lesson->start_time->format('H:i') . " - " . $lesson->end_time->format('H:i') . ")\n\n";

// اختبار حالة الحضور
$attendanceStatus = $lesson->getAttendanceStatus();
echo "📝 حالة الحضور: ";
switch ($attendanceStatus) {
    case 'present':
        echo "✅ حاضر (أول 15 دقيقة)\n";
        break;
    case 'late':
        echo "⚠️ متأخر (بعد 15 دقيقة)\n";
        break;
    case 'unavailable':
        echo "❌ الدرس غير متاح (خارج وقت الدرس أو يوم مختلف)\n";
        break;
}

// تفاصيل إضافية
$currentDay = strtolower($now->format('l'));
$lessonDay = strtolower($lesson->day_of_week);

echo "\n🔍 تفاصيل التحقق:\n";
echo "   📅 اليوم الحالي: " . $currentDay . "\n";
echo "   📅 يوم الدرس: " . $lessonDay . "\n";
echo "   📊 مطابقة اليوم: " . ($currentDay === $lessonDay ? "✅ نعم" : "❌ لا") . "\n";

if ($currentDay === $lessonDay) {
    $lessonStart = Carbon::createFromFormat('H:i', $lesson->start_time->format('H:i'));
    $lessonStart->setDate($now->year, $now->month, $now->day);
    
    $lessonEnd = Carbon::createFromFormat('H:i', $lesson->end_time->format('H:i'));
    $lessonEnd->setDate($now->year, $now->month, $now->day);
    
    $lateThreshold = $lessonStart->copy()->addMinutes(15);
    
    echo "   ⏰ بداية الدرس: " . $lessonStart->format('H:i:s') . "\n";
    echo "   ⏰ نهاية الدرس: " . $lessonEnd->format('H:i:s') . "\n";
    echo "   ⏰ حد التأخر (15 دقيقة): " . $lateThreshold->format('H:i:s') . "\n";
    echo "   📊 ضمن وقت الدرس: " . ($now->between($lessonStart, $lessonEnd) ? "✅ نعم" : "❌ لا") . "\n";
    
    if ($now->between($lessonStart, $lessonEnd)) {
        echo "   📊 قبل حد التأخر: " . ($now->lte($lateThreshold) ? "✅ نعم (حاضر)" : "❌ لا (متأخر)") . "\n";
        
        $minutesFromStart = $lessonStart->diffInMinutes($now);
        echo "   ⏱️ مضى من بداية الدرس: " . $minutesFromStart . " دقيقة\n";
        
        $minutesToEnd = $now->diffInMinutes($lessonEnd);
        echo "   ⏱️ متبقي على نهاية الدرس: " . $minutesToEnd . " دقيقة\n";
    }
}

echo "\n=== انتهى الاختبار ===\n";
