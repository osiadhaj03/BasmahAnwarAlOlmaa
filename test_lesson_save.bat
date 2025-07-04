@echo off
echo ===================================================
echo           اختبار حفظ الدروس - BasmahApp
echo ===================================================
echo.

cd /d "c:\Users\abdul\OneDrive\Documents\BasmahApp"

echo خطوة 1: محاولة إصلاح قاعدة البيانات...
php quick_fix_database.php
if %errorlevel% neq 0 (
    echo تحذير: قد تحتاج لإصلاح قاعدة البيانات يدوياً
    echo راجع ملف COMPLETE_DATABASE_FIX.sql
)

echo.
echo خطوة 2: مسح الكاش...
php artisan cache:clear >nul 2>&1
php artisan config:clear >nul 2>&1
php artisan view:clear >nul 2>&1

echo خطوة 3: تشغيل الخادم...
echo الخادم يعمل على: http://127.0.0.1:8000
echo.
echo بيانات تسجيل الدخول للاختبار:
echo ==========================================
echo المعلم: teacher1@basmahapp.com / password
echo المدير: admin@basmahapp.com / password
echo ==========================================
echo.
echo اختبر الآن:
echo 1. سجل دخول بحساب معلم
echo 2. انتقل إلى إدارة الدروس
echo 3. جرب إنشاء أو تعديل درس
echo 4. تأكد من إمكانية الحفظ
echo.

start http://127.0.0.1:8000/admin/login

php artisan serve --host=127.0.0.1 --port=8000
