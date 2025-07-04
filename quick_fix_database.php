<?php
// إصلاح سريع لقاعدة البيانات
echo "بدء إصلاح قاعدة البيانات...\n";

try {
    // الاتصال بقاعدة البيانات
    $host = '127.0.0.1';
    $username = 'root';
    $password = '';
    $database = 'basmah';

    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ تم الاتصال بقاعدة البيانات\n";
    
    // التحقق من الأعمدة الموجودة
    $stmt = $pdo->query("SHOW COLUMNS FROM lessons");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "الأعمدة الموجودة: " . implode(', ', $columns) . "\n";
    
    // إضافة الأعمدة المفقودة
    $addColumns = [];
    
    if (!in_array('name', $columns)) {
        $addColumns[] = "ADD COLUMN name VARCHAR(255) AFTER id";
    }
    
    if (!in_array('description', $columns)) {
        $addColumns[] = "ADD COLUMN description TEXT NULL AFTER end_time";
    }
    
    if (!in_array('schedule_time', $columns)) {
        $addColumns[] = "ADD COLUMN schedule_time TIME NULL AFTER description";
    }
    
    if (!empty($addColumns)) {
        $sql = "ALTER TABLE lessons " . implode(', ', $addColumns);
        $pdo->exec($sql);
        echo "✅ تم إضافة الأعمدة المفقودة\n";
    } else {
        echo "✅ جميع الأعمدة موجودة\n";
    }
    
    // تحديث الدروس بأسماء افتراضية
    $updateStmt = $pdo->prepare("UPDATE lessons SET name = CONCAT(subject, ' - الدرس') WHERE name IS NULL OR name = ''");
    $updateStmt->execute();
    echo "✅ تم تحديث أسماء الدروس\n";
    
    // تحديث schedule_time
    $updateSchedule = $pdo->prepare("UPDATE lessons SET schedule_time = start_time WHERE schedule_time IS NULL");
    $updateSchedule->execute();
    echo "✅ تم تحديث أوقات الجدولة\n";
    
    // التحقق من جدول attendances
    $stmt = $pdo->query("SHOW COLUMNS FROM attendances");
    $attendanceColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('notes', $attendanceColumns)) {
        $pdo->exec("ALTER TABLE attendances ADD COLUMN notes TEXT NULL AFTER status");
        echo "✅ تم إضافة عمود notes لجدول attendances\n";
    }
    
    echo "🎉 تم إصلاح قاعدة البيانات بنجاح!\n";
    
} catch (PDOException $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage() . "\n";
    echo "تأكد من:\n";
    echo "1. تشغيل خادم MySQL\n";
    echo "2. وجود قاعدة بيانات باسم 'basmah'\n";
    echo "3. صحة بيانات الاتصال\n";
    exit(1);
}
