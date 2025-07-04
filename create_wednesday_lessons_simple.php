<?php
echo "🗓️ إنشاء دروس يوم الأربعاء مع درس الساعة 4 العصر\n";
echo "================================================\n";

try {
    // الاتصال بقاعدة البيانات
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=basmah;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ تم الاتصال بقاعدة البيانات\n";
    
    // التأكد من وجود الأعمدة المطلوبة
    $columns = $pdo->query("SHOW COLUMNS FROM lessons")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('name', $columns)) {
        $pdo->exec("ALTER TABLE lessons ADD COLUMN name VARCHAR(255) AFTER id");
        echo "✅ تم إضافة عمود name\n";
    }
    
    if (!in_array('description', $columns)) {
        $pdo->exec("ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER end_time");
        echo "✅ تم إضافة عمود description\n";
    }
    
    if (!in_array('schedule_time', $columns)) {
        $pdo->exec("ALTER TABLE lessons ADD COLUMN schedule_time TIME NULL AFTER description");
        echo "✅ تم إضافة عمود schedule_time\n";
    }

    // جلب معلم للدروس
    $teacher = $pdo->query("SELECT id FROM users WHERE role = 'teacher' LIMIT 1")->fetch();
    if (!$teacher) {
        echo "❌ لا يوجد معلمين في النظام\n";
        exit(1);
    }
    
    $teacherId = $teacher['id'];
    echo "👨‍🏫 سيتم تعيين المعلم رقم: $teacherId\n";

    // حذف الدروس الموجودة ليوم الأربعاء
    $pdo->exec("DELETE FROM lesson_student WHERE lesson_id IN (SELECT id FROM lessons WHERE day_of_week = 'wednesday')");
    $pdo->exec("DELETE FROM lessons WHERE day_of_week = 'wednesday'");
    echo "🗑️ تم حذف الدروس الموجودة ليوم الأربعاء\n";

    // إنشاء دروس يوم الأربعاء
    $lessons = [
        [
            'name' => 'الرياضيات - الجبر الأساسي',
            'subject' => 'الرياضيات',
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
            'description' => 'درس الجبر الأساسي مع التمارين العملية.'
        ],
        [
            'name' => 'اللغة العربية - النحو والصرف',
            'subject' => 'اللغة العربية',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'description' => 'تعلم قواعد النحو والصرف في اللغة العربية.'
        ],
        [
            'name' => 'العلوم - الفيزياء التطبيقية',
            'subject' => 'العلوم',
            'start_time' => '12:30:00',
            'end_time' => '14:00:00',
            'description' => 'استكشاف مبادئ الفيزياء من خلال التجارب العملية.'
        ],
        [
            'name' => 'التاريخ - الحضارات القديمة',
            'subject' => 'التاريخ',
            'start_time' => '16:00:00', // 4 العصر
            'end_time' => '17:30:00',   // حتى 5:30
            'description' => '🎯 درس خاص لاختبار نظام تسجيل الحضور - دراسة الحضارات القديمة.'
        ],
        [
            'name' => 'اللغة الإنجليزية - المحادثة',
            'subject' => 'اللغة الإنجليزية',
            'start_time' => '18:00:00',
            'end_time' => '19:30:00',
            'description' => 'تطوير مهارات المحادثة في اللغة الإنجليزية.'
        ]
    ];

    echo "\n📚 إنشاء " . count($lessons) . " دروس جديدة...\n";

    $insertStmt = $pdo->prepare("
        INSERT INTO lessons (name, subject, teacher_id, day_of_week, start_time, end_time, schedule_time, description, created_at, updated_at) 
        VALUES (?, ?, ?, 'wednesday', ?, ?, ?, ?, NOW(), NOW())
    ");

    $createdLessons = [];
    
    foreach ($lessons as $lesson) {
        $insertStmt->execute([
            $lesson['name'],
            $lesson['subject'],
            $teacherId,
            $lesson['start_time'],
            $lesson['end_time'],
            $lesson['start_time'], // schedule_time = start_time
            $lesson['description']
        ]);
        
        $lessonId = $pdo->lastInsertId();
        $createdLessons[] = $lessonId;
        
        echo "✅ تم إنشاء: " . $lesson['name'] . " (" . $lesson['start_time'] . " - " . $lesson['end_time'] . ")";
        
        if ($lesson['start_time'] == '16:00:00') {
            echo " 🎯 ★ درس الساعة 4 العصر!";
        }
        echo "\n";
    }

    // تسجيل جميع الطلاب في جميع الدروس
    $students = $pdo->query("SELECT id FROM users WHERE role = 'student'")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($students)) {
        echo "\n👥 تسجيل " . count($students) . " طالب في جميع الدروس...\n";
        
        $enrollStmt = $pdo->prepare("INSERT INTO lesson_student (lesson_id, student_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        
        foreach ($createdLessons as $lessonId) {
            foreach ($students as $studentId) {
                $enrollStmt->execute([$lessonId, $studentId]);
            }
        }
        echo "✅ تم تسجيل جميع الطلاب في جميع الدروس\n";
    }

    // عرض ملخص الدروس المُنشأة
    echo "\n📊 ملخص دروس يوم الأربعاء:\n";
    echo "==========================================\n";
    
    $stmt = $pdo->query("
        SELECT l.*, u.name as teacher_name,
               (SELECT COUNT(*) FROM lesson_student ls WHERE ls.lesson_id = l.id) as students_count
        FROM lessons l 
        LEFT JOIN users u ON l.teacher_id = u.id 
        WHERE l.day_of_week = 'wednesday' 
        ORDER BY l.start_time
    ");
    
    while ($lesson = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "📖 {$lesson['name']}\n";
        echo "   ⏰ الوقت: {$lesson['start_time']} - {$lesson['end_time']}\n";
        echo "   👨‍🏫 المعلم: {$lesson['teacher_name']}\n";
        echo "   👥 عدد الطلاب: {$lesson['students_count']}\n";
        if ($lesson['start_time'] == '16:00:00') {
            echo "   🎯 ★ هذا هو درس الساعة 4 العصر المطلوب!\n";
        }
        echo "   ────────────────────────────────────────\n";
    }

    echo "\n🎉 تم إنشاء جميع دروس يوم الأربعاء بنجاح!\n";
    echo "\n📱 للاختبار الآن:\n";
    echo "1. افتح: http://127.0.0.1:8000/admin/login\n";
    echo "2. سجل دخول بحساب طالب: student1@basmahapp.com / password\n";
    echo "3. ابحث عن درس 'التاريخ - الحضارات القديمة' في الساعة 16:00 (4 العصر)\n";
    echo "4. جرب تسجيل الحضور\n";
    echo "\n🔗 أو سجل دخول كمعلم: teacher1@basmahapp.com / password\n";
    echo "واختبر إنشاء وتعديل الدروس\n";

} catch (PDOException $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage() . "\n";
    echo "تأكد من:\n";
    echo "1. تشغيل خادم MySQL\n";
    echo "2. وجود قاعدة بيانات باسم 'basmah'\n";
    echo "3. صحة بيانات الاتصال في ملف .env\n";
}
