<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;
use App\Http\Controllers\QRCodeController;

echo "🔧 اختبار سريع لصفحة QR...\n\n";

try {
    $lesson = Lesson::first();
    if (!$lesson) {
        echo "❌ لا يوجد دروس\n";
        exit;
    }
    
    echo "✅ الدرس: {$lesson->subject}\n\n";
    
    // اختبار getTokenInfo
    echo "🔨 اختبار getTokenInfo...\n";
    $controller = new QRCodeController();
    
    // محاكاة مستخدم مدير
    auth()->loginUsingId(1); // تسجيل دخول المدير
    
    $tokenInfo = $controller->getTokenInfo($lesson);
    $data = json_decode($tokenInfo->getContent(), true);
    
    echo "📊 نتائج getTokenInfo:\n";
    echo "   can_generate_qr: " . ($data['can_generate_qr'] ? 'true' : 'false') . "\n";
    echo "   has_valid_token: " . ($data['has_valid_token'] ? 'true' : 'false') . "\n";
    echo "   qr_url: " . ($data['qr_url'] ?? 'null') . "\n";
    echo "   token_remaining_minutes: " . $data['token_remaining_minutes'] . "\n\n";
    
    // اختبار quick QR
    echo "🔨 اختبار quickGenerate...\n";
    $qrResponse = $controller->quickGenerate($lesson);
    echo "📊 رمز الاستجابة: " . $qrResponse->getStatusCode() . "\n";
    echo "📦 نوع المحتوى: " . $qrResponse->headers->get('Content-Type') . "\n\n";
    
    // اختبار refresh
    echo "🔨 اختبار refreshToken...\n";
    $refreshResponse = $controller->refreshToken($lesson);
    $refreshData = json_decode($refreshResponse->getContent(), true);
    echo "📊 refresh success: " . ($refreshData['success'] ? 'true' : 'false') . "\n";
    echo "📝 message: " . $refreshData['message'] . "\n\n";
    
    echo "🚀 الروابط للاختبار:\n";
    echo "   صفحة QR: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-display\n";
    echo "   QR مباشر: http://127.0.0.1:8000/quick-qr/{$lesson->id}\n";
    echo "   معلومات Token: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-info\n\n";
    
    echo "✅ جميع الاختبارات اكتملت\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
