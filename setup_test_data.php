<?php

/**
 * إضافة طلاب إلى الدروس لاختبار النظام
 */

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// تهيئة قاعدة البيانات
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => 'database/database.sqlite',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== إضافة طلاب إلى الدروس ===\n\n";

try {
    // 1. جلب المعلمين
    $teachers = DB::table('users')->where('role', 'teacher')->get();
    echo "عدد المعلمين: " . count($teachers) . "\n";

    // 2. جلب الطلاب  
    $students = DB::table('users')->where('role', 'student')->get();
    echo "عدد الطلاب: " . count($students) . "\n";

    // 3. جلب الدروس
    $lessons = DB::table('lessons')->get();
    echo "عدد الدروس: " . count($lessons) . "\n\n";

    if (count($students) == 0) {
        echo "❌ لا توجد طلاب في النظام!\n";
        echo "إنشاء طلاب تجريبيين...\n";
        
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'name' => "طالب تجريبي $i",
                'email' => "student$i@test.com",
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $students = DB::table('users')->where('role', 'student')->get();
        echo "✅ تم إنشاء " . count($students) . " طلاب\n\n";
    }

    // 4. إضافة طلاب إلى الدروس
    $addedCount = 0;
    foreach ($lessons as $lesson) {
        // فحص إذا كان الدرس يحتوي على طلاب
        $existingStudents = DB::table('lesson_student')
            ->where('lesson_id', $lesson->id)
            ->count();
            
        if ($existingStudents == 0) {
            // إضافة 3-5 طلاب عشوائيين لكل درس
            $randomStudents = $students->random(min(3, count($students)));
            
            foreach ($randomStudents as $student) {
                // التحقق من عدم وجود الطالب مسبقاً
                $exists = DB::table('lesson_student')
                    ->where('lesson_id', $lesson->id)
                    ->where('student_id', $student->id)
                    ->exists();
                    
                if (!$exists) {
                    DB::table('lesson_student')->insert([
                        'lesson_id' => $lesson->id,
                        'student_id' => $student->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $addedCount++;
                }
            }
            
            echo "✅ تم إضافة طلاب للدرس: $lesson->subject\n";
        }
    }

    echo "\n=== النتائج ===\n";
    echo "تم إضافة $addedCount ربط جديد بين الطلاب والدروس\n";

    // 5. عرض إحصائيات نهائية
    $totalConnections = DB::table('lesson_student')->count();
    echo "إجمالي الروابط: $totalConnections\n";

    // 6. عرض دروس معلم معين مع طلابه
    if (count($teachers) > 0) {
        $firstTeacher = $teachers->first();
        echo "\n=== دروس المعلم: {$firstTeacher->name} ===\n";
        
        $teacherLessons = DB::table('lessons')
            ->where('teacher_id', $firstTeacher->id)
            ->limit(3)
            ->get();
            
        foreach ($teacherLessons as $lesson) {
            $studentsCount = DB::table('lesson_student')
                ->where('lesson_id', $lesson->id)
                ->count();
            echo "- {$lesson->subject}: $studentsCount طالب\n";
        }
    }

    echo "\n✅ تم إعداد البيانات بنجاح!\n";
    echo "يمكنك الآن اختبار تسجيل الحضور\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

function now() {
    return date('Y-m-d H:i:s');
}
