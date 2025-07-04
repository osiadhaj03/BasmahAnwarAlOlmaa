<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Carbon\Carbon;

echo "🕐 اختبار إعدادات التوقيت...\n\n";

// التوقيت الحالي
echo "📅 معلومات التوقيت:\n";
echo "   التوقيت المحلي (PHP): " . date('Y-m-d H:i:s') . "\n";
echo "   المنطقة الزمنية (PHP): " . date_default_timezone_get() . "\n";
echo "   Carbon now(): " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
echo "   Carbon timezone: " . Carbon::now()->timezone->getName() . "\n";
echo "   Laravel timezone: " . config('app.timezone') . "\n";

// اليوم والتاريخ
echo "\n📆 معلومات اليوم:\n";
echo "   التاريخ الحالي: " . Carbon::now()->format('Y-m-d') . "\n";
echo "   اليوم (انجليزي): " . Carbon::now()->format('l') . "\n";
echo "   اليوم (مختصر): " . strtolower(Carbon::now()->format('l')) . "\n";
echo "   الوقت: " . Carbon::now()->format('H:i:s') . "\n";

// تحويل للتوقيت المطلوب
$riyadhTime = Carbon::now('Asia/Riyadh');
echo "\n🏛️ توقيت الرياض:\n";
echo "   التاريخ: " . $riyadhTime->format('Y-m-d') . "\n";
echo "   اليوم: " . strtolower($riyadhTime->format('l')) . "\n";
echo "   الوقت: " . $riyadhTime->format('H:i:s') . "\n";

// التحقق من الدروس
try {
    $today = strtolower(Carbon::now()->format('l'));
    $todayCount = \App\Models\Lesson::where('day_of_week', $today)->count();
    
    echo "\n📚 معلومات الدروس:\n";
    echo "   اليوم للبحث: {$today}\n";
    echo "   عدد دروس اليوم: {$todayCount}\n";
    
    // عرض جميع الدروس
    $allLessons = \App\Models\Lesson::all();
    echo "   إجمالي الدروس: " . $allLessons->count() . "\n";
    
    if ($allLessons->count() > 0) {
        echo "\n📋 قائمة الدروس:\n";
        foreach ($allLessons as $lesson) {
            echo "   - ID: {$lesson->id}, اليوم: {$lesson->day_of_week}, الموضوع: {$lesson->subject}\n";
        }
    }
    
} catch (Exception $e) {
    echo "\n❌ خطأ في قراءة الدروس: " . $e->getMessage() . "\n";
}

echo "\n✅ انتهى اختبار التوقيت\n";
