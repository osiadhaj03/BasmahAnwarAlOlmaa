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

echo "=== اختبار الرسائل خارج وقت الدرس ===\n\n";

try {
    // البحث عن معلم ودرس
    $teacher = User::where('role', 'teacher')->first();
    $lesson = Lesson::where('teacher_id', $teacher->id)->first();
    
    echo "👨‍🏫 المعلم: {$teacher->name}\n";
    echo "📚 الدرس: {$lesson->name}\n\n";
    
    // تعديل الدرس ليكون خارج الوقت الحالي
    $lesson->day_of_week = 'sunday'; // يوم مختلف
    $lesson->start_time = now()->addHours(2); // يبدأ بعد ساعتين
    $lesson->end_time = now()->addHours(3); // ينتهي بعد 3 ساعات
    $lesson->save();
    
    echo "⏰ تم تعديل الدرس ليكون خارج الوقت:\n";
    echo "📅 اليوم: {$lesson->day_of_week} (اليوم الحالي: " . strtolower(now()->format('l')) . ")\n";
    echo "⏰ الوقت: {$lesson->start_time->format('H:i')} - {$lesson->end_time->format('H:i')}\n\n";
    
    // محاكاة تسجيل دخول المعلم
    auth()->login($teacher);
    
    // إنشاء Controller
    $controller = new QrCodeController();
    
    // اختبار getTokenInfo خارج وقت الدرس
    echo "🔍 اختبار getTokenInfo خارج وقت الدرس:\n";
    $response = $controller->getTokenInfo($lesson);
    $data = json_decode($response->getContent(), true);
    
    echo "✅ إمكانية توليد QR: " . ($data['can_generate_qr'] ? 'نعم' : 'لا') . "\n";
    echo "✅ رسالة التوفر: " . ($data['qr_availability_message'] ?? 'غير محدد') . "\n\n";
    
    // اختبار refreshToken خارج وقت الدرس
    echo "🔄 اختبار refreshToken خارج وقت الدرس:\n";
    $refreshResponse = $controller->refreshToken($lesson);
    $refreshData = json_decode($refreshResponse->getContent(), true);
    
    echo "✅ نجح التوليد: " . ($refreshData['success'] ? 'نعم' : 'لا') . "\n";
    echo "✅ رسالة الاستجابة: {$refreshData['message']}\n\n";
    
    // اختبار generateQR خارج وقت الدرس
    echo "🖼️  اختبار generateQR خارج وقت الدرس:\n";
    $request = new Request();
    $qrResponse = $controller->generateQR($request, $lesson->id);
    
    if ($qrResponse->getStatusCode() === 200) {
        echo "✅ تم توليد QR (هذا غير متوقع!)\n";
    } else {
        echo "✅ تم منع توليد QR بشكل صحيح\n";
        $errorData = json_decode($qrResponse->getContent(), true);
        if ($errorData && isset($errorData['error'])) {
            echo "✅ رسالة المنع: {$errorData['error']}\n";
        }
    }
    
    echo "\n=== اكتمل اختبار الرسائل بنجاح ===\n";
    
} catch (Exception $e) {
    echo "❌ خطأ في الاختبار: " . $e->getMessage() . "\n";
}
