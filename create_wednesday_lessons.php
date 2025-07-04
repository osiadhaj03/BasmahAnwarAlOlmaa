<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

echo "🗓️ إنشاء دروس يوم الأربعاء...\n";

try {
    // التأكد من وجود الأعمدة المطلوبة
    $columns = DB::select("SHOW COLUMNS FROM lessons");
    $columnNames = array_column($columns, 'Field');
    
    if (!in_array('name', $columnNames)) {
        DB::statement('ALTER TABLE lessons ADD COLUMN name VARCHAR(255) AFTER id');
        echo "✅ تم إضافة عمود name\n";
    }
    
    if (!in_array('description', $columnNames)) {
        DB::statement('ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER end_time');
        echo "✅ تم إضافة عمود description\n";
    }
    
    if (!in_array('schedule_time', $columnNames)) {
        DB::statement('ALTER TABLE lessons ADD COLUMN schedule_time TIME NULL AFTER description');
        echo "✅ تم إضافة عمود schedule_time\n";
    }

    // جلب المعلمين والطلاب
    $teachers = User::where('role', 'teacher')->get();
    $students = User::where('role', 'student')->get();
    
    if ($teachers->isEmpty()) {
        echo "❌ لا يوجد معلمين في النظام\n";
        return;
    }
    
    if ($students->isEmpty()) {
        echo "❌ لا يوجد طلاب في النظام\n";
        return;
    }

    echo "👨‍🏫 المعلمين المتاحين: " . $teachers->count() . "\n";
    echo "👨‍🎓 الطلاب المتاحين: " . $students->count() . "\n";

    // حذف الدروس الموجودة ليوم الأربعاء لتجنب التكرار
    $existingLessons = Lesson::where('day_of_week', 'wednesday')->get();
    foreach ($existingLessons as $lesson) {
        $lesson->students()->detach();
        $lesson->delete();
    }
    echo "🗑️ تم حذف الدروس الموجودة ليوم الأربعاء\n";

    // إنشاء دروس يوم الأربعاء
    $wednesdayLessons = [
        [
            'name' => 'الرياضيات - الجبر الأساسي',
            'subject' => 'الرياضيات',
            'teacher_id' => $teachers->first()->id,
            'day_of_week' => 'wednesday',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
            'schedule_time' => '08:00:00',
            'description' => 'درس الجبر الأساسي مع التمارين العملية والحلول التفاعلية.'
        ],
        [
            'name' => 'اللغة العربية - النحو والصرف',
            'subject' => 'اللغة العربية',
            'teacher_id' => $teachers->count() > 1 ? $teachers->get(1)->id : $teachers->first()->id,
            'day_of_week' => 'wednesday',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'schedule_time' => '10:00:00',
            'description' => 'تعلم قواعد النحو والصرف في اللغة العربية مع أمثلة تطبيقية.'
        ],
        [
            'name' => 'العلوم - الفيزياء التطبيقية',
            'subject' => 'العلوم',
            'teacher_id' => $teachers->first()->id,
            'day_of_week' => 'wednesday',
            'start_time' => '12:30:00',
            'end_time' => '14:00:00',
            'schedule_time' => '12:30:00',
            'description' => 'استكشاف مبادئ الفيزياء من خلال التجارب العملية والمشاهدات.'
        ],
        [
            'name' => 'التاريخ - الحضارات القديمة (درس الساعة 4)',
            'subject' => 'التاريخ',
            'teacher_id' => $teachers->count() > 1 ? $teachers->get(1)->id : $teachers->first()->id,
            'day_of_week' => 'wednesday',
            'start_time' => '16:00:00', // 4 العصر
            'end_time' => '17:30:00',   // حتى 5:30
            'schedule_time' => '16:00:00',
            'description' => '🎯 درس خاص لاختبار نظام تسجيل الحضور - دراسة الحضارات المصرية والبابلية القديمة.'
        ],
        [
            'name' => 'اللغة الإنجليزية - المحادثة المتقدمة',
            'subject' => 'اللغة الإنجليزية',
            'teacher_id' => $teachers->first()->id,
            'day_of_week' => 'wednesday',
            'start_time' => '18:00:00',
            'end_time' => '19:30:00',
            'schedule_time' => '18:00:00',
            'description' => 'تطوير مهارات المحادثة في اللغة الإنجليزية من خلال المناقشات الجماعية.'
        ]
    ];

    echo "\n📚 إنشاء " . count($wednesdayLessons) . " دروس جديدة...\n";

    foreach ($wednesdayLessons as $index => $lessonData) {
        $lesson = Lesson::create($lessonData);
        
        // تسجيل جميع الطلاب في كل درس
        $lesson->students()->attach($students->pluck('id'));
        
        echo "✅ تم إنشاء: " . $lesson->name . " (" . $lesson->start_time . " - " . $lesson->end_time . ")\n";
        
        if ($lesson->start_time == '16:00:00') {
            echo "🎯 ★ هذا هو درس الساعة 4 العصر المطلوب للاختبار!\n";
        }
    }

    echo "\n📊 إحصائيات الدروس المُنشأة:\n";
    echo "==========================================\n";
    $wednesdayLessonsCreated = Lesson::where('day_of_week', 'wednesday')->get();
    
    foreach ($wednesdayLessonsCreated as $lesson) {
        $studentsCount = $lesson->students()->count();
        echo "📖 {$lesson->name}\n";
        echo "   ⏰ الوقت: {$lesson->start_time} - {$lesson->end_time}\n";
        echo "   👨‍🏫 المعلم: {$lesson->teacher->name}\n";
        echo "   👥 عدد الطلاب: {$studentsCount}\n";
        if ($lesson->start_time == '16:00:00') {
            echo "   🎯 ★ درس الاختبار الرئيسي\n";
        }
        echo "   ────────────────────────────────────────\n";
    }

    echo "\n🎉 تم إنشاء جميع دروس يوم الأربعاء بنجاح!\n";
    echo "\n🧪 للاختبار:\n";
    echo "1. سجل دخول بحساب طالب: student1@basmahapp.com / password\n";
    echo "2. انتقل إلى لوحة تحكم الطالب\n";
    echo "3. ابحث عن درس 'التاريخ - الحضارات القديمة' في الساعة 4:00 PM\n";
    echo "4. جرب تسجيل الحضور\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "تأكد من:\n";
    echo "1. تشغيل خادم MySQL\n";
    echo "2. وجود قاعدة بيانات 'basmah'\n";
    echo "3. وجود معلمين وطلاب في النظام\n";
}
