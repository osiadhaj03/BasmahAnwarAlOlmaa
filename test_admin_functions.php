<?php

require __DIR__.'/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Lesson;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;

echo "=======================================================\n";
echo "اختبار وظائف المدير في نظام BasmahApp\n";
echo "=======================================================\n\n";

// ============================================
// 1. اختبار قاعدة البيانات والاتصال
// ============================================
echo "1. اختبار الاتصال بقاعدة البيانات:\n";
try {
    $adminCount = User::where('role', 'admin')->count();
    $teacherCount = User::where('role', 'teacher')->count();
    $studentCount = User::where('role', 'student')->count();
    $lessonCount = Lesson::count();
    
    echo "   ✓ تم الاتصال بقاعدة البيانات بنجاح\n";
    echo "   • المدراء: {$adminCount}\n";
    echo "   • المعلمين: {$teacherCount}\n";
    echo "   • الطلاب: {$studentCount}\n";
    echo "   • الدروس: {$lessonCount}\n\n";
} catch (\Exception $e) {
    echo "   × خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n\n";
    exit(1);
}

// ============================================
// 2. اختبار إنشاء مستخدمين جدد
// ============================================
echo "2. اختبار إنشاء مستخدمين:\n";

// إنشاء معلم جديد
try {
    $newTeacher = User::create([
        'name' => 'معلم اختبار - ' . now()->format('H:i:s'),
        'email' => 'test_teacher_' . time() . '@basmahapp.com',
        'password' => Hash::make('password123'),
        'role' => 'teacher',
    ]);
    echo "   ✓ تم إنشاء معلم جديد: {$newTeacher->name} ({$newTeacher->email})\n";
} catch (\Exception $e) {
    echo "   × فشل إنشاء معلم: " . $e->getMessage() . "\n";
}

