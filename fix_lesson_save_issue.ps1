# حل مشكلة حفظ الدروس - BasmahApp
Write-Host "🔧 حل مشكلة حفظ الدروس" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Green

Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"

# مسح الكاش
Write-Host "`n📦 مسح الكاش..." -ForegroundColor Yellow
try {
    php artisan cache:clear
    php artisan config:clear
    php artisan view:clear
    Write-Host "✅ تم مسح الكاش" -ForegroundColor Green
} catch {
    Write-Host "⚠️ تحذير: فشل في مسح الكاش" -ForegroundColor Yellow
}

# إصلاح قاعدة البيانات
Write-Host "`n🔨 إصلاح قاعدة البيانات..." -ForegroundColor Yellow
try {
    php quick_fix_database.php
    Write-Host "✅ تم إصلاح qاعدة البيانات" -ForegroundColor Green
} catch {
    Write-Host "⚠️ قد تحتاج لإصلاح قاعدة البيانات يدوياً" -ForegroundColor Yellow
    Write-Host "💡 راجع ملف COMPLETE_DATABASE_FIX.sql" -ForegroundColor Cyan
}

# اختبار الاتصال
Write-Host "`n🔍 اختبار بنية قاعدة البيانات..." -ForegroundColor Yellow
$testScript = @'
<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=basmah;charset=utf8mb4", "root", "");
    $stmt = $pdo->query("DESCRIBE lessons");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $required = ['id', 'name', 'subject', 'teacher_id', 'day_of_week', 'start_time', 'end_time', 'description', 'schedule_time'];
    $missing = array_diff($required, $columns);
    
    if (empty($missing)) {
        echo "✅ جميع الأعمدة المطلوبة موجودة\n";
    } else {
        echo "❌ أعمدة مفقودة: " . implode(', ', $missing) . "\n";
    }
    
    // اختبار إدراج بسيط
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM lessons");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    echo "📊 عدد الدروس الموجودة: $count\n";
    
} catch (Exception $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage() . "\n";
}
'@

$testScript | Out-File -FilePath "test_db.php" -Encoding UTF8
php test_db.php
Remove-Item "test_db.php" -ErrorAction SilentlyContinue

# تشغيل الخادم
Write-Host "`n🌐 تشغيل الخادم..." -ForegroundColor Yellow
$serverJob = Start-Job -ScriptBlock {
    Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"
    php artisan serve --host=127.0.0.1 --port=8000
}

Start-Sleep -Seconds 3
Write-Host "✅ الخادم يعمل على: http://127.0.0.1:8000" -ForegroundColor Green

Write-Host @"

🧪 خطوات الاختبار:
================================
1. فتح المتصفح: http://127.0.0.1:8000/admin/login

2. تسجيل دخول بحساب معلم:
   📧 البريد: teacher1@basmahapp.com
   🔑 كلمة المرور: password

3. اختبار إنشاء درس جديد:
   ✅ انتقل إلى "الدروس" 
   ✅ اضغط "إنشاء جديد"
   ✅ املأ البيانات:
      - اسم الدرس: "اختبار الحفظ"
      - المادة: "اختبار"
      - يوم الأسبوع: أي يوم
      - وقت البداية: 10:00
      - وقت النهاية: 11:00
   ✅ اضغط "حفظ"

4. اختبار تعديل درس موجود:
   ✅ اختر درس موجود
   ✅ اضغط "تعديل"  
   ✅ غيّر أي تفاصيل
   ✅ اضغط "حفظ"

5. التأكد من النتائج:
   ✅ يجب أن يتم الحفظ بدون أخطاء
   ✅ يجب أن تظهر رسالة نجاح
   ✅ يجب أن تظهر التغييرات في القائمة

================================

"@ -ForegroundColor Cyan

Write-Host "🚨 إذا استمرت المشكلة:" -ForegroundColor Red
Write-Host "1. شغّل استعلامات SQL من ملف COMPLETE_DATABASE_FIX.sql يدوياً" -ForegroundColor White
Write-Host "2. أو استخدم: .\fix_lesson_management_complete.ps1 -ForceReset" -ForegroundColor White

# فتح المتصفح
Start-Sleep -Seconds 2
Start-Process "http://127.0.0.1:8000/admin/login"

Write-Host "`n⏳ الخادم يعمل... اضغط Ctrl+C لإيقافه" -ForegroundColor Yellow

# انتظار إيقاف الخادم
try {
    Wait-Job $serverJob
} catch {
    Write-Host "تم إيقاف الخادم" -ForegroundColor Yellow
} finally {
    Remove-Job $serverJob -Force -ErrorAction SilentlyContinue
}
