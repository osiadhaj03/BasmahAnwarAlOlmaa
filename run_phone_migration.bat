@echo off
echo Running migration to add phone and student_id to users table...

php artisan migrate --force

echo Migration completed!
pause
