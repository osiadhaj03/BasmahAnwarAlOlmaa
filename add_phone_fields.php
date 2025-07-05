<?php

/**
 * اختبار سريع لإضافة حقول قاعدة البيانات
 */

echo "=== إضافة حقول الهاتف ورقم الطالب ===\n\n";

try {
    // قراءة متغيرات البيئة
    $envFile = '.env';
    if (!file_exists($envFile)) {
        echo "❌ ملف .env غير موجود\n";
        exit;
    }
    
    $envContent = file_get_contents($envFile);
    
    // استخراج بيانات قاعدة البيانات
    preg_match('/DB_HOST=(.*)/', $envContent, $hostMatch);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $dbMatch);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $userMatch);
    preg_match('/DB_PASSWORD=(.*)/', $envContent, $passMatch);
    
    $host = isset($hostMatch[1]) ? trim($hostMatch[1]) : 'localhost';
    $database = isset($dbMatch[1]) ? trim($dbMatch[1]) : 'basmah';
    $username = isset($userMatch[1]) ? trim($userMatch[1]) : 'root';
    $password = isset($passMatch[1]) ? trim($passMatch[1]) : '';
    
    // الاتصال بقاعدة البيانات
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4", 
        $username, 
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ تم الاتصال بقاعدة البيانات بنجاح\n\n";
    
    // فحص الأعمدة الحالية
    echo "1. فحص الأعمدة الحالية في جدول users:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "الأعمدة الموجودة: " . implode(', ', $columns) . "\n\n";
    
    // إضافة عمود الهاتف إذا لم يكن موجوداً
    if (!in_array('phone', $columns)) {
        echo "2. إضافة عمود phone:\n";
        $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER email");
        echo "✅ تم إضافة عمود phone\n";
    } else {
        echo "2. عمود phone موجود بالفعل ✅\n";
    }
    
    // إضافة عمود رقم الطالب إذا لم يكن موجوداً  
    if (!in_array('student_id', $columns)) {
        echo "3. إضافة عمود student_id:\n";
        $pdo->exec("ALTER TABLE users ADD COLUMN student_id VARCHAR(50) NULL AFTER phone");
        echo "✅ تم إضافة عمود student_id\n";
    } else {
        echo "3. عمود student_id موجود بالفعل ✅\n";
    }
    
    // التحقق من الأعمدة الجديدة
    echo "\n4. التحقق من الأعمدة الجديدة:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM users");
    $newColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('phone', $newColumns) && in_array('student_id', $newColumns)) {
        echo "✅ جميع الأعمدة المطلوبة موجودة\n";
        echo "الأعمدة الحالية: " . implode(', ', $newColumns) . "\n";
    } else {
        echo "❌ بعض الأعمدة غير موجودة\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ تم تحديث قاعدة البيانات بنجاح!\n\n";
    
    echo "📝 الآن يمكنك:\n";
    echo "   1. اختبار صفحة التسجيل: http://127.0.0.1:8000/register\n";
    echo "   2. اختبار صفحة تسجيل الدخول: http://127.0.0.1:8000/admin/login\n";
    echo "   3. سيطلب منك رقم الهاتف عند التسجيل\n\n";
    
} catch (PDOException $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage() . "\n";
    echo "\nتأكد من:\n";
    echo "   • تشغيل خادم MySQL\n";
    echo "   • وجود قاعدة البيانات\n";
    echo "   • صحة بيانات الاتصال في .env\n";
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
}

echo "\nانتهى! ✅\n";
?>
