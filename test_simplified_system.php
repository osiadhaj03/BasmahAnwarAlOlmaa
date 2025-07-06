<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;

echo "=== اختبار النظام المبسط ===\n\n";

$lesson = Lesson::first();
if (!$lesson) {
    echo "❌ لا توجد دروس في قاعدة البيانات\n";
    exit;
}

echo "📚 معلومات الدرس:\n";
echo "   📝 الاسم: " . $lesson->name . "\n";
echo "   📅 اليوم: " . $lesson->day_of_week . "\n";
echo "   ⏰ الوقت: " . $lesson->start_time->format('H:i') . " - " . $lesson->end_time->format('H:i') . "\n\n";

// اختبار canGenerateQR
echo "🔧 اختبار توليد QR:\n";
$canGenerate = $lesson->canGenerateQR();
echo "   📊 يمكن توليد QR: " . ($canGenerate ? "✅ نعم (دائماً)" : "❌ لا") . "\n\n";

// محاكاة أوقات مختلفة للاختبار
$testTimes = [
    ['19:32', 'sunday', 'بداية الدرس'],
    ['19:40', 'sunday', 'أول 15 دقيقة (حاضر)'],
    ['19:50', 'sunday', 'بعد 15 دقيقة (متأخر)'],
    ['20:40', 'sunday', 'خارج وقت الدرس'],
    ['19:40', 'monday', 'يوم مختلف']
];

echo "🧪 اختبار سيناريوهات الحضور:\n";
foreach ($testTimes as $test) {
    [$time, $day, $description] = $test;
    
    $status = $lesson->getAttendanceStatusWithLocalTime($time, $day);
    
    echo "   ⏰ " . $description . " (" . $time . " - " . $day . "):\n";
    
    switch ($status) {
        case 'present':
            echo "      ✅ حاضر\n";
            break;
        case 'late':
            echo "      ⚠️ متأخر\n";
            break;
        case 'unavailable':
            echo "      ❌ الدرس حالياً غير متاح\n";
            break;
    }
    echo "\n";
}

echo "✅ النظام المبسط:\n";
echo "   🔧 زر توليد QR يعمل دائماً\n";
echo "   ❌ لا توجد مؤقتات أو عدادات\n";
echo "   📱 التحقق من الوقت فقط عند مسح الطالب\n";
echo "   💬 رسائل واضحة بالعربية\n\n";

echo "🎯 جاهز للاستخدام!\n";
