@echo off
echo === تشغيل اختبار إنشاء الدروس المبسط ===
echo.

REM التحقق من وجود PHP
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo خطأ: PHP غير مثبت أو غير موجود في PATH
    pause
    exit /b 1
)

REM تشغيل الاختبار
echo تشغيل اختبار إنشاء الدروس المبسط...
echo.

php test_simple_lesson_creation.php

echo.
echo === انتهى الاختبار ===
pause
