@echo off
chcp 65001 > nul
cls
echo ===============================================
echo     BasmahApp - إنشاء 375+ درس
echo ===============================================
echo.

echo [STEP 1] تشغيل Migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo خطأ في Migrations!
    pause
    exit /b 1
)

echo.
echo [STEP 2] إنشاء البيانات الضخمة...
php create_massive_lessons_dataset.php
if %errorlevel% neq 0 (
    echo خطأ في إنشاء البيانات!
    pause
    exit /b 1
)

echo.
echo [STEP 3] التحقق من النتائج...
php check_database_status.php

echo.
echo ===============================================
echo            تم الانتهاء بنجاح!
echo ===============================================
echo.
echo الآن يمكنك الوصول للنظام:
echo - لوحة الإدارة: http://localhost/admin
echo - لوحة الطلاب: http://localhost/student/dashboard
echo.
pause
