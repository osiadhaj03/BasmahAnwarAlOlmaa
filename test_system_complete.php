<?php
// فحص النظام مع Laravel
require_once 'vendor/autoload.php';

// إعداد Laravel Bootstrap
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== فحص Laravel now() الفعلي ===\n";
echo "التكوين الحالي: " . config('app.timezone') . "\n";
echo "Laravel now(): " . now()->format('Y-m-d H:i:s A l') . "\n";
echo "Laravel now() (UTC): " . now()->utc()->format('Y-m-d H:i:s A') . "\n";

// فحص Carbon
echo "\n=== فحص Carbon ===\n";
echo "Carbon::now(): " . \Carbon\Carbon::now()->format('Y-m-d H:i:s A l') . "\n";
echo "Carbon::now('Asia/Amman'): " . \Carbon\Carbon::now('Asia/Amman')->format('Y-m-d H:i:s A l') . "\n";

// فحص مع قاعدة البيانات
echo "\n=== فحص قاعدة البيانات ===\n";
try {
    $dbTime = DB::select('SELECT NOW() as current_time')[0]->current_time;
    echo "وقت قاعدة البيانات: $dbTime\n";
} catch (Exception $e) {
    echo "خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
    // بديل للفحص
    try {
        $lesson = App\Models\Lesson::first();
        if ($lesson) {
            echo "تم الاتصال بقاعدة البيانات بنجاح ✅\n";
            echo "عدد الدروس: " . App\Models\Lesson::count() . "\n";
        }
    } catch (Exception $e2) {
        echo "لا يمكن الاتصال بقاعدة البيانات ❌\n";
    }
}

// فحص الدروس الموجودة
echo "\n=== الدروس الموجودة ===\n";
try {
    $lessons = App\Models\Lesson::select('id', 'title', 'day', 'start_time', 'end_time')->get();
    if ($lessons->count() > 0) {
        foreach ($lessons->take(5) as $lesson) {
            echo "درس #{$lesson->id}: {$lesson->title} - {$lesson->day} من {$lesson->start_time} إلى {$lesson->end_time}\n";
        }
        if ($lessons->count() > 5) {
            echo "... و " . ($lessons->count() - 5) . " دروس أخرى\n";
        }
    } else {
        echo "لا توجد دروس في النظام\n";
    }
} catch (Exception $e) {
    echo "خطأ في قراءة الدروس: " . $e->getMessage() . "\n";
}

// محاكاة سيناريو الحضور
echo "\n=== اختبار منطق الحضور ===\n";
$currentDay = now()->format('l');
$currentTime = now()->format('H:i');

echo "اليوم: $currentDay\n";
echo "الوقت: $currentTime\n";

// محاكاة درس الأربعاء 10:00-12:00
$lessonDay = 'Wednesday';
$lessonStartTime = '10:00';
$lessonEndTime = '12:00';

if ($currentDay === $lessonDay) {
    if ($currentTime >= $lessonStartTime && $currentTime <= $lessonEndTime) {
        $lateThreshold = '10:15';
        if ($currentTime <= $lateThreshold) {
            echo "الحالة: حاضر ✅\n";
        } else {
            echo "الحالة: متأخر ⏰\n";
        }
    } else {
        echo "الحالة: الدرس غير متاح (خارج وقت الدرس) ❌\n";
    }
} else {
    echo "الحالة: الدرس غير متاح (يوم مختلف) ❌\n";
}

echo "\n=== اختبار فوري للدرس ===\n";
// تجربة البحث عن درس في الوقت الحالي
try {
    $currentLesson = App\Models\Lesson::where('day', $currentDay)
                                    ->where('start_time', '<=', $currentTime)
                                    ->where('end_time', '>=', $currentTime)
                                    ->first();
    if ($currentLesson) {
        echo "يوجد درس حالياً: {$currentLesson->title}\n";
    } else {
        echo "لا يوجد درس حالياً\n";
    }
} catch (Exception $e) {
    echo "خطأ في البحث عن الدرس: " . $e->getMessage() . "\n";
}
?>