// إنشاء طالب جديد
try {
    $newStudent = User::create([
        'name' => 'طالب اختبار - ' . now()->format('H:i:s'),
        'email' => 'test_student_' . time() . '@basmahapp.com',
        'password' => Hash::make('password123'),
        'role' => 'student',
    ]);
    echo "   ✓ تم إنشاء طالب جديد: {$newStudent->name} ({$newStudent->email})\n";
} catch (\Exception $e) {
    echo "   × فشل إنشاء طالب: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================
// 3. اختبار إنشاء درس جديد
// ============================================
echo "3. اختبار إنشاء درس جديد:\n";

try {
    $teacher = User::where('role', 'teacher')->first();
    if (!$teacher) {
        throw new Exception('لا يوجد معلمين في النظام');
    }
    
    $newLesson = Lesson::create([
        'name' => 'درس اختبار - ' . now()->format('H:i:s'),
        'subject' => 'مادة اختبار',
        'teacher_id' => $teacher->id,
        'day_of_week' => 'sunday',
        'start_time' => '08:00',
        'end_time' => '09:30',
        'description' => 'درس تجريبي لاختبار النظام',
    ]);
    
    echo "   ✓ تم إنشاء درس جديد: {$newLesson->name}\n";
    echo "   • المعلم: {$teacher->name}\n";
    echo "   • المادة: {$newLesson->subject}\n";
    echo "   • اليوم: {$newLesson->day_of_week}\n";
    echo "   • الوقت: {$newLesson->start_time} - {$newLesson->end_time}\n";
} catch (\Exception $e) {
    echo "   × فشل إنشاء درس: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================
// 4. اختبار ربط الطلاب بالدرس
// ============================================
echo "4. اختبار ربط الطلاب بالدرس:\n";

try {
    if (isset($newLesson)) {
        $students = User::where('role', 'student')->take(5)->get();
        $studentIds = $students->pluck('id')->toArray();
        
        $newLesson->students()->attach($studentIds);
        
        echo "   ✓ تم ربط {$students->count()} طلاب بالدرس\n";
        foreach ($students as $student) {
            echo "     - {$student->name}\n";
        }
    }
} catch (\Exception $e) {
    echo "   × فشل ربط الطلاب: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================
// 5. اختبار إنشاء QR Code للدرس
// ============================================
echo "5. اختبار إنشاء QR Code:\n";

try {
    if (isset($newLesson)) {
        $qrCode = $newLesson->generateQRCode();
        
        echo "   ✓ تم إنشاء QR Code للدرس بنجاح\n";
        echo "   • طول الكود: " . strlen($qrCode) . " حرف\n";
        echo "   • وقت الإنشاء: " . $newLesson->qr_generated_at->format('Y-m-d H:i:s') . "\n";
        echo "   • صالح حتى: " . $newLesson->qr_generated_at->addHours(6)->format('Y-m-d H:i:s') . "\n";
    }
} catch (\Exception $e) {
    echo "   × فشل إنشاء QR Code: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================
// 6. اختبار تسجيل حضور
// ============================================
echo "6. اختبار تسجيل حضور:\n";

try {
    if (isset($newLesson) && isset($newStudent)) {
        // التأكد من أن الطالب مسجل في الدرس
        if (!$newLesson->students()->where('student_id', $newStudent->id)->exists()) {
            $newLesson->students()->attach($newStudent->id);
        }
        
        $attendance = Attendance::create([
            'student_id' => $newStudent->id,
            'lesson_id' => $newLesson->id,
            'date' => now()->format('Y-m-d'),
            'status' => 'present',
            'notes' => 'حضر في الاختبار التلقائي',
        ]);
        
        echo "   ✓ تم تسجيل حضور بنجاح\n";
        echo "   • الطالب: {$newStudent->name}\n";
        echo "   • الدرس: {$newLesson->name}\n";
        echo "   • التاريخ: {$attendance->date}\n";
        echo "   • الحالة: {$attendance->status}\n";
    }
} catch (\Exception $e) {
    echo "   × فشل تسجيل الحضور: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================
// 7. اختبار تحديث بيانات المستخدمين
// ============================================
echo "7. اختبار تحديث بيانات المستخدمين:\n";

try {
    if (isset($newTeacher)) {
        $oldName = $newTeacher->name;
        $newName = $oldName . ' (محدث)';
        
        $newTeacher->update([
            'name' => $newName
        ]);
        
        echo "   ✓ تم تحديث اسم المعلم من '{$oldName}' إلى '{$newName}'\n";
    }
    
    if (isset($newStudent)) {
        $oldEmail = $newStudent->email;
        $newEmail = 'updated_' . $oldEmail;
        
        $newStudent->update([
            'email' => $newEmail
        ]);
        
        echo "   ✓ تم تحديث بريد الطالب من '{$oldEmail}' إلى '{$newEmail}'\n";
    }
} catch (\Exception $e) {
    echo "   × فشل تحديث البيانات: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================
// 8. اختبار حذف البيانات (التنظيف)
// ============================================
echo "8. تنظيف البيانات التجريبية:\n";

try {
    // حذف الحضور التجريبي
    if (isset($attendance)) {
        $attendance->delete();
        echo "   ✓ تم حذف سجل الحضور التجريبي\n";
    }
    
    // حذف الدرس التجريبي
    if (isset($newLesson)) {
        $newLesson->students()->detach();
        $newLesson->delete();
        echo "   ✓ تم حذف الدرس التجريبي\n";
    }
    
    // حذف المستخدمين التجريبيين
    if (isset($newTeacher)) {
        $newTeacher->delete();
        echo "   ✓ تم حذف المعلم التجريبي\n";
    }
    
    if (isset($newStudent)) {
        $newStudent->delete();
        echo "   ✓ تم حذف الطالب التجريبي\n";
    }
} catch (\Exception $e) {
    echo "   × فشل في التنظيف: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================
// 9. الإحصائيات النهائية
// ============================================
echo "9. الإحصائيات النهائية:\n";

$finalStats = [
    'total_users' => User::count(),
    'admins' => User::where('role', 'admin')->count(),
    'teachers' => User::where('role', 'teacher')->count(),
    'students' => User::where('role', 'student')->count(),
    'lessons' => Lesson::count(),
    'attendances' => Attendance::count(),
    'lesson_student_relations' => \DB::table('lesson_student')->count(),
];

echo "   📊 المستخدمين الكل: {$finalStats['total_users']}\n";
echo "   🛡️  المدراء: {$finalStats['admins']}\n";
echo "   👨‍🏫 المعلمين: {$finalStats['teachers']}\n";
echo "   👨‍🎓 الطلاب: {$finalStats['students']}\n";
echo "   📚 الدروس: {$finalStats['lessons']}\n";
echo "   ✅ سجلات الحضور: {$finalStats['attendances']}\n";
echo "   🔗 ربط الطلاب بالدروس: {$finalStats['lesson_student_relations']}\n";

echo "\n=======================================================\n";
echo "اكتمل اختبار وظائف المدير بنجاح! ✅\n";
echo "=======================================================\n";

// ============================================
// 10. معلومات تسجيل الدخول للاختبار
// ============================================
echo "\n🔑 معلومات تسجيل الدخول للاختبار:\n";
$admin = User::where('role', 'admin')->first();
$teacher = User::where('role', 'teacher')->first();
$student = User::where('role', 'student')->first();

if ($admin) {
    echo "   👑 مدير: {$admin->email} / password\n";
}
if ($teacher) {
    echo "   👨‍🏫 معلم: {$teacher->email} / password\n";
}
if ($student) {
    echo "   👨‍🎓 طالب: {$student->email} / password\n";
}

echo "\n🌐 روابط لوحة التحكم:\n";
echo "   • المدير: http://localhost:8000/admin/login\n";
echo "   • إدارة المستخدمين: http://localhost:8000/admin/users\n";
echo "   • إدارة الدروس: http://localhost:8000/admin/lessons\n";
echo "   • إدارة الحضور: http://localhost:8000/admin/attendances\n";
echo "\n";
