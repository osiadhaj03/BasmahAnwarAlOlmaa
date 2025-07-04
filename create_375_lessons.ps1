Write-Host "===============================================" -ForegroundColor Green
Write-Host "     BasmahApp - إنشاء 375+ درس" -ForegroundColor Green  
Write-Host "===============================================" -ForegroundColor Green
Write-Host ""

Write-Host "[STEP 1] تشغيل Migrations..." -ForegroundColor Yellow
$result = & php artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "خطأ في Migrations!" -ForegroundColor Red
    Read-Host "اضغط Enter للمتابعة"
    exit 1
}

Write-Host ""
Write-Host "[STEP 2] إنشاء البيانات الضخمة..." -ForegroundColor Yellow
$result = & php create_massive_lessons_dataset.php
if ($LASTEXITCODE -ne 0) {
    Write-Host "خطأ في إنشاء البيانات!" -ForegroundColor Red
    Read-Host "اضغط Enter للمتابعة"
    exit 1
}

Write-Host ""
Write-Host "[STEP 3] التحقق من النتائج..." -ForegroundColor Yellow
& php check_database_status.php

Write-Host ""
Write-Host "===============================================" -ForegroundColor Green
Write-Host "            تم الانتهاء بنجاح!" -ForegroundColor Green
Write-Host "===============================================" -ForegroundColor Green
Write-Host ""
Write-Host "الآن يمكنك الوصول للنظام:" -ForegroundColor Cyan
Write-Host "- لوحة الإدارة: http://localhost/admin" -ForegroundColor White
Write-Host "- لوحة الطلاب: http://localhost/student/dashboard" -ForegroundColor White
Write-Host ""
Read-Host "اضغط Enter للانتهاء"
