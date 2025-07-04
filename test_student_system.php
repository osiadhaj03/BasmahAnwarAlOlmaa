<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// إعداد Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار نظام الطالب - BasmahApp ===\n\n";

try {
    // اختبار الاتصال بقاعدة البيانات
    echo "1. اختبار الاتصال بقاعدة البيانات...\n";
    DB::connection()->getPdo();
    echo "✅ تم الاتصال بقاعدة البيانات بنجاح\n\n";

    // فحص البيانات الأساسية
    echo "2. فحص البيانات الأساسية:\n";
    
    // عدد الطلاب
    $studentsCount = DB::table('users')->where('role', 'student')->count();
    echo "   - عدد الطلاب: $studentsCount\n";
    
    // عدد الدروس
    $lessonsCount = DB::table('lessons')->count();
    echo "   - عدد الدروس: $lessonsCount\n";
    
    // عدد تسجيلات الطلاب في الدروس
    $enrollmentsCount = DB::table('lesson_student')->count();
    echo "   - عدد تسجيلات الطلاب: $enrollmentsCount\n";
    
    // عدد سجلات الحضور
    $attendanceCount = DB::table('attendances')->count();
    echo "   - عدد سجلات الحضور: $attendanceCount\n\n";

    // فحص الطلاب والدروس المرتبطة بهم
    echo "3. فحص تسجيل الطلاب في الدروس:\n";
    $studentsWithLessons = DB::table('users')
        ->join('lesson_student', 'users.id', '=', 'lesson_student.student_id')
        ->join('lessons', 'lesson_student.lesson_id', '=', 'lessons.id')
        ->join('users as teachers', 'lessons.teacher_id', '=', 'teachers.id')
        ->where('users.role', 'student')
        ->select(
            'users.name as student_name',
            'lessons.subject',
            'lessons.class_level',
            'lessons.day_of_week',
            'lessons.start_time',
            'teachers.name as teacher_name'
        )
        ->get();

    if ($studentsWithLessons->count() > 0) {
        foreach ($studentsWithLessons as $record) {
            echo "   - الطالب: {$record->student_name}\n";
            echo "     الدرس: {$record->subject} - {$record->class_level}\n";
            echo "     المعلم: {$record->teacher_name}\n";
            echo "     اليوم: {$record->day_of_week} - الوقت: {$record->start_time}\n";
            echo "     ────────────────────────────────\n";
        }
    } else {
        echo "   ⚠️ لا توجد تسجيلات للطلاب في الدروس\n";
    }

    // فحص دروس اليوم للطلاب
    echo "\n4. فحص دروس اليوم (الأربعاء):\n";
    $today = Carbon::today();
    $currentDayOfWeek = strtolower($today->format('l')); // wednesday
    
    $todayLessons = DB::table('lessons')
        ->join('users as teachers', 'lessons.teacher_id', '=', 'teachers.id')
        ->whereRaw('LOWER(day_of_week) = ?', [$currentDayOfWeek])
        ->select(
            'lessons.id',
            'lessons.subject',
            'lessons.class_level',
            'lessons.start_time',
            'lessons.end_time',
            'teachers.name as teacher_name'
        )
        ->get();

    echo "   اليوم: $currentDayOfWeek\n";
    echo "   عدد دروس اليوم: " . $todayLessons->count() . "\n";
    
    if ($todayLessons->count() > 0) {
        foreach ($todayLessons as $lesson) {
            echo "   - درس: {$lesson->subject} - {$lesson->class_level}\n";
            echo "     المعلم: {$lesson->teacher_name}\n";
            echo "     الوقت: {$lesson->start_time} - {$lesson->end_time}\n";
            
            // فحص الطلاب المسجلين في هذا الدرس
            $studentsInLesson = DB::table('lesson_student')
                ->join('users', 'lesson_student.student_id', '=', 'users.id')
                ->where('lesson_student.lesson_id', $lesson->id)
                ->where('users.role', 'student')
                ->count();
            
            echo "     عدد الطلاب المسجلين: $studentsInLesson\n";
            echo "     ────────────────────────────────\n";
        }
    }

    // فحص سجلات الحضور الحديثة
    echo "\n5. فحص سجلات الحضور الحديثة:\n";
    $recentAttendances = DB::table('attendances')
        ->join('users', 'attendances.student_id', '=', 'users.id')
        ->join('lessons', 'attendances.lesson_id', '=', 'lessons.id')
        ->join('users as teachers', 'lessons.teacher_id', '=', 'teachers.id')
        ->orderBy('attendances.date', 'desc')
        ->select(
            'users.name as student_name',
            'lessons.subject',
            'lessons.class_level',
            'teachers.name as teacher_name',
            'attendances.date',
            'attendances.status',
            'attendances.notes'
        )
        ->take(10)
        ->get();

    if ($recentAttendances->count() > 0) {
        foreach ($recentAttendances as $attendance) {
            echo "   - الطالب: {$attendance->student_name}\n";
            echo "     الدرس: {$attendance->subject} - {$attendance->class_level}\n";
            echo "     المعلم: {$attendance->teacher_name}\n";
            echo "     التاريخ: {$attendance->date}\n";
            echo "     الحالة: {$attendance->status}\n";
            if ($attendance->notes) {
                echo "     ملاحظات: {$attendance->notes}\n";
            }
            echo "     ────────────────────────────────\n";
        }
    } else {
        echo "   ⚠️ لا توجد سجلات حضور حديثة\n";
    }

    // إحصائيات الحضور لكل طالب
    echo "\n6. إحصائيات الحضور للطلاب:\n";
    $studentsStats = DB::table('users')
        ->leftJoin('attendances', 'users.id', '=', 'attendances.student_id')
        ->where('users.role', 'student')
        ->groupBy('users.id', 'users.name')
        ->select(
            'users.name',
            DB::raw('COUNT(attendances.id) as total_attendances'),
            DB::raw('SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) as present_count'),
            DB::raw('SUM(CASE WHEN attendances.status = "late" THEN 1 ELSE 0 END) as late_count'),
            DB::raw('SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) as absent_count')
        )
        ->get();

    foreach ($studentsStats as $stats) {
        $attendanceRate = $stats->total_attendances > 0 
            ? round(($stats->present_count / $stats->total_attendances) * 100, 1) 
            : 0;
        
        echo "   - الطالب: {$stats->name}\n";
        echo "     إجمالي السجلات: {$stats->total_attendances}\n";
        echo "     حاضر: {$stats->present_count} | متأخر: {$stats->late_count} | غائب: {$stats->absent_count}\n";
        echo "     معدل الحضور: {$attendanceRate}%\n";
        echo "     ────────────────────────────────\n";
    }

    echo "\n✅ اكتمل اختبار نظام الطالب بنجاح!\n\n";

    // تحقق من جودة البيانات
    echo "7. تقييم جودة البيانات:\n";
    
    $issues = [];
    
    if ($studentsCount == 0) {
        $issues[] = "لا يوجد طلاب في النظام";
    }
    
    if ($lessonsCount == 0) {
        $issues[] = "لا توجد دروس في النظام";
    }
    
    if ($enrollmentsCount == 0) {
        $issues[] = "لا توجد تسجيلات للطلاب في الدروس";
    }
    
    if ($todayLessons->count() == 0) {
        $issues[] = "لا توجد دروس مجدولة لليوم الحالي ($currentDayOfWeek)";
    }
    
    if (empty($issues)) {
        echo "   ✅ البيانات جاهزة للاستخدام!\n";
        echo "   ✅ يمكن للطلاب تسجيل الدخول واستخدام النظام\n";
        echo "   ✅ نظام الحضور جاهز للعمل\n";
    } else {
        echo "   ⚠️ مشاكل تم العثور عليها:\n";
        foreach ($issues as $issue) {
            echo "   - $issue\n";
        }
    }

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== انتهى الاختبار ===\n";
?>
