<?php

require_once 'vendor/autoload.php';

// تحميل Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Lesson;

echo "=== الدروس في قاعدة البيانات ===\n\n";

try {
    $lessons = Lesson::select('id', 'name', 'day_of_week', 'start_time', 'end_time')->get();
    
    if ($lessons->count() > 0) {
        foreach ($lessons as $lesson) {
            echo "🎓 الدرس #{$lesson->id}: {$lesson->name}\n";
            echo "   📅 اليوم: {$lesson->day_of_week}\n";
            echo "   ⏰ الوقت: {$lesson->start_time->format('H:i')} - {$lesson->end_time->format('H:i')}\n";
            
            // التحقق من إمكانية النشاط الآن
            $currentDay = strtolower(date('l'));
            $currentTime = date('H:i');
            $lessonDay = strtolower($lesson->day_of_week);
            
            if ($lessonDay === $currentDay) {
                $startTime = $lesson->start_time->format('H:i');
                $endTime = $lesson->end_time->format('H:i');
                
                if ($currentTime >= $startTime && $currentTime <= $endTime) {
                    echo "   ✅ الدرس نشط الآن!\n";
                    
                    $startTimestamp = strtotime($startTime);
                    $currentTimestamp = strtotime($currentTime);
                    $minutesFromStart = ($currentTimestamp - $startTimestamp) / 60;
                    
                    if ($minutesFromStart <= 15) {
                        echo "   🟢 ضمن أول 15 دقيقة = حاضر\n";
                    } else {
                        echo "   🟡 بعد أول 15 دقيقة = متأخر\n";
                    }
                } else {
                    echo "   ❌ خارج وقت الدرس\n";
                }
            } else {
                echo "   ❌ ليس يوم الدرس (اليوم: $currentDay)\n";
            }
            echo "\n";
        }
    } else {
        echo "❌ لا توجد دروس في قاعدة البيانات\n";
        echo "يجب إنشاء دروس تجريبية للاختبار\n\n";
        
        echo "📝 لإنشاء درس تجريبي للوقت الحالي:\n";
        echo "الوقت الحالي: " . date('H:i') . " يوم " . date('l') . "\n";
        echo "يمكنك إنشاء درس من " . date('H:i') . " إلى " . date('H:i', strtotime('+2 hours')) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
}

echo "\n=== الوقت الحالي ===\n";
echo "📅 التاريخ: " . date('Y-m-d') . "\n";
echo "⏰ الوقت: " . date('H:i:s') . "\n";
echo "📍 اليوم: " . date('l') . " (english) / " . [
    'Sunday' => 'الأحد',
    'Monday' => 'الاثنين',
    'Tuesday' => 'الثلاثاء', 
    'Wednesday' => 'الأربعاء',
    'Thursday' => 'الخميس',
    'Friday' => 'الجمعة',
    'Saturday' => 'السبت'
][date('l')] . " (عربي)\n";

?>
