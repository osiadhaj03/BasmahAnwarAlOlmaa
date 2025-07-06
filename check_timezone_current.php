<?php
require_once 'vendor/autoload.php';

echo "=== فحص توقيت النظام الحالي ===\n";
echo "التاريخ: " . date('Y-m-d H:i:s A') . "\n";

// تحميل إعدادات .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "APP_TIMEZONE من .env: " . $_ENV['APP_TIMEZONE'] . "\n";
echo "توقيت PHP الافتراضي: " . date_default_timezone_get() . "\n";

// تعيين توقيت عمان
date_default_timezone_set('Asia/Amman');
echo "توقيت عمان المحدد: " . date_default_timezone_get() . "\n";
echo "الوقت الحالي في عمان: " . date('Y-m-d H:i:s A') . " " . date('l') . "\n";

// فحص توقيت UTC
date_default_timezone_set('UTC');
echo "الوقت الحالي UTC: " . date('Y-m-d H:i:s A') . "\n";

// إعادة تعيين توقيت عمان
date_default_timezone_set('Asia/Amman');
echo "\n=== معلومات إضافية ===\n";
echo "التوقيت الصيفي: " . (date('I') ? 'نعم' : 'لا') . "\n";
echo "فرق التوقيت: GMT+" . (date('Z') / 3600) . "\n";

// محاكاة now() Laravel
echo "\n=== محاكاة Laravel now() ===\n";
$now = new DateTime('now', new DateTimeZone('Asia/Amman'));
echo "Laravel now() محاكي: " . $now->format('Y-m-d H:i:s A') . " " . $now->format('l') . "\n";

echo "\n=== اختبار درس وهمي ===\n";
// محاكاة درس الأربعاء من 10:00 إلى 12:00
$today = $now->format('l');
$currentTime = $now->format('H:i');

echo "اليوم الحالي: $today\n";
echo "الوقت الحالي: $currentTime\n";

if ($today === 'Wednesday') {
    if ($currentTime >= '10:00' && $currentTime <= '12:00') {
        if ($currentTime <= '10:15') {
            echo "الحالة: حاضر ✅\n";
        } else {
            echo "الحالة: متأخر ⏰\n";
        }
    } else {
        echo "الحالة: الدرس غير متاح ❌\n";
    }
} else {
    echo "الحالة: ليس يوم الأربعاء - الدرس غير متاح ❌\n";
}
?>
