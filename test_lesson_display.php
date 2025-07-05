<?php

// اختبار سريع للتأكد من صحة عرض الدروس

require_once 'vendor/autoload.php';

// تحديد البيئة
putenv('APP_ENV=local');

// تحميل التطبيق
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// إعداد الاتصال بقاعدة البيانات
$app->make('db');

echo "=== اختبار عرض الدروس ===\n\n";

try {
    // جلب الدروس مع عدد الحضور
    $lessons = \App\Models\Lesson::with(['teacher'])->withCount(['attendances'])->take(5)->get();
    
    echo "عدد الدروس الموجودة: " . $lessons->count() . "\n\n";
    
    foreach ($lessons as $lesson) {
        echo "اسم الدرس: " . $lesson->name . "\n";
        echo "المدرس: " . ($lesson->teacher ? $lesson->teacher->name : 'غير محدد') . "\n";
        echo "عدد الطلاب: " . $lesson->attendances_count . "\n";
        echo "الحالة: " . ($lesson->status ?? 'مجدول') . "\n";
        echo "اليوم: " . ($lesson->day_of_week ?? 'غير محدد') . "\n";
        echo "---\n";
    }
    
    echo "\n=== إحصائيات الدروس ===\n";
    echo "إجمالي الدروس: " . \App\Models\Lesson::count() . "\n";
    echo "دروس مجدولة: " . \App\Models\Lesson::where('status', 'scheduled')->count() . "\n";
    echo "دروس نشطة: " . \App\Models\Lesson::where('status', 'active')->count() . "\n";
    echo "دروس مكتملة: " . \App\Models\Lesson::where('status', 'completed')->count() . "\n";
    
    echo "\n✅ جميع الاختبارات تمت بنجاح!\n";
    
} catch (\Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}
