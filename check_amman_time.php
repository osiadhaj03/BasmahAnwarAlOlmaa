<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Carbon\Carbon;

echo "=== فحص الوقت في النظام مقابل وقت عمان ===\n\n";

// وقت النظام الحالي (Laravel)
$systemTime = now();
echo "🖥️ وقت النظام (Laravel now()):\n";
echo "   📅 التاريخ: " . $systemTime->format('Y-m-d') . "\n";
echo "   ⏰ الوقت: " . $systemTime->format('H:i:s') . "\n";
echo "   🌍 المنطقة الزمنية: " . $systemTime->timezone->getName() . "\n";
echo "   📍 UTC Offset: " . $systemTime->format('P') . "\n\n";

// وقت عمان، الأردن (Asia/Amman)
$ammanTime = Carbon::now('Asia/Amman');
echo "🏠 وقت عمان، الأردن (Asia/Amman):\n";
echo "   📅 التاريخ: " . $ammanTime->format('Y-m-d') . "\n";
echo "   ⏰ الوقت: " . $ammanTime->format('H:i:s') . "\n";
echo "   🌍 المنطقة الزمنية: " . $ammanTime->timezone->getName() . "\n";
echo "   📍 UTC Offset: " . $ammanTime->format('P') . "\n\n";

// حساب الفرق
$diff = $systemTime->diffInMinutes($ammanTime);
$systemAhead = $systemTime->gt($ammanTime);

echo "📊 مقارنة الأوقات:\n";
if ($diff == 0) {
    echo "   ✅ الأوقات متطابقة تماماً!\n";
} else {
    if ($systemAhead) {
        echo "   ⚠️ وقت النظام متقدم بـ " . $diff . " دقيقة\n";
    } else {
        echo "   ⚠️ وقت النظام متأخر بـ " . $diff . " دقيقة\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";

// فحص إعدادات Laravel
echo "⚙️ إعدادات Laravel:\n";
echo "   🌍 APP_TIMEZONE: " . config('app.timezone') . "\n";
echo "   📅 Carbon default timezone: " . Carbon::now()->timezone->getName() . "\n";

// فحص إعدادات PHP
echo "\n⚙️ إعدادات PHP:\n";
echo "   🌍 date_default_timezone_get(): " . date_default_timezone_get() . "\n";
echo "   📅 date('Y-m-d H:i:s'): " . date('Y-m-d H:i:s') . "\n";

echo "\n" . str_repeat("=", 50) . "\n";

// توصيات
if ($diff > 0) {
    echo "💡 التوصيات:\n";
    echo "   1. تحديث APP_TIMEZONE في .env إلى 'Asia/Amman'\n";
    echo "   2. تشغيل: php artisan config:cache\n";
    echo "   3. إعادة تشغيل الخادم\n\n";
    
    echo "📝 الأمر المطلوب:\n";
    echo "   APP_TIMEZONE=Asia/Amman\n";
} else {
    echo "✅ النظام مضبوط على الوقت الصحيح!\n";
}

echo "\n=== انتهى الفحص ===\n";
