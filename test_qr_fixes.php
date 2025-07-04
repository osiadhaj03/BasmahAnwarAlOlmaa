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

echo "=== اختبار إصلاح مشاكل QR Code ===\n\n";

try {
    // البحث عن معلم ودرس للاختبار
    $teacher = User::where('role', 'teacher')->first();
    $lesson = Lesson::where('teacher_id', $teacher->id)->first();
    
    echo "👨‍🏫 المعلم: {$teacher->name}\n";
    echo "📚 الدرس: {$lesson->name}\n";
    
    // 📅 عرض يوم الدرس الحالي من قاعدة البيانات
    echo "📅 يوم الدرس في قاعدة البيانات: '{$lesson->day_of_week}'\n";
    echo "📅 اليوم الحالي: '" . strtolower(now()->format('l')) . "'\n";
    
    // تعديل الدرس ليصبح اليوم الحالي
    $currentDay = strtolower(now()->format('l'));
    $lesson->day_of_week = $currentDay;
    $lesson->start_time = now()->subMinutes(10);
    $lesson->end_time = now()->addMinutes(50);
    $lesson->save();
    
    echo "\n⚡ تم تعديل الدرس:\n";
    echo "📅 اليوم: '{$lesson->day_of_week}'\n";
    echo "⏰ الوقت: {$lesson->start_time->format('H:i')} - {$lesson->end_time->format('H:i')}\n";
    echo "🕐 الوقت الحالي: " . now()->format('H:i') . "\n\n";
    
    // اختبار canGenerateQR
    echo "🔍 اختبار canGenerateQR:\n";
    $canGenerate = $lesson->canGenerateQR();
    echo "النتيجة: " . ($canGenerate ? "✅ يمكن التوليد" : "❌ لا يمكن التوليد") . "\n\n";
    
    if ($canGenerate) {
        // محاكاة تسجيل دخول المعلم
        auth()->login($teacher);
        
        // إنشاء Controller
        $controller = new QrCodeController();
        
        // اختبار getTokenInfo
        echo "🔍 اختبار getTokenInfo:\n";
        $response = $controller->getTokenInfo($lesson);
        $data = json_decode($response->getContent(), true);
        
        echo "✅ إمكانية توليد QR: " . ($data['can_generate_qr'] ? 'نعم' : 'لا') . "\n";
        echo "✅ الدقائق المتبقية: {$data['token_remaining_minutes']} دقيقة\n";
        
        // اختبار توليد QR token
        if ($data['can_generate_qr']) {
            echo "\n🔄 اختبار توليد QR token:\n";
            $refreshResponse = $controller->refreshToken($lesson);
            $refreshData = json_decode($refreshResponse->getContent(), true);
            
            if ($refreshData['success']) {
                echo "✅ تم التوليد بنجاح\n";
                echo "✅ الدقائق المتبقية: {$refreshData['token_remaining_minutes']} دقيقة\n";
                echo "✅ وقت الانتهاء: {$refreshData['token_expires_at']}\n";
            } else {
                echo "❌ فشل التوليد: {$refreshData['message']}\n";
            }
        }
    }
    
    echo "\n=== اكتمل الاختبار ===\n";
    
} catch (Exception $e) {
    echo "❌ خطأ في الاختبار: " . $e->getMessage() . "\n";
}
