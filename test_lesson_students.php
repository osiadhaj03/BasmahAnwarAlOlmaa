<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// إعداد قاعدة البيانات
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'basmah_app',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== اختبار الطلاب في الدروس ===\n\n";

try {
    $pdo = $capsule->getConnection()->getPdo();
    
    // فحص جدول lesson_student
    echo "📋 فحص جدول lesson_student:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM lesson_student");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "عدد العلاقات بين الطلاب والدروس: " . $count['count'] . "\n\n";
    
    if ($count['count'] > 0) {
        echo "📝 أمثلة على العلاقات:\n";
        $stmt = $pdo->query("
            SELECT ls.lesson_id, ls.student_id, l.subject, u.name as student_name
            FROM lesson_student ls
            JOIN lessons l ON ls.lesson_id = l.id
            JOIN users u ON ls.student_id = u.id
            LIMIT 5
        ");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - الدرس: {$row['subject']} | الطالب: {$row['student_name']}\n";
        }
    } else {
        echo "⚠️ لا توجد طلاب مسجلين في أي درس!\n";
        echo "سأقوم بإضافة بعض الطلاب للدروس...\n\n";
        
        // البحث عن الدروس والطلاب
        $lessons = $pdo->query("SELECT id, subject FROM lessons LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
        $students = $pdo->query("SELECT id, name FROM users WHERE role = 'student' LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($lessons)) {
            echo "❌ لا توجد دروس في النظام!\n";
        } elseif (empty($students)) {
            echo "❌ لا توجد طلاب في النظام!\n";
        } else {
            echo "✅ إضافة طلاب للدروس:\n";
            
            foreach ($lessons as $lesson) {
                echo "📚 الدرس: {$lesson['subject']}\n";
                
                foreach ($students as $student) {
                    try {
                        $stmt = $pdo->prepare("
                            INSERT IGNORE INTO lesson_student (lesson_id, student_id, created_at, updated_at)
                            VALUES (?, ?, NOW(), NOW())
                        ");
                        $stmt->execute([$lesson['id'], $student['id']]);
                        echo "  ✓ تم إضافة الطالب: {$student['name']}\n";
                    } catch (Exception $e) {
                        echo "  ❌ خطأ في إضافة الطالب {$student['name']}: " . $e->getMessage() . "\n";
                    }
                }
                echo "\n";
            }
        }
    }
    
    echo "\n=== انتهى الاختبار ===\n";
    
} catch (PDOException $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
}
