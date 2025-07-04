@echo off
echo ========================================
echo     BasmahApp QR Test System 
echo ========================================
echo.

echo [1] Starting Laravel Server...
start /B php artisan serve

echo.
echo [2] Waiting for server to start...
timeout /t 3 /nobreak >nul

echo.
echo [3] Opening QR Test Page...
start http://127.0.0.1:8000/qr-test-page

echo.
echo ========================================
echo           QR SYSTEM READY!
echo ========================================
echo.
echo Available URLs:
echo   - QR Test Page: http://127.0.0.1:8000/qr-test-page
echo   - Admin Panel: http://127.0.0.1:8000/admin/login
echo   - Direct QR for Lesson ID 1: http://127.0.0.1:8000/quick-qr/1
echo.
echo Credentials:
echo   Admin: admin@basmahapp.com / password
echo   Teacher: teacher@basmahapp.com / password
echo   Student: student@basmahapp.com / password
echo.
pause
