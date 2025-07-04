# إعداد الترميز لدعم العربية
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "🚀 بدء الاختبار الشامل لنظام BasmahApp" -ForegroundColor Green
Write-Host "=" * 50 -ForegroundColor Blue

# الانتقال إلى مجلد المشروع
Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"

# تشغيل الاختبار الشامل
try {
    php comprehensive_system_test.php
    Write-Host "`n✅ تم إكمال الاختبار بنجاح!" -ForegroundColor Green
} catch {
    Write-Host "`n❌ حدث خطأ في تشغيل الاختبار: $_" -ForegroundColor Red
}

Write-Host "`nاضغط أي مفتاح للإنهاء..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
