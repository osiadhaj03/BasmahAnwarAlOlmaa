# حل شامل لمشكلة إدارة الدروس في BasmahApp
param(
    [switch]$ForceReset
)

Write-Host @"
 
====================================================
   حل مشكلة إدارة الدروس - BasmahApp
====================================================
المشاكل المحلولة:
✓ إضافة حقل اسم الدرس 
✓ إمكانية تعديل يوم الأسبوع
✓ عرض أسماء الدروس بشكل صحيح
✓ إضافة حقول الوصف ووقت الجدولة
====================================================

"@ -ForegroundColor Cyan

Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"

if ($ForceReset) {
    Write-Host "🔄 إعادة تعيين قاعدة البيانات بالكامل..." -ForegroundColor Yellow
    try {
        php artisan migrate:fresh --seed --force
        Write-Host "✅ تم إعادة تعيين قاعدة البيانات بنجاح" -ForegroundColor Green
    } catch {
        Write-Host "❌ فشل في إعادة التعيين: $($_.Exception.Message)" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "📥 تطبيق الميجريشن..." -ForegroundColor Yellow
    try {
        php artisan migrate --force
        Write-Host "✅ تم تطبيق الميجريشن" -ForegroundColor Green
    } catch {
        Write-Host "⚠️ تحذير: فشل في الميجريشن - $($_.Exception.Message)" -ForegroundColor Yellow
        Write-Host "💡 سنحاول الإصلاح اليدوي..." -ForegroundColor Cyan
    }
}

Write-Host "`n🔧 تشغيل إصلاح قاعدة البيانات المخصص..." -ForegroundColor Yellow
try {
    # إنشاء ملف PHP مؤقت للإصلاح
    $fixScript = @"
<?php
require_once 'vendor/autoload.php';
`$app = require_once 'bootstrap/app.php';
`$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "🔍 فحص بنية قاعدة البيانات...\n";
    
    // إضافة الأعمدة المفقودة
    `$queries = [
        "ALTER TABLE lessons ADD COLUMN IF NOT EXISTS name VARCHAR(255) AFTER id",
        "ALTER TABLE lessons ADD COLUMN IF NOT EXISTS description TEXT NULL AFTER end_time", 
        "ALTER TABLE lessons ADD COLUMN IF NOT EXISTS schedule_time TIME NULL AFTER description",
        "ALTER TABLE attendances ADD COLUMN IF NOT EXISTS notes TEXT NULL AFTER status"
    ];
    
    foreach (`$queries as `$query) {
        try {
            DB::statement(`$query);
            echo "✅ تم تنفيذ: `$query\n";
        } catch (Exception `$e) {
            if (strpos(`$e->getMessage(), 'Duplicate column') === false) {
                echo "⚠️ تحذير: `$query - " . `$e->getMessage() . "\n";
            }
        }
    }
    
    // تحديث البيانات
    DB::table('lessons')
        ->whereNull('name')
        ->orWhere('name', '')
        ->update(['name' => DB::raw('CONCAT(subject, " - الدرس")')]);
    echo "✅ تم تحديث أسماء الدروس\n";
    
    DB::table('lessons')
        ->whereNull('schedule_time')
        ->update(['schedule_time' => DB::raw('start_time')]);
    echo "✅ تم تحديث أوقات الجدولة\n";
    
    echo "🎉 تم إصلاح قاعدة البيانات بنجاح!\n";
    
} catch (Exception `$e) {
    echo "❌ خطأ: " . `$e->getMessage() . "\n";
    exit(1);
}
"@
    
    $fixScript | Out-File -FilePath "temp_fix.php" -Encoding UTF8
    php temp_fix.php
    Remove-Item "temp_fix.php" -ErrorAction SilentlyContinue
    
    Write-Host "✅ تم إصلاح قاعدة البيانات" -ForegroundColor Green
} catch {
    Write-Host "❌ فشل في الإصلاح: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "💡 يرجى تشغيل استعلامات SQL من ملف COMPLETE_DATABASE_FIX.sql يدوياً" -ForegroundColor Yellow
}

Write-Host "`n🌐 تشغيل الخادم..." -ForegroundColor Yellow
$serverProcess = Start-Process -FilePath "php" -ArgumentList "artisan", "serve", "--host=127.0.0.1", "--port=8000" -PassThru -WindowStyle Hidden

Write-Host "✅ تم تشغيل الخادم على http://127.0.0.1:8000" -ForegroundColor Green

Write-Host @"

🎯 اختبار النظام:
====================================================
1. فتح المتصفح: http://127.0.0.1:8000/admin/login
2. تسجيل دخول (اختر أحد الحسابات):

👨‍💼 حساب المدير:
   البريد: admin@basmahapp.com
   كلمة المرور: password

👨‍🏫 حساب المعلم:
   البريد: teacher1@basmahapp.com أو teacher2@basmahapp.com  
   كلمة المرور: password

👨‍🎓 حساب الطالب:
   البريد: student1@basmahapp.com (أو student2-student10)
   كلمة المرور: password

3. اختبار الميزات الجديدة:
   ✅ إنشاء درس جديد مع اسم مخصص
   ✅ تعديل يوم الأسبوع للدرس
   ✅ إضافة وصف للدرس
   ✅ تحديد وقت الجدولة
   ✅ عرض أسماء الدروس في القائمة

🛑 لإيقاف الخادم: اضغط Ctrl+C في نافذة الأوامر
====================================================

"@ -ForegroundColor White

Write-Host "🚀 النظام جاهز للاستخدام!" -ForegroundColor Green
Write-Host "📖 راجع ملف COMPLETE_DATABASE_FIX.sql للاستعلامات اليدوية إن لزم الأمر" -ForegroundColor Cyan

# فتح المتصفح تلقائياً
Start-Sleep -Seconds 2
Start-Process "http://127.0.0.1:8000/admin/login"

Write-Host "`n💡 نصيحة: استخدم المعامل -ForceReset لإعادة تعيين قاعدة البيانات بالكامل" -ForegroundColor Yellow
Write-Host "مثال: .\fix_lesson_management_complete.ps1 -ForceReset" -ForegroundColor Gray

Read-Host "`n📝 اضغط Enter للإنهاء"
