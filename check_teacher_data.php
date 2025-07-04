<?php

/**
 * فحص بيانات المعلم والدروس
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

echo "=== فحص بيانات المعلمين والدروس ===\n\n";

try {
    // 1. جلب جميع المعلمين
    $teachers = DB::table('users')->where('role', 'teacher')->get();
    echo "📋 المعلمين المتاحين:\n";
    
    foreach ($teachers as $teacher) {
        echo "- ID: {$teacher->id}, الاسم: {$teacher->name}, البريد: {$teacher->email}\n";
        
        // جلب دروس هذا المعلم
        $lessons = DB::table('lessons')->where('teacher_id', $teacher->id)->get();
        echo "  📚 دروسه (" . count($lessons) . "):\n";
        
        foreach ($lessons as $lesson) {
            $studentsCount = DB::table('lesson_student')
                ->where('lesson_id', $lesson->id)
                ->count();
            echo "    - ID: {$lesson->id}, المادة: {$lesson->subject}, الطلاب: {$studentsCount}\n";
        }
        echo "\n";
    }

    // 2. فحص lesson_student table
    echo "📊 إحصائيات جدول lesson_student:\n";
    $totalConnections = DB::table('lesson_student')->count();
    echo "إجمالي الروابط: $totalConnections\n";

    // عرض عينة من البيانات
    $sampleConnections = DB::table('lesson_student')
        ->join('lessons', 'lesson_student.lesson_id', '=', 'lessons.id')
        ->join('users', 'lesson_student.student_id', '=', 'users.id')
        ->select('lessons.subject', 'lessons.teacher_id', 'users.name as student_name')
        ->limit(5)
        ->get();

    echo "\n📋 عينة من الروابط:\n";
    foreach ($sampleConnections as $connection) {
        echo "- {$connection->subject} (معلم: {$connection->teacher_id}) -> طالب: {$connection->student_name}\n";
    }

    // 3. فحص أول معلم بالتفصيل
    if ($teachers->count() > 0) {
        $firstTeacher = $teachers->first();
        echo "\n🔍 تفاصيل المعلم الأول: {$firstTeacher->name}\n";
        
        $teacherLessons = DB::table('lessons')
            ->where('teacher_id', $firstTeacher->id)
            ->get();
            
        foreach ($teacherLessons as $lesson) {
            echo "\n📖 الدرس: {$lesson->subject} (ID: {$lesson->id})\n";
            
            $students = DB::table('lesson_student')
                ->join('users', 'lesson_student.student_id', '=', 'users.id')
                ->where('lesson_student.lesson_id', $lesson->id)
                ->select('users.id', 'users.name')
                ->get();
                
            echo "   👥 الطلاب:\n";
            foreach ($students as $student) {
                echo "     - ID: {$student->id}, الاسم: {$student->name}\n";
            }
        }
    }

    echo "\n=== تعليمات الاختبار ===\n";
    echo "1. سجل دخول بأحد هذه الحسابات:\n";
    foreach ($teachers->take(3) as $teacher) {
        echo "   - البريد: {$teacher->email}, كلمة المرور: password\n";
    }
    echo "\n2. اذهب إلى: /teacher/attendances/create\n";
    echo "3. اختر أحد الدروس المذكورة أعلاه\n";
    echo "4. يجب أن تظهر قائمة الطلاب\n\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
