@echo off
echo Adding phone and student_id fields to users table...

php add_phone_fields.php

echo.
echo Database update completed!
echo.
echo Now you can test the registration system:
echo 1. Visit: http://127.0.0.1:8000/admin/login
echo 2. Click "Create new student account" button
echo 3. Fill the form with phone number
echo.
pause
