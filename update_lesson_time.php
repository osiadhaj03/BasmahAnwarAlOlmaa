<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use Carbon\Carbon;

echo "=== تحديث وقت الدرس للاختبار ===\n";

$lesson = Lesson::first();
if ($lesson) {
    $lesson->start_time = '16:20:00';
    $lesson->end_time = '17:20:00';
    $lesson->save();
    
    echo "✅ تم تحديث الدرس:\n";
    echo "   📝 الاسم: " . $lesson->name . "\n";
    echo "   📅 اليوم: " . $lesson->day_of_week . "\n";
    echo "   ⏰ الوقت الجديد: " . $lesson->start_time->format('H:i') . " - " . $lesson->end_time->format('H:i') . "\n";
    
    $now = now();
    echo "\n=== الوقت الحالي ===\n";
    echo "⏰ الوقت: " . $now->format('H:i') . "\n";
    echo "📅 اليوم: " . $now->format('l') . "\n";
    
    // التحقق من إمكانية توليد QR
    echo "\n=== فحص إمكانية توليد QR ===\n";
    echo "canGenerateQR(): " . ($lesson->canGenerateQR() ? "✅ نعم" : "❌ لا") . "\n";
    
} else {
    echo "❌ لم يتم العثور على دروس في قاعدة البيانات\n";
}
