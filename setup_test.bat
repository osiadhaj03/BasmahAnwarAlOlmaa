@echo off
echo Setting up BasmahApp test environment...
cd /d "c:\Users\abdul\OneDrive\Documents\BasmahApp"

echo Running migrations...
php artisan migrate:fresh --force

echo Running seeder...
php artisan db:seed --class=BasmahAppSeeder

echo Starting development server...
start /B php artisan serve

echo Opening browser...
timeout /t 3
start http://127.0.0.1:8000/admin/login

echo.
echo ========================================
echo BasmahApp is ready for testing!
echo ========================================
echo Login credentials:
echo Student: student1@basmahapp.com / password
echo Admin: admin@basmahapp.com / password
echo ========================================
