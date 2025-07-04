# حل مشكلة حفظ الدروس - تحديث
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "🔧 حل مشكلة حفظ الدروس - الإصدار المحدث" -ForegroundColor Green
Write-Host "=" * 50 -ForegroundColor Blue

Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"

Write-Host "`n📝 تشغيل اختبار حفظ الدروس..." -ForegroundColor Yellow
php test_lesson_save.php

Write-Host "`n🔧 تحديث ذاكرة التخزين المؤقت..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache

Write-Host "`n✅ تم إصلاح مشكلة حفظ الدروس!" -ForegroundColor Green
Write-Host "المشكلة كانت في عدم تطابق أسماء الحقول بين النموذج والمتحكم" -ForegroundColor Cyan
Write-Host "الآن يمكنك إنشاء الدروس بنجاح من الواجهة" -ForegroundColor Cyan

Write-Host "`n🌐 للاختبار:" -ForegroundColor Yellow
Write-Host "1. شغل الخادم: php artisan serve" -ForegroundColor White
Write-Host "2. اذهب إلى: http://127.0.0.1:8000/admin/login" -ForegroundColor White
Write-Host "3. سجل دخول بالبيانات: admin@basmah.com / password" -ForegroundColor White
Write-Host "4. اذهب إلى إدارة الدروس > إضافة درس جديد" -ForegroundColor White

Write-Host "`nاضغط أي مفتاح للإنهاء..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
