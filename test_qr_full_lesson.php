<?php

require_once 'vendor/autoload.php';

use App\Models\Lesson;
use App\Models\QrToken;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// بدء Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== اختبار نظام QR Code الجديد - متاح طوال فترة الدرس ===\n\n";

try {
    // البحث عن درس للاختبار
    $lesson = Lesson::whereNotNull('day_of_week')
                    ->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->first();
    
    if (!$lesson) {
        echo "❌ لا توجد دروس للاختبار\n";
        exit;
    }
    
    echo "📚 اختبار الدرس: {$lesson->name}\n";
    echo "📅 اليوم: {$lesson->day_of_week}\n";
    echo "⏰ الوقت: {$lesson->start_time->format('H:i')} - {$lesson->end_time->format('H:i')}\n\n";
    
    // تنظيف QR tokens القديمة
    QrToken::where('lesson_id', $lesson->id)->delete();
    echo "🧹 تم حذف التوكنز القديمة\n\n";
    
    // اختبار 1: التحقق من دالة canGenerateQR خارج وقت الدرس
    echo "🔍 اختبار 1: التحقق من canGenerateQR خارج وقت الدرس\n";
    $originalDay = $lesson->day_of_week;
    $lesson->day_of_week = 'sunday'; // تغيير مؤقت لمحاكاة يوم مختلف
    $canGenerate = $lesson->canGenerateQR();
    echo "النتيجة: " . ($canGenerate ? "✅ يمكن التوليد" : "❌ لا يمكن التوليد") . " (متوقع: لا يمكن)\n\n";
    
    // إعادة اليوم الأصلي
    $lesson->day_of_week = $originalDay;
    
    // اختبار 2: محاولة توليد QR خارج وقت الدرس
    echo "🔍 اختبار 2: محاولة توليد QR خارج وقت الدرس\n";
    try {
        $lesson->day_of_week = 'sunday'; // تغيير مؤقت
        $token = $lesson->generateQRCodeToken();
        echo "❌ خطأ: تم توليد التوكن بالرغم من عدم الإذن!\n";
    } catch (Exception $e) {
        echo "✅ تم منع التوليد بشكل صحيح: {$e->getMessage()}\n";
    }
    $lesson->day_of_week = $originalDay; // إعادة القيمة الأصلية
    echo "\n";
      // اختبار 3: محاكاة وقت الدرس واختبار التوليد
    echo "🔍 اختبار 3: محاكاة وقت الدرس واختبار التوليد\n";
    
    // تعديل مؤقت للدرس ليصبح نشط الآن
    $now = Carbon::now();
    $currentDay = strtolower($now->format('l'));
    $lesson->day_of_week = $currentDay;
    $lesson->start_time = $now->copy()->subMinutes(10); // بدأ منذ 10 دقائق
    $lesson->end_time = $now->copy()->addMinutes(50); // ينتهي بعد 50 دقيقة
    $lesson->save();
    
    echo "⏰ تم تعديل الدرس ليصبح نشط الآن\n";
    echo "📅 اليوم الحالي: {$lesson->day_of_week}\n";
    echo "⏰ وقت البداية: {$lesson->start_time->format('H:i')}\n";
    echo "⏰ وقت النهاية: {$lesson->end_time->format('H:i')}\n\n";
    
    // التحقق من إمكانية التوليد
    $canGenerate = $lesson->canGenerateQR();
    echo "قابلية التوليد: " . ($canGenerate ? "✅ يمكن التوليد" : "❌ لا يمكن التوليد") . "\n";
    
    if ($canGenerate) {
        // توليد QR token
        echo "🔄 توليد QR token...\n";
        $qrToken = $lesson->generateQRCodeToken();
        
        echo "✅ تم توليد QR token بنجاح!\n";
        echo "🔑 Token ID: {$qrToken->id}\n";
        echo "⏰ وقت التوليد: {$qrToken->generated_at->format('Y-m-d H:i:s')}\n";
        echo "⌛ وقت الانتهاء: {$qrToken->expires_at->format('Y-m-d H:i:s')}\n";
        
        // حساب المدة المتبقية
        $remainingMinutes = $qrToken->expires_at->diffInMinutes(now());
        echo "⏱️  المدة المتبقية: {$remainingMinutes} دقيقة\n\n";
        
        // اختبار صلاحية التوكن
        echo "🔍 اختبار صلاحية التوكن:\n";
        echo "هل التوكن صالح؟ " . ($qrToken->isValid() ? "✅ نعم" : "❌ لا") . "\n";
        echo "هل التوكن منتهي؟ " . ($qrToken->isExpired() ? "❌ نعم" : "✅ لا") . "\n";
        echo "هل التوكن مستخدم؟ " . ($qrToken->isUsed() ? "❌ نعم" : "✅ لا") . "\n\n";
        
        // اختبار الحصول على التوكن الصالح
        echo "🔍 اختبار getValidQRToken:\n";
        $validToken = $lesson->getValidQRToken();
        if ($validToken) {
            echo "✅ تم العثور على توكن صالح (ID: {$validToken->id})\n";
            echo "⌛ ينتهي في: {$validToken->expires_at->format('Y-m-d H:i:s')}\n";
        } else {
            echo "❌ لم يتم العثور على توكن صالح\n";
        }
        
    } else {
        echo "❌ لا يمكن توليد QR في الوقت الحالي\n";
    }
    
    echo "\n=== اكتمل الاختبار بنجاح ===\n";
    
} catch (Exception $e) {
    echo "❌ خطأ في الاختبار: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
