<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Carbon\Carbon;

echo "=== فحص الوقت الحالي ===\n\n";

// الوقت في النظام (UTC)
$utcTime = now();
echo "🌍 وقت النظام (UTC): " . $utcTime->format('Y-m-d H:i:s') . "\n";

// الوقت المحلي (تقدير - الرياض GMT+3)
$localTime = $utcTime->copy()->addHours(3);
echo "🏠 الوقت المحلي المقدر (الرياض): " . $localTime->format('Y-m-d H:i:s') . "\n";

// فحص التوقيت الصيفي
$riyadhTime = $utcTime->copy()->setTimezone('Asia/Riyadh');
echo "🇸🇦 توقيت الرياض الدقيق: " . $riyadhTime->format('Y-m-d H:i:s T') . "\n";

echo "\n=== المقارنة ===\n";
echo "الفرق بين UTC والرياض: " . $utcTime->diffInHours($riyadhTime) . " ساعات\n";

// JavaScript time
echo "\n=== كود JavaScript للحصول على الوقت المحلي ===\n";
echo "const localTime = new Date();\n";
echo "const localHour = localTime.getHours();\n";
echo "const localMinute = localTime.getMinutes();\n";
echo "console.log('الوقت المحلي:', localHour + ':' + localMinute);\n";
