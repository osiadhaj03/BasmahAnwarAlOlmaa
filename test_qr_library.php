<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use SimpleSoftwareIO\QrCode\Facades\QrCode;

echo "🔧 اختبار مكتبة QR Code مباشرة...\n\n";

try {
    // اختبار بسيط لتوليد QR Code
    echo "📱 اختبار توليد QR Code بسيط...\n";
    
    $testUrl = "https://google.com";
    
    // اختبار 1: بدون backend محدد
    try {
        $qr1 = QrCode::format('png')->size(200)->generate($testUrl);
        echo "✅ QR Code بدون backend محدد: نجح\n";
    } catch (Exception $e) {
        echo "❌ QR Code بدون backend محدد: " . $e->getMessage() . "\n";
    }
    
    // اختبار 2: مع GD backend
    try {
        $qr2 = QrCode::format('png')->size(200)->backend('GD')->generate($testUrl);
        echo "✅ QR Code مع GD backend: نجح\n";
    } catch (Exception $e) {
        echo "❌ QR Code مع GD backend: " . $e->getMessage() . "\n";
    }
    
    // اختبار 3: مع SVG format
    try {
        $qr3 = QrCode::format('svg')->size(200)->generate($testUrl);
        echo "✅ QR Code مع SVG format: نجح\n";
    } catch (Exception $e) {
        echo "❌ QR Code مع SVG format: " . $e->getMessage() . "\n";
    }
    
    // التحقق من GD extension
    echo "\n🔍 فحص extensions:\n";
    echo "   GD extension: " . (extension_loaded('gd') ? '✅ موجود' : '❌ غير موجود') . "\n";
    echo "   ImageMagick extension: " . (extension_loaded('imagick') ? '✅ موجود' : '❌ غير موجود') . "\n";
    
    if (extension_loaded('gd')) {
        $gdInfo = gd_info();
        echo "   GD version: " . $gdInfo['GD Version'] . "\n";
        echo "   PNG support: " . ($gdInfo['PNG Support'] ? '✅' : '❌') . "\n";
    }
    
    echo "\n✅ انتهى الاختبار\n";
    
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
    echo "تفاصيل: " . $e->getTraceAsString() . "\n";
}
