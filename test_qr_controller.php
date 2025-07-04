<?php

require_once 'vendor/autoload.php';

use App\Models\Lesson;
use App\Models\User;
use App\Http\Controllers\QrCodeController;
use Illuminate\Http\Request;

// بدء Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== اختبار QrCodeController الجديد ===\n\n";

try {
    // البحث عن معلم ودرس للاختبار
    $teacher = User::where('role', 'teacher')->first();
    if (!$teacher) {
        echo "❌ لا يوجد معلم للاختبار\n";
        exit;
    }
    
    $lesson = Lesson::where('teacher_id', $teacher->id)
                    ->whereNotNull('day_of_week')
                    ->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->first();
    
    if (!$lesson) {
        echo "❌ لا يوجد درس للمعلم للاختبار\n";
        exit;
    }
    
    echo "👨‍🏫 المعلم: {$teacher->name}\n";
    echo "📚 الدرس: {$lesson->name}\n";
    echo "📅 اليوم الأصلي: {$lesson->day_of_week}\n";
    echo "⏰ الوقت الأصلي: {$lesson->start_time->format('H:i')} - {$lesson->end_time->format('H:i')}\n\n";
    
    // تعديل الدرس ليصبح نشط الآن
    $now = now();
    $currentDay = strtolower($now->format('l'));
    $lesson->day_of_week = $currentDay;
    $lesson->start_time = $now->copy()->subMinutes(10);
    $lesson->end_time = $now->copy()->addMinutes(50);
    $lesson->save();
    
    echo "⚡ تم تعديل الدرس ليصبح نشط:\n";
    echo "📅 اليوم: {$lesson->day_of_week}\n";
    echo "⏰ الوقت: {$lesson->start_time->format('H:i')} - {$lesson->end_time->format('H:i')}\n\n";
    
    // محاكاة تسجيل دخول المعلم
    auth()->login($teacher);
    
    // إنشاء Controller
    $controller = new QrCodeController();
    
    // اختبار getTokenInfo
    echo "🔍 اختبار getTokenInfo:\n";
    $response = $controller->getTokenInfo($lesson);
    $data = json_decode($response->getContent(), true);
    
    echo "✅ اسم الدرس: {$data['lesson_name']}\n";
    echo "✅ إمكانية توليد QR: " . ($data['can_generate_qr'] ? 'نعم' : 'لا') . "\n";
    echo "✅ رسالة التوفر: " . ($data['qr_availability_message'] ?? 'متاح') . "\n";
    echo "✅ عدد الطلاب: {$data['students_count']}\n\n";
    
    if ($data['can_generate_qr']) {
        // اختبار refreshToken
        echo "🔄 اختبار refreshToken:\n";
        $refreshResponse = $controller->refreshToken($lesson);
        $refreshData = json_decode($refreshResponse->getContent(), true);
        
        if ($refreshData['success']) {
            echo "✅ تم توليد التوكن بنجاح\n";
            echo "✅ رسالة النجاح: {$refreshData['message']}\n";
            echo "✅ وقت الانتهاء: {$refreshData['token_expires_at']}\n";
            echo "✅ الدقائق المتبقية: {$refreshData['token_remaining_minutes']}\n\n";
        } else {
            echo "❌ فشل في توليد التوكن: {$refreshData['message']}\n\n";
        }
        
        // اختبار توليد QR image
        echo "🖼️  اختبار توليد QR image:\n";
        $request = new Request();
        $qrResponse = $controller->generateQR($request, $lesson->id);
        
        if ($qrResponse->getStatusCode() === 200) {
            echo "✅ تم توليد QR image بنجاح\n";
            echo "✅ نوع المحتوى: " . $qrResponse->headers->get('Content-Type') . "\n";
            echo "✅ حجم الصورة: " . strlen($qrResponse->getContent()) . " بايت\n";
        } else {
            echo "❌ فشل في توليد QR image\n";
            $errorData = json_decode($qrResponse->getContent(), true);
            if ($errorData && isset($errorData['error'])) {
                echo "❌ رسالة الخطأ: {$errorData['error']}\n";
            }
        }
    } else {
        echo "⚠️  QR غير متاح في الوقت الحالي\n";
    }
    
    echo "\n=== اكتمل اختبار Controller بنجاح ===\n";
    
} catch (Exception $e) {
    echo "❌ خطأ في الاختبار: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
