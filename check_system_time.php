<?php

/**
 * فحص وقت النظام - أنوار العلماء
 */

echo "=== فحص وقت النظام ===\n\n";

// عرض الوقت الحالي
echo "📅 التاريخ والوقت الحاليين:\n";
echo "التاريخ: " . date('Y-m-d') . "\n";
echo "الوقت: " . date('H:i:s') . "\n";
echo "اليوم: " . date('l') . " (بالإنجليزية)\n";

// ترجمة اليوم للعربية
$days = [
    'Sunday' => 'الأحد',
    'Monday' => 'الاثنين', 
    'Tuesday' => 'الثلاثاء',
    'Wednesday' => 'الأربعاء',
    'Thursday' => 'الخميس',
    'Friday' => 'الجمعة',
    'Saturday' => 'السبت'
];

$currentDay = date('l');
echo "اليوم: " . ($days[$currentDay] ?? $currentDay) . " (بالعربية)\n\n";

// عرض المنطقة الزمنية
echo "🌍 إعدادات المنطقة الزمنية:\n";
echo "المنطقة الزمنية: " . date_default_timezone_get() . "\n";
echo "UTC Offset: " . date('P') . "\n\n";

// عرض أمثلة لأوقات دروس تجريبية
echo "📚 أمثلة لاختبار أوقات الدروس:\n\n";

// درس تجريبي 1: الأحد 10:00-12:00
echo "درس تجريبي 1:\n";
echo "اليوم: الأحد (Sunday)\n";
echo "الوقت: 10:00 - 12:00\n";
if ($currentDay === 'Sunday') {
    $currentTime = date('H:i');
    if ($currentTime >= '10:00' && $currentTime <= '12:00') {
        echo "✅ الآن في وقت الدرس!\n";
        $startTime = strtotime('10:00');
        $currentTimeStamp = strtotime($currentTime);
        $minutesFromStart = ($currentTimeStamp - $startTime) / 60;
        
        if ($minutesFromStart <= 15) {
            echo "✅ ضمن أول 15 دقيقة = حاضر\n";
        } else {
            echo "⚠️ بعد أول 15 دقيقة = متأخر\n";
        }
    } else {
        echo "❌ خارج وقت الدرس\n";
    }
} else {
    echo "❌ ليس يوم الأحد\n";
}
echo "\n";

// درس تجريبي 2: اليوم الحالي
echo "درس تجريبي 2 (اليوم الحالي):\n";
echo "اليوم: " . ($days[$currentDay] ?? $currentDay) . "\n";
echo "الوقت: " . date('H:i') . " - " . date('H:i', strtotime('+2 hours')) . "\n";
echo "✅ هذا درس نشط الآن!\n";

$currentMinute = (int)date('i');
if ($currentMinute <= 15) {
    echo "✅ ضمن أول 15 دقيقة = حاضر\n";
} else {
    echo "⚠️ بعد أول 15 دقيقة = متأخر\n";
}
echo "\n";

echo "🔍 ملاحظات مهمة:\n";
echo "• تأكد من صحة المنطقة الزمنية في إعدادات النظام\n";
echo "• تأكد من تطابق أوقات الدروس في قاعدة البيانات\n";
echo "• النظام يستخدم تنسيق 24 ساعة (H:i)\n";
echo "• أيام الأسبوع باللغة الإنجليزية في قاعدة البيانات\n\n";

echo "⚙️ للتحقق من دروس حقيقية:\n";
echo "1. ادخل إلى قاعدة البيانات\n";
echo "2. تحقق من جدول lessons\n";
echo "3. انظر للأعمدة: day_of_week, start_time, end_time\n";
echo "4. تأكد من تطابق البيانات مع الوقت الحالي\n";

?>
