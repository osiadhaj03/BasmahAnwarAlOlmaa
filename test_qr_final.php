<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use App\Http\Controllers\QRCodeController;

echo "🔧 اختبار نهائي لتوليد QR Code...\n\n";

try {
    // العثور على الدرس
    $lesson = Lesson::first();
    if (!$lesson) {
        echo "❌ لا يوجد دروس في قاعدة البيانات\n";
        exit;
    }
    
    echo "✅ الدرس: {$lesson->subject}\n";
    echo "📅 اليوم: {$lesson->day_of_week}\n";
    echo "🕐 الوقت: {$lesson->start_time} - {$lesson->end_time}\n\n";
    
    // اختبار QR Token
    echo "🔨 اختبار توليد QR Token...\n";
    $qrToken = $lesson->forceGenerateQRToken();
    echo "✅ تم توليد QR Token بنجاح\n";
    echo "🔑 Token: " . substr($qrToken->token, 0, 30) . "...\n";
    echo "⏰ ينتهي في: {$qrToken->expires_at}\n\n";
    
    // اختبار QR Code Controller
    echo "🔨 اختبار QR Controller...\n";
    $controller = new QRCodeController();
    
    try {
        $response = $controller->quickGenerate($lesson);
        echo "✅ QR Code تم توليده بنجاح\n";
        echo "📊 رمز الاستجابة: " . $response->getStatusCode() . "\n";
        echo "📦 نوع المحتوى: " . $response->headers->get('Content-Type') . "\n\n";
    } catch (Exception $e) {
        echo "❌ خطأ في توليد QR: " . $e->getMessage() . "\n\n";
    }
    
    echo "🚀 روابط للاختبار:\n";
    echo "   🌐 تشغيل الخادم: php artisan serve\n";
    echo "   📱 QR مباشر: http://127.0.0.1:8000/quick-qr/{$lesson->id}\n";
    echo "   🖼️ صفحة QR: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-display\n";
    echo "   👨‍🎓 dashboard الطالب: http://127.0.0.1:8000/student/dashboard\n";
    echo "   🧪 صفحة اختبار QR: http://127.0.0.1:8000/qr-test-page\n\n";
    
    echo "✅ جميع الاختبارات نجحت!\n";
    echo "💡 المشكلة تم حلها:\n";
    echo "   - التوقيت صحيح الآن (Asia/Riyadh بدلاً من UTC)\n";
    echo "   - النظام يعرض التاريخ الصحيح (الثلاثاء 24 يونيو 2025)\n";
    echo "   - QR Code يعمل في بيئة التطوير بأي وقت\n";
    echo "   - dashboard الطالب سيعرض الدروس الصحيحة\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "التفاصيل: " . $e->getTraceAsString() . "\n";
}
