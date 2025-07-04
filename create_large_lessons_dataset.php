<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

// استبدل بيانات قاعدة البيانات الخاصة بك
$database = 'c:\Users\abdul\OneDrive\Documents\BasmahApp\database\database.sqlite';

try {
    $pdo = new PDO("sqlite:$database");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ تم الاتصال بقاعدة البيانات بنجاح\n";

    // إنشاء معلمين إضافيين
    $teachers = [
        ['name' => 'أ. محمد أحمد', 'email' => 'mohammed@basmahapp.com'],
        ['name' => 'أ. فاطمة علي', 'email' => 'fatima@basmahapp.com'],
        ['name' => 'أ. عبدالله حسن', 'email' => 'abdullah@basmahapp.com'],
        ['name' => 'أ. زينب محمد', 'email' => 'zeinab@basmahapp.com'],
        ['name' => 'أ. أحمد عبدالرحمن', 'email' => 'ahmed@basmahapp.com'],
        ['name' => 'أ. مريم خالد', 'email' => 'mariam@basmahapp.com'],
        ['name' => 'أ. عمر سالم', 'email' => 'omar@basmahapp.com'],
        ['name' => 'أ. نورا إبراهيم', 'email' => 'nora@basmahapp.com'],
    ];

    // إضافة المعلمين
    foreach ($teachers as $teacher) {
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, 'teacher', datetime('now'), datetime('now'))");
        $stmt->execute([$teacher['name'], $teacher['email'], password_hash('password', PASSWORD_DEFAULT)]);
    }

    // جلب جميع المعلمين
    $teacherIds = $pdo->query("SELECT id FROM users WHERE role = 'teacher'")->fetchAll(PDO::FETCH_COLUMN);
    echo "✅ تم إنشاء " . count($teacherIds) . " معلم\n";

    // إنشاء طلاب إضافيين (50 طالب)
    for ($i = 11; $i <= 50; $i++) {
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, 'student', datetime('now'), datetime('now'))");
        $stmt->execute(["طالب $i", "student$i@basmahapp.com", password_hash('password', PASSWORD_DEFAULT)]);
    }

    // جلب جميع الطلاب
    $studentIds = $pdo->query("SELECT id FROM users WHERE role = 'student'")->fetchAll(PDO::FETCH_COLUMN);
    echo "✅ تم إنشاء " . count($studentIds) . " طالب\n";

    // مواد دراسية متنوعة
    $subjects = [
        'الرياضيات', 'العلوم', 'اللغة العربية', 'اللغة الإنجليزية', 'التاريخ',
        'الجغرافيا', 'الفيزياء', 'الكيمياء', 'الأحياء', 'الحاسوب',
        'التربية الإسلامية', 'التربية الفنية', 'التربية الرياضية', 'الموسيقى', 'الفلسفة'
    ];

    // أيام الأسبوع
    $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
    $dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'];

    // أوقات الحصص
    $timeslots = [
        ['start' => '08:00', 'end' => '09:30'],
        ['start' => '09:45', 'end' => '11:15'],
        ['start' => '11:30', 'end' => '13:00'],
        ['start' => '14:00', 'end' => '15:30'],
        ['start' => '15:45', 'end' => '17:15'],
        ['start' => '17:30', 'end' => '19:00'],
    ];

    // إنشاء دروس شاملة
    $lessonCount = 0;
    foreach ($days as $dayIndex => $day) {
        foreach ($timeslots as $timeslot) {
            foreach ($subjects as $subject) {
                // اختيار معلم عشوائي
                $teacherId = $teacherIds[array_rand($teacherIds)];
                
                // إنشاء اسم الدرس
                $lessonName = "$subject - " . $dayNames[$dayIndex] . " " . $timeslot['start'];
                
                // إنشاء وصف الدرس
                $description = "درس $subject يوم " . $dayNames[$dayIndex] . " من " . $timeslot['start'] . " إلى " . $timeslot['end'];

                // إدراج الدرس
                $stmt = $pdo->prepare("
                    INSERT OR IGNORE INTO lessons 
                    (name, subject, teacher_id, day_of_week, start_time, end_time, schedule_time, description, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))
                ");
                
                $stmt->execute([
                    $lessonName,
                    $subject,
                    $teacherId,
                    $day,
                    $timeslot['start'],
                    $timeslot['end'],
                    $timeslot['start'],
                    $description
                ]);

                $lessonId = $pdo->lastInsertId();
                
                if ($lessonId) {
                    // تسجيل طلاب عشوائيين في الدرس (5-15 طالب لكل درس)
                    $numStudents = rand(5, 15);
                    $selectedStudents = array_rand(array_flip($studentIds), $numStudents);
                    
                    if (!is_array($selectedStudents)) {
                        $selectedStudents = [$selectedStudents];
                    }
                    
                    foreach ($selectedStudents as $studentId) {
                        $stmt = $pdo->prepare("INSERT OR IGNORE INTO lesson_student (lesson_id, student_id, created_at, updated_at) VALUES (?, ?, datetime('now'), datetime('now'))");
                        $stmt->execute([$lessonId, $studentId]);
                    }
                    
                    $lessonCount++;
                }
            }
        }
    }

    echo "✅ تم إنشاء $lessonCount درس بنجاح!\n";
    echo "📊 إحصائيات النظام:\n";
    echo "   - عدد المعلمين: " . count($teacherIds) . "\n";
    echo "   - عدد الطلاب: " . count($studentIds) . "\n";
    echo "   - عدد الدروس: $lessonCount\n";
    echo "   - عدد المواد: " . count($subjects) . "\n";
    echo "   - أيام الأسبوع: " . count($days) . "\n";
    echo "   - الحصص اليومية: " . count($timeslots) . "\n";

    // إحصائيات إضافية
    $totalEnrollments = $pdo->query("SELECT COUNT(*) FROM lesson_student")->fetchColumn();
    echo "   - إجمالي التسجيلات: $totalEnrollments\n";

    echo "\n🎉 تم إعداد قاعدة بيانات شاملة للنظام!\n";
    echo "🔑 بيانات تسجيل الدخول:\n";
    echo "   المدير: admin@basmahapp.com / password\n";
    echo "   المعلمين: teacher1@basmahapp.com - teacher{n}@basmahapp.com / password\n";
    echo "   الطلاب: student1@basmahapp.com - student50@basmahapp.com / password\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
