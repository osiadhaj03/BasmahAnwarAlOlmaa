# BasmahApp Database Fix Script
Write-Host "إصلاح قاعدة البيانات لـ BasmahApp" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green

Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"

Write-Host "خطوة 1: تشغيل الميجريشن..." -ForegroundColor Yellow
try {
    php artisan migrate --force
    Write-Host "✓ تم تشغيل الميجريشن بنجاح" -ForegroundColor Green
} catch {
    Write-Host "⚠ فشل في تشغيل الميجريشن: $_" -ForegroundColor Red
}

Write-Host "`nخطوة 2: تشغيل أمر إصلاح قاعدة البيانات المخصص..." -ForegroundColor Yellow
try {
    php artisan basmah:fix-database
    Write-Host "✓ تم إصلاح قاعدة البيانات بنجاح" -ForegroundColor Green
} catch {
    Write-Host "⚠ فشل في إصلاح قاعدة البيانات: $_" -ForegroundColor Red
}

Write-Host "`nخطوة 3: تحديث البيانات..." -ForegroundColor Yellow
try {
    php artisan db:seed --class=BasmahAppSeeder --force
    Write-Host "✓ تم تحديث البيانات بنجاح" -ForegroundColor Green
} catch {
    Write-Host "⚠ فشل في تحديث البيانات: $_" -ForegroundColor Red
}

Write-Host "`nخطوة 4: تشغيل الخادم..." -ForegroundColor Yellow
try {
    Start-Process -FilePath "php" -ArgumentList "artisan", "serve" -NoNewWindow
    Write-Host "✓ تم تشغيل الخادم" -ForegroundColor Green
} catch {
    Write-Host "⚠ فشل في تشغيل الخادم: $_" -ForegroundColor Red
}

Write-Host "`n🎉 انتهى الإصلاح!" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green
Write-Host "يمكنك الآن:" -ForegroundColor Cyan
Write-Host "1. فتح المتصفح على: http://127.0.0.1:8000/admin/login" -ForegroundColor White
Write-Host "2. تسجيل دخول بحساب: admin@basmahapp.com / password" -ForegroundColor White
Write-Host "3. اختبار تعديل الدروس وإدارة يوم الأسبوع" -ForegroundColor White

Write-Host "`nبيانات تسجيل الدخول:" -ForegroundColor Cyan
Write-Host "المدير: admin@basmahapp.com / password" -ForegroundColor White
Write-Host "المعلمين: teacher1@basmahapp.com, teacher2@basmahapp.com / password" -ForegroundColor White
Write-Host "الطلاب: student1@basmahapp.com - student10@basmahapp.com / password" -ForegroundColor White

Read-Host "`nاضغط Enter للمتابعة..."
