@echo off
echo ===============================================
echo حل مشكلة الأعمدة المفقودة في BasmahApp
echo ===============================================
echo.

cd /d "c:\Users\abdul\OneDrive\Documents\BasmahApp"

echo خطوة 1: محاولة تشغيل الميجريشن العادي...
php artisan migrate --force
if %errorlevel% equ 0 (
    echo تم تشغيل الميجريشن بنجاح!
    goto :success
)

echo.
echo خطوة 2: محاولة إعادة تشغيل قاعدة البيانات...
echo تحذير: سيتم مسح جميع البيانات الموجودة!
set /p choice="هل تريد المتابعة؟ (y/n): "
if /i "%choice%"=="y" (
    php artisan migrate:fresh --seed --force
    if %errorlevel% equ 0 (
        echo تم إعادة إنشاء قاعدة البيانات بنجاح!
        goto :success
    )
)

echo.
echo خطوة 3: تعليمات الحل اليدوي...
echo يرجى فتح phpMyAdmin أو أي أداة إدارة MySQL وتشغيل الاستعلامات التالية:
echo.
echo ALTER TABLE lessons ADD COLUMN name VARCHAR(255) AFTER id;
echo ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER end_time;
echo ALTER TABLE lessons ADD COLUMN schedule_time TIME NULL AFTER description;
echo ALTER TABLE attendances ADD COLUMN notes TEXT NULL AFTER status;
echo UPDATE lessons SET name = CONCAT(subject, ' - الدرس') WHERE name IS NULL OR name = '';
echo.
echo أو راجع ملف DATABASE_FIX_GUIDE.md للتفاصيل الكاملة
goto :end

:success
echo.
echo ===============================================
echo تم إصلاح قاعدة البيانات بنجاح! 
echo ===============================================
echo.
echo يمكنك الآن:
echo 1. فتح http://127.0.0.1:8000/admin/login
echo 2. تسجيل دخول بحساب الإدارة: admin@basmahapp.com / password
echo 3. تجربة تحديث الدروس بدون أخطاء
echo.
echo بيانات تسجيل الدخول:
echo الطلاب: student1@basmahapp.com - student10@basmahapp.com / password
echo الإدارة: admin@basmahapp.com / password
echo المعلمين: teacher1@basmahapp.com, teacher2@basmahapp.com / password
echo.

:end
pause
