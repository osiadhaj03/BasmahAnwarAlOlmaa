<?php

// التحقق من البيانات الحالية في قاعدة البيانات
require_once 'vendor/autoload.php';

// التحقق من إعدادات قاعدة البيانات
$envFile = '.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    echo "📋 إعدادات قاعدة البيانات:\n";
    
    if (preg_match('/DB_CONNECTION=(.+)/', $envContent, $matches)) {
        echo "   النوع: " . trim($matches[1]) . "\n";
    }
    if (preg_match('/DB_DATABASE=(.+)/', $envContent, $matches)) {
        echo "   اسم قاعدة البيانات: " . trim($matches[1]) . "\n";
    }
    if (preg_match('/DB_HOST=(.+)/', $envContent, $matches)) {
        echo "   الخادم: " . trim($matches[1]) . "\n";
    }
}

echo "\n";

try {
    // محاولة الاتصال بـ MySQL
    $host = '127.0.0.1';
    $dbname = 'basmah';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ تم الاتصال بقاعدة البيانات MySQL بنجاح!\n\n";
    
    // التحقق من الجداول الموجودة
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "📊 الجداول الموجودة (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        echo "   - $table\n";
    }
    echo "\n";
    
    // إحصائيات البيانات
    $stats = [];
    
    if (in_array('users', $tables)) {
        $stats['total_users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['admins'] = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $stats['teachers'] = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher'")->fetchColumn();
        $stats['students'] = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
    }
    
    if (in_array('lessons', $tables)) {
        $stats['total_lessons'] = $pdo->query("SELECT COUNT(*) FROM lessons")->fetchColumn();
        
        // إحصائيات الدروس حسب اليوم
        $dayStats = $pdo->query("
            SELECT day_of_week, COUNT(*) as count 
            FROM lessons 
            GROUP BY day_of_week 
            ORDER BY FIELD(day_of_week, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        // إحصائيات المواد
        $subjectStats = $pdo->query("
            SELECT subject, COUNT(*) as count 
            FROM lessons 
            GROUP BY subject 
            ORDER BY count DESC 
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    if (in_array('lesson_student', $tables)) {
        $stats['enrollments'] = $pdo->query("SELECT COUNT(*) FROM lesson_student")->fetchColumn();
    }
    
    if (in_array('attendances', $tables)) {
        $stats['attendances'] = $pdo->query("SELECT COUNT(*) FROM attendances")->fetchColumn();
    }
    
    // عرض الإحصائيات
    echo "📈 إحصائيات البيانات:\n";
    echo "   👥 إجمالي المستخدمين: " . ($stats['total_users'] ?? 0) . "\n";
    echo "   🛡️  المدراء: " . ($stats['admins'] ?? 0) . "\n";
    echo "   👨‍🏫 المعلمين: " . ($stats['teachers'] ?? 0) . "\n";
    echo "   👨‍🎓 الطلاب: " . ($stats['students'] ?? 0) . "\n";
    echo "   📚 الدروس: " . ($stats['total_lessons'] ?? 0) . "\n";
    echo "   📝 التسجيلات: " . ($stats['enrollments'] ?? 0) . "\n";
    echo "   ✅ الحضور: " . ($stats['attendances'] ?? 0) . "\n";
    
    if (!empty($dayStats)) {
        echo "\n📅 توزيع الدروس حسب الأيام:\n";
        $dayNames = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين', 
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت'
        ];
        
        foreach ($dayStats as $day) {
            $dayName = $dayNames[$day['day_of_week']] ?? $day['day_of_week'];
            echo "   $dayName: " . $day['count'] . " درس\n";
        }
    }
    
    if (!empty($subjectStats)) {
        echo "\n📖 أهم المواد:\n";
        foreach ($subjectStats as $subject) {
            echo "   " . $subject['subject'] . ": " . $subject['count'] . " درس\n";
        }
    }
    
    // التحقق من وجود 375+ درس
    $lessonCount = $stats['total_lessons'] ?? 0;
    echo "\n🎯 تقييم الهدف:\n";
    if ($lessonCount >= 375) {
        echo "   ✅ تم تحقيق الهدف: $lessonCount درس (المطلوب 375+)\n";
    } else {
        $needed = 375 - $lessonCount;
        echo "   ⚠️  نحتاج لإضافة $needed درس للوصول للهدف (375+)\n";
        echo "   📊 الحالي: $lessonCount درس\n";
    }
    
} catch (PDOException $e) {
    echo "❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
    echo "\n🔧 تأكد من:\n";
    echo "   - تشغيل خادم MySQL\n";
    echo "   - وجود قاعدة البيانات 'basmah'\n";
    echo "   - صحة بيانات الاتصال في ملف .env\n";
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
}
