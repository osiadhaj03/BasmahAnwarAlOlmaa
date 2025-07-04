# إنشاء دروس يوم الأربعاء مع درس الساعة 4 العصر
Write-Host "🗓️ إنشاء دروس يوم الأربعاء" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Green

Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"

# التأكد من تشغيل الخادم
Write-Host "`n🔄 التحقق من الخادم..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000" -TimeoutSec 5 -ErrorAction Stop
    Write-Host "✅ الخادم يعمل" -ForegroundColor Green
} catch {
    Write-Host "⚠️ الخادم لا يعمل - سيتم تشغيله..." -ForegroundColor Yellow
    Start-Job -ScriptBlock {
        Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"
        php artisan serve --host=127.0.0.1 --port=8000
    } | Out-Null
    Start-Sleep -Seconds 5
}

# إنشاء دروس يوم الأربعاء
Write-Host "`n📚 إنشاء دروس يوم الأربعاء..." -ForegroundColor Yellow

$phpScript = @'
<?php
echo "إنشاء دروس يوم الأربعاء...\n";

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=basmah;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // إضافة الأعمدة المفقودة
    $columns = $pdo->query("SHOW COLUMNS FROM lessons")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('name', $columns)) {
        $pdo->exec("ALTER TABLE lessons ADD COLUMN name VARCHAR(255) AFTER id");
        echo "✅ أضيف عمود name\n";
    }
    if (!in_array('description', $columns)) {
        $pdo->exec("ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER end_time");
        echo "✅ أضيف عمود description\n";
    }
    if (!in_array('schedule_time', $columns)) {
        $pdo->exec("ALTER TABLE lessons ADD COLUMN schedule_time TIME NULL AFTER description");
        echo "✅ أضيف عمود schedule_time\n";
    }

    // جلب معلم
    $teacher = $pdo->query("SELECT id FROM users WHERE role = 'teacher' LIMIT 1")->fetch();
    if (!$teacher) {
        echo "❌ لا يوجد معلمين\n";
        exit(1);
    }
    
    $teacherId = $teacher['id'];
    
    // حذف دروس الأربعاء الموجودة
    $pdo->exec("DELETE FROM lesson_student WHERE lesson_id IN (SELECT id FROM lessons WHERE day_of_week = 'wednesday')");
    $pdo->exec("DELETE FROM lessons WHERE day_of_week = 'wednesday'");
    
    // إنشاء الدروس الجديدة
    $lessons = [
        ['الرياضيات - الجبر', 'الرياضيات', '08:00:00', '09:30:00'],
        ['العربية - النحو', 'اللغة العربية', '10:00:00', '11:30:00'],
        ['العلوم - الفيزياء', 'العلوم', '12:30:00', '14:00:00'],
        ['التاريخ - الحضارات القديمة', 'التاريخ', '16:00:00', '17:30:00'],
        ['الإنجليزية - المحادثة', 'اللغة الإنجليزية', '18:00:00', '19:30:00']
    ];
    
    foreach ($lessons as $lesson) {
        $pdo->prepare("
            INSERT INTO lessons (name, subject, teacher_id, day_of_week, start_time, end_time, schedule_time, description, created_at, updated_at) 
            VALUES (?, ?, ?, 'wednesday', ?, ?, ?, 'درس تجريبي ليوم الأربعاء', NOW(), NOW())
        ")->execute([
            $lesson[0], $lesson[1], $teacherId, $lesson[2], $lesson[3], $lesson[2]
        ]);
        
        echo "✅ تم إنشاء: {$lesson[0]} ({$lesson[2]} - {$lesson[3]})";
        if ($lesson[2] == '16:00:00') echo " 🎯 درس الساعة 4!";
        echo "\n";
    }
    
    // تسجيل الطلاب
    $students = $pdo->query("SELECT id FROM users WHERE role = 'student'")->fetchAll(PDO::FETCH_COLUMN);
    $lessonIds = $pdo->query("SELECT id FROM lessons WHERE day_of_week = 'wednesday'")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($lessonIds as $lessonId) {
        foreach ($students as $studentId) {
            $pdo->prepare("INSERT INTO lesson_student (lesson_id, student_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())")->execute([$lessonId, $studentId]);
        }
    }
    
    echo "✅ تم تسجيل " . count($students) . " طالب في " . count($lessonIds) . " دروس\n";
    echo "🎉 انتهى بنجاح!\n";
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
'@

# كتابة وتشغيل السكريپت
$phpScript | Out-File -FilePath "temp_create_lessons.php" -Encoding UTF8
$result = php temp_create_lessons.php
Write-Host $result
Remove-Item "temp_create_lessons.php" -ErrorAction SilentlyContinue

Write-Host "`n📊 عرض الدروس المُنشأة..." -ForegroundColor Yellow

$checkScript = @'
<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=basmah;charset=utf8mb4", "root", "");
    $stmt = $pdo->query("
        SELECT l.name, l.start_time, l.end_time, u.name as teacher_name,
               (SELECT COUNT(*) FROM lesson_student ls WHERE ls.lesson_id = l.id) as students
        FROM lessons l 
        LEFT JOIN users u ON l.teacher_id = u.id 
        WHERE l.day_of_week = 'wednesday' 
        ORDER BY l.start_time
    ");
    
    echo "دروس يوم الأربعاء:\n";
    echo "==================\n";
    
    while ($lesson = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "📖 {$lesson['name']}\n";
        echo "   ⏰ {$lesson['start_time']} - {$lesson['end_time']}\n";
        echo "   👨‍🏫 {$lesson['teacher_name']}\n";
        echo "   👥 {$lesson['students']} طالب\n";
        if ($lesson['start_time'] == '16:00:00') {
            echo "   🎯 ★ درس الساعة 4 العصر للاختبار!\n";
        }
        echo "   ──────────────────────────\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
'@

$checkScript | Out-File -FilePath "temp_check_lessons.php" -Encoding UTF8
$lessonsResult = php temp_check_lessons.php
Write-Host $lessonsResult
Remove-Item "temp_check_lessons.php" -ErrorAction SilentlyContinue

Write-Host @"

🎯 دروس يوم الأربعاء جاهزة للاختبار!
====================================

📱 للاختبار:
1. افتح: http://127.0.0.1:8000/admin/login

2. اختبار حفظ الدروس (كمعلم):
   📧 teacher1@basmahapp.com / password
   - انتقل إلى "الدروس"
   - جرب إنشاء درس جديد
   - جرب تعديل درس موجود

3. اختبار تسجيل الحضور (كطالب):
   📧 student1@basmahapp.com / password
   - انتقل إلى لوحة التحكم
   - ابحث عن درس "التاريخ - الحضارات القديمة" (4:00 PM)
   - جرب تسجيل الحضور

🌟 درس الساعة 4 العصر متاح الآن للاختبار!

"@ -ForegroundColor Cyan

# فتح المتصفح
Start-Process "http://127.0.0.1:8000/admin/login"

Write-Host "✅ تم بنجاح!" -ForegroundColor Green
