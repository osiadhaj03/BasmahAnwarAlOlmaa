<?php
// فحص اتصال قاعدة البيانات باستخدام إعدادات .env
require_once 'vendor/autoload.php';

// تحميل إعدادات .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== فحص اتصال قاعدة البيانات ===\n";
echo "الخادم: " . $_ENV['DB_HOST'] . "\n";
echo "قاعدة البيانات: " . $_ENV['DB_DATABASE'] . "\n";
echo "المستخدم: " . $_ENV['DB_USERNAME'] . "\n";

try {
    $dsn = "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']}";
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ تم الاتصال بقاعدة البيانات بنجاح!\n\n";
    
    // فحص الجداول
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "الجداول الموجودة:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    // فحص جدول lessons إذا كان موجوداً
    if (in_array('lessons', $tables)) {
        echo "\n=== فحص جدول lessons ===\n";
        $stmt = $pdo->query("DESCRIBE lessons");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "أعمدة جدول lessons:\n";
        foreach ($columns as $column) {
            echo "- {$column['Field']} ({$column['Type']})\n";
        }
        
        // فحص عدد الدروس
        $stmt = $pdo->query("SELECT COUNT(*) FROM lessons");
        $count = $stmt->fetchColumn();
        echo "\nعدد الدروس: $count\n";
        
        if ($count > 0) {
            // فحص بعض الدروس
            $stmt = $pdo->query("SELECT * FROM lessons LIMIT 3");
            $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "\nعينة من الدروس:\n";
            foreach ($lessons as $i => $lesson) {
                echo "درس " . ($i + 1) . ":\n";
                foreach ($lesson as $key => $value) {
                    echo "  $key: $value\n";
                }
                echo "---\n";
            }
        }
    } else {
        echo "\n❌ جدول lessons غير موجود!\n";
    }
    
} catch (PDOException $e) {
    echo "❌ خطأ في الاتصال: " . $e->getMessage() . "\n";
}

echo "\n=== فحص التوقيت ===\n";
date_default_timezone_set('Asia/Amman');
echo "الوقت الحالي في عمان: " . date('Y-m-d H:i:s A l') . "\n";
echo "التوقيت من .env: " . $_ENV['APP_TIMEZONE'] . "\n";
?>
