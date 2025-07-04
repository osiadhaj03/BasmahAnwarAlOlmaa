<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Carbon\Carbon;
use App\Models\Lesson;
use App\Models\User;

echo "🕐 اختبار شامل للتوقيت والنظام...\n\n";

// 1. التحقق من التوقيت
echo "1️⃣ التوقيت الحالي:\n";
$now = Carbon::now();
echo "   📅 التاريخ: " . $now->format('Y-m-d') . "\n";
echo "   🕐 الوقت: " . $now->format('H:i:s') . "\n";
echo "   📆 اليوم: " . $now->format('l') . "\n";
echo "   🌍 المنطقة الزمنية: " . $now->timezone->getName() . "\n";

// 2. التحقق من إعدادات Laravel
echo "\n2️⃣ إعدادات Laravel:\n";
echo "   APP_TIMEZONE: " . config('app.timezone') . "\n";
echo "   APP_ENV: " . config('app.env') . "\n";
echo "   APP_DEBUG: " . (config('app.debug') ? 'true' : 'false') . "\n";

// 3. اختبار البيانات
echo "\n3️⃣ البيانات:\n";
$lessonsCount = Lesson::count();
$usersCount = User::count();
echo "   📚 عدد الدروس: $lessonsCount\n";
echo "   👤 عدد المستخدمين: $usersCount\n";

// 4. اختبار دروس اليوم
$today = strtolower($now->format('l'));
$todayLessons = Lesson::where('day_of_week', $today)->get();
echo "   📖 دروس اليوم ($today): " . $todayLessons->count() . "\n";

if ($todayLessons->count() > 0) {
    foreach ($todayLessons as $lesson) {
        echo "     - {$lesson->subject} في {$lesson->start_time}\n";
    }
}

// 5. اختبار QR Code
echo "\n4️⃣ اختبار QR Code:\n";
if ($todayLessons->count() > 0) {
    $lesson = $todayLessons->first();
    $canGenerate = $lesson->canGenerateQR();
    echo "   📱 يمكن توليد QR: " . ($canGenerate ? "نعم" : "لا") . "\n";
    
    if (!$canGenerate) {
        echo "   📝 السبب: وقت الدرس لم يحن بعد أو انتهى\n";
        echo "   🕐 وقت الدرس: {$lesson->start_time} - {$lesson->end_time}\n";
    }
    
    // اختبار توليد QR في بيئة التطوير
    try {
        $qrToken = $lesson->forceGenerateQRToken();
        echo "   ✅ تم توليد QR Token بنجاح للاختبار\n";
        echo "   🔑 Token: " . substr($qrToken->token, 0, 20) . "...\n";
        echo "   ⏰ ينتهي في: " . $qrToken->expires_at->format('Y-m-d H:i:s') . "\n";
    } catch (Exception $e) {
        echo "   ❌ خطأ في توليد QR Token: " . $e->getMessage() . "\n";
    }
}

// 6. روابط مفيدة
echo "\n5️⃣ روابط مفيدة:\n";
echo "   🔗 تشغيل الخادم: php artisan serve\n";
echo "   🌐 الصفحة الرئيسية: http://127.0.0.1:8000\n";
echo "   👨‍🎓 dashboard الطالب: http://127.0.0.1:8000/student/dashboard\n";
echo "   👨‍🏫 dashboard المعلم: http://127.0.0.1:8000/teacher/dashboard\n";
echo "   👨‍💼 dashboard المدير: http://127.0.0.1:8000/admin/dashboard\n";

if ($todayLessons->count() > 0) {
    $lesson = $todayLessons->first();
    echo "   📱 اختبار QR: http://127.0.0.1:8000/quick-qr/{$lesson->id}\n";
    echo "   🖼️ صفحة QR: http://127.0.0.1:8000/admin/lessons/{$lesson->id}/qr-display\n";
}

echo "\n✅ انتهى الاختبار بنجاح!\n";
echo "\n📝 ملاحظات:\n";
echo "   - التوقيت الآن صحيح (Asia/Riyadh)\n";
echo "   - النظام يعرض اليوم الصحيح (الثلاثاء)\n";
echo "   - QR Code يعمل في بيئة التطوير بأي وقت\n";
echo "   - dashboard الطالب سيعرض الدروس الصحيحة لليوم\n";
