<?php
// فحص بنية قاعدة البيانات
require_once 'vendor/autoload.php';

// إعداد Laravel Bootstrap
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== فحص جداول قاعدة البيانات ===\n";

try {
    // فحص الجداول الموجودة
    $tables = DB::select('SHOW TABLES');
    echo "الجداول الموجودة:\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- $tableName\n";
    }
    
    echo "\n=== فحص جدول lessons ===\n";
    // فحص بنية جدول lessons
    $columns = DB::select('DESCRIBE lessons');
    echo "أعمدة جدول lessons:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) - {$column->Null} - {$column->Default}\n";
    }
    
    echo "\n=== فحص محتوى جدول lessons ===\n";
    $lessons = DB::select('SELECT * FROM lessons LIMIT 3');
    if (count($lessons) > 0) {
        echo "عدد الدروس: " . count(DB::select('SELECT id FROM lessons')) . "\n";
        echo "أول 3 دروس:\n";
        foreach ($lessons as $lesson) {
            $lessonArray = (array)$lesson;
            echo "درس #{$lessonArray['id']}:\n";
            foreach ($lessonArray as $key => $value) {
                echo "  $key: $value\n";
            }
            echo "---\n";
        }
    } else {
        echo "لا توجد دروس\n";
    }
    
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}

echo "\n=== فحص التوقيت ===\n";
echo "Laravel now(): " . now()->format('Y-m-d H:i:s A l') . "\n";
echo "التوقيت المحدد: " . config('app.timezone') . "\n";

// فحص إذا كان يمكن الوصول لنموذج Lesson
try {
    $lessonCount = App\Models\Lesson::count();
    echo "عدد الدروس من النموذج: $lessonCount\n";
} catch (Exception $e) {
    echo "خطأ في الوصول لنموذج Lesson: " . $e->getMessage() . "\n";
}
?>
