<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use App\Http\Controllers\QRCodeController;
use Illuminate\Http\Request;

echo "🔧 اختبار شامل لنظام QR Code...\n\n";

try {
    // العثور على الدرس
    $lesson = Lesson::find(1);
    if (!$lesson) {
        echo "❌ لا يوجد درس بـ ID = 1\n";
        exit;
    }
    
    echo "✅ الدرس: {$lesson->name}\n";
    echo "   المعلم: " . ($lesson->teacher ? $lesson->teacher->name : 'غير معين') . "\n";
    echo "   اليوم: {$lesson->day_of_week}\n";
    echo "   الوقت: {$lesson->start_time} - {$lesson->end_time}\n\n";
    
    // اختبار 1: توليد QR Token
    echo "🔨 اختبار 1: توليد QR Token...\n";
    try {
        $qrToken = $lesson->forceGenerateQRToken();
        echo "✅ تم توليد QR Token بنجاح\n";
        echo "   Token: " . substr($qrToken->token, 0, 30) . "...\n";
        echo "   ينتهي في: {$qrToken->expires_at}\n";
    } catch (Exception $e) {
        echo "❌ فشل توليد QR Token: " . $e->getMessage() . "\n";
    }
    
    // اختبار 2: canGenerateQR
    echo "\n🔨 اختبار 2: فحص إمكانية التوليد...\n";
    $canGenerate = $lesson->canGenerateQR();
    echo $canGenerate ? "✅ يمكن توليد QR Code\n" : "❌ لا يمكن توليد QR Code\n";
    
    // اختبار 3: QR Code Controller
    echo "\n🔨 اختبار 3: QR Controller...\n";
    $controller = new QRCodeController();
    
    // محاكاة طلب HTTP
    $request = new \Illuminate\Http\Request();
    $request->setMethod('GET');
    
    try {
        // اختبار quickGenerate
        echo "   🔸 اختبار quickGenerate...\n";
        $response = $controller->quickGenerate($lesson);
        $statusCode = $response->getStatusCode();
        echo "   📊 رمز الاستجابة: {$statusCode}\n";
        
        if ($statusCode === 200) {
            echo "   ✅ QR Code تم توليده بنجاح\n";
            echo "   📦 نوع المحتوى: " . $response->headers->get('Content-Type') . "\n";
        } else {
            echo "   ❌ فشل التوليد\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ خطأ في Controller: " . $e->getMessage() . "\n";
    }
    
    // اختبار 4: البيئة والإعدادات
    echo "\n🔨 اختبار 4: إعدادات البيئة...\n";
    echo "   APP_ENV: " . env('APP_ENV') . "\n";
    echo "   APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "\n";
    echo "   APP_URL: " . env('APP_URL') . "\n";
    
    // اختبار 5: مسار QR
    echo "\n🔨 اختبار 5: مسارات QR...\n";
    echo "   🔗 QR مباشر: http://127.0.0.1:8000/quick-qr/{$lesson->id}\n";
    echo "   🔗 صفحة QR: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-display\n";
    echo "   🔗 معلومات Token: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-info\n";
    echo "   🔗 تجديد Token: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-refresh\n";
    
    echo "\n✅ انتهى الاختبار بنجاح!\n";
    echo "\n🚀 لتجربة النظام:\n";
    echo "   1. شغل الخادم: php artisan serve\n";
    echo "   2. افتح: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-display\n";
    echo "   3. اضغط 'توليد QR جديد'\n";
    
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
    echo "التفاصيل: " . $e->getTraceAsString() . "\n";
}
