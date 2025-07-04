<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;

try {
    echo "🔍 البحث عن درس 'مراقي الفلاح'...\n";
    
    $lesson = Lesson::where('name', 'like', '%مراقي الفلاح%')
                   ->orWhere('subject', 'like', '%مراقي الفلاح%')
                   ->first();
    
    if (!$lesson) {
        echo "❌ لم يتم العثور على الدرس. البحث في جميع الدروس:\n";
        $lessons = Lesson::take(5)->get();
        foreach ($lessons as $l) {
            echo "   - ID: {$l->id}, الاسم: {$l->name}, المادة: {$l->subject}\n";
        }
        
        if ($lessons->count() > 0) {
            $lesson = $lessons->first();
            echo "\n✅ سيتم استخدام الدرس الأول: {$lesson->name}\n";
        } else {
            echo "❌ لا توجد دروس في النظام!\n";
            exit;
        }
    } else {
        echo "✅ تم العثور على الدرس: {$lesson->name}\n";
    }
    
    echo "\n📋 معلومات الدرس:\n";
    echo "   - ID: {$lesson->id}\n";
    echo "   - الاسم: {$lesson->name}\n";
    echo "   - المادة: {$lesson->subject}\n";
    echo "   - اليوم: {$lesson->day_of_week}\n";
    echo "   - الوقت: {$lesson->start_time} - {$lesson->end_time}\n";
    
    echo "\n🔧 اختبار توليد QR Token...\n";
    
    // إجبار توليد QR Token
    $qrToken = $lesson->forceGenerateQRToken();
    
    echo "✅ تم توليد QR Token بنجاح!\n";
    echo "   - Token: " . substr($qrToken->token, 0, 20) . "...\n";
    echo "   - تم التوليد: {$qrToken->generated_at}\n";
    echo "   - ينتهي في: {$qrToken->expires_at}\n";
    
    echo "\n🌐 روابط الاختبار:\n";
    echo "   - QR للدرس: http://127.0.0.1:8000/quick-qr/{$lesson->id}\n";
    echo "   - صفحة QR: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-display\n";
    echo "   - رابط المسح: http://127.0.0.1:8000/attendance/scan?token=" . urlencode($qrToken->token) . "\n";
    
    echo "\n✅ النظام جاهز للاختبار!\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "التفاصيل: " . $e->getTraceAsString() . "\n";
}
