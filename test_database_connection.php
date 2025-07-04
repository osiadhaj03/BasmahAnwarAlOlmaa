<?php

echo "🔍 التحقق من قاعدة البيانات...\n";

try {
    // قراءة إعدادات قاعدة البيانات من .env
    $host = '127.0.0.1';
    $dbname = 'basmah';
    $username = 'root';
    $password = '';
    
    echo "محاولة الاتصال بـ MySQL...\n";
    echo "الخادم: $host\n";
    echo "قاعدة البيانات: $dbname\n";
    echo "المستخدم: $username\n\n";
    
    // الاتصال بـ MySQL أولاً (بدون تحديد قاعدة البيانات)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ تم الاتصال بخادم MySQL بنجاح!\n";
    
    // التحقق من وجود قاعدة البيانات
    $databases = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 قواعد البيانات الموجودة: " . implode(', ', $databases) . "\n\n";
    
    if (!in_array($dbname, $databases)) {
        echo "⚠️ قاعدة البيانات '$dbname' غير موجودة. إنشاؤها الآن...\n";
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ تم إنشاء قاعدة البيانات '$dbname' بنجاح!\n";
    } else {
        echo "✅ قاعدة البيانات '$dbname' موجودة!\n";
    }
    
    // الاتصال بقاعدة البيانات المحددة
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ تم الاتصال بقاعدة البيانات '$dbname' بنجاح!\n\n";
    
    // عرض الجداول الموجودة
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "📊 الجداول الموجودة (" . count($tables) . "):\n";
    
    if (empty($tables)) {
        echo "   لا توجد جداول. تحتاج لتشغيل migrations.\n";
        echo "   تشغيل الأمر: php artisan migrate\n";
    } else {
        foreach ($tables as $table) {
            $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            echo "   - $table: $count سجل\n";
        }
    }
    
    echo "\n🎯 قاعدة البيانات جاهزة للاستخدام!\n";
    
} catch (PDOException $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage() . "\n\n";
    
    echo "🔧 حلول مقترحة:\n";
    echo "1. تأكد من تشغيل خادم MySQL (XAMPP, WAMP, MAMP)\n";
    echo "2. تأكد من صحة بيانات الاتصال في ملف .env\n";
    echo "3. تأكد من وجود قاعدة البيانات 'basmah'\n";
    echo "4. جرب تشغيل: mysql -u root -p ثم CREATE DATABASE basmah;\n";
}
