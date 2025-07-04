@echo off
echo ========================================
echo    BasmahApp QR Code System Setup
echo ========================================
echo.

echo [1/4] Installing QR Code Package...
composer require simplesoftwareio/simple-qrcode

echo.
echo [2/4] Running Database Migration...
php artisan migrate --force

echo.
echo [3/4] Creating Large Dataset of Lessons...
php create_large_lessons_dataset.php

echo.
echo [4/4] Updating Composer Autoload...
composer dump-autoload

echo.
echo ========================================
echo          SETUP COMPLETED!
echo ========================================
echo.
echo QR Code System Features:
echo   - 375+ lessons across 5 days
echo   - 50 students and 8+ teachers  
echo   - QR Code generation for teachers
echo   - QR Code scanner for students
echo   - 15-minute attendance window
echo   - Secure encrypted QR codes
echo.
echo Login Credentials:
echo   Admin: admin@basmahapp.com / password
echo   Teachers: teacher1@basmahapp.com / password
echo   Students: student1@basmahapp.com / password
echo.
echo Access URLs:
echo   Admin Panel: http://localhost/admin
echo   Student Dashboard: http://localhost/student/dashboard
echo   QR Scanner: http://localhost/qr-scanner
echo.
echo Usage:
echo   Teacher: Login ^> Lessons ^> QR Code button ^> Display on screen
echo   Student: Login ^> Dashboard ^> QR Scanner ^> Scan code
echo.
pause
