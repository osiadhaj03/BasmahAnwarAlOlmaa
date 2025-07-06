<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use App\Models\QrToken;
use Carbon\Carbon;

echo "=== اختبار شامل لنظام QR Code ===\n\n";

// 1. فحص الوقت الحالي
$now = now();
echo "🕐 الوقت الحالي:\n";
echo "   📅 التاريخ: " . $now->format('Y-m-d') . "\n";
echo "   ⏰ الوقت: " . $now->format('H:i:s') . "\n";
echo "   📍 اليوم: " . $now->format('l') . " (English) / " . $now->locale('ar')->translatedFormat('l') . " (عربي)\n\n";

// 2. فحص الدرس
$lesson = Lesson::first();
if (!$lesson) {
    echo "❌ لا توجد دروس في قاعدة البيانات\n";
    exit;
}

echo "📚 معلومات الدرس:\n";
echo "   📝 الاسم: " . $lesson->name . "\n";
echo "   📅 اليوم: " . $lesson->day_of_week . "\n";
echo "   ⏰ الوقت: " . $lesson->start_time->format('H:i') . " - " . $lesson->end_time->format('H:i') . "\n\n";

// 3. فحص canGenerateQR
echo "🔍 فحص إمكانية توليد QR:\n";
$canGenerate = $lesson->canGenerateQR();
echo "   canGenerateQR(): " . ($canGenerate ? "✅ نعم" : "❌ لا") . "\n\n";

// 4. اختبار توليد QR Token
echo "🏷️ اختبار توليد QR Token:\n";
try {
    $qrToken = $lesson->generateQRCodeToken();
    echo "   ✅ تم توليد Token بنجاح\n";
    echo "   🆔 Token ID: " . $qrToken->id . "\n";
    echo "   🔑 Token: " . substr($qrToken->token, 0, 20) . "...\n";
    echo "   ⏰ تاريخ الإنشاء: " . $qrToken->created_at->format('Y-m-d H:i:s') . "\n";
    echo "   ⏰ تاريخ الانتهاء: " . $qrToken->expires_at->format('Y-m-d H:i:s') . "\n";
    
    // حساب الوقت المتبقي
    $minutesLeft = now()->diffInMinutes($qrToken->expires_at, false);
    echo "   ⏱️ الوقت المتبقي: " . $minutesLeft . " دقيقة\n";
    
} catch (Exception $e) {
    echo "   ❌ خطأ في توليد Token: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. فحص الحضور المسموح
echo "👥 فحص منطق الحضور:\n";
$currentDay = strtolower($now->format('l'));
$lessonDay = strtolower($lesson->day_of_week);

if ($currentDay === $lessonDay) {
    echo "   📅 اليوم صحيح: ✅\n";
    
    $startTime = Carbon::createFromFormat('H:i', $lesson->start_time->format('H:i'));
    $endTime = Carbon::createFromFormat('H:i', $lesson->end_time->format('H:i'));
    $currentTime = Carbon::createFromFormat('H:i', $now->format('H:i'));
    
    if ($currentTime->between($startTime, $endTime)) {
        echo "   ⏰ الوقت ضمن وقت الدرس: ✅\n";
        
        $minutesFromStart = $startTime->diffInMinutes($currentTime);
        echo "   ⏱️ مضى من بداية الدرس: " . $minutesFromStart . " دقيقة\n";
        
        if ($minutesFromStart <= 15) {
            echo "   📝 حالة الحضور: ✅ حاضر (أول 15 دقيقة)\n";
        } else {
            echo "   📝 حالة الحضور: ⚠️ متأخر (بعد 15 دقيقة)\n";
        }
        
    } else {
        echo "   ⏰ الوقت خارج وقت الدرس: ❌\n";
        echo "   📝 حالة الحضور: ❌ الدرس غير متاح\n";
    }
    
} else {
    echo "   📅 اليوم خطأ (اليوم الحالي: " . $currentDay . ", يوم الدرس: " . $lessonDay . "): ❌\n";
    echo "   📝 حالة الحضور: ❌ الدرس غير متاح\n";
}

echo "\n";

// 6. إحصائيات
echo "📊 إحصائيات:\n";
echo "   👥 عدد الطلاب المسجلين: " . $lesson->students()->count() . "\n";
echo "   🏷️ عدد QR Tokens: " . QrToken::where('lesson_id', $lesson->id)->count() . "\n";
echo "   ✅ QR Tokens النشطة: " . QrToken::where('lesson_id', $lesson->id)->where('expires_at', '>', now())->count() . "\n";

echo "\n=== انتهى الاختبار ===\n";
