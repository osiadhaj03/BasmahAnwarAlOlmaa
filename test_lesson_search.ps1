# اختبار نظام البحث والفلترة المتقدم للدروس
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "🔍 اختبار نظام البحث والفلترة المتقدم للدروس" -ForegroundColor Green
Write-Host "=" * 60 -ForegroundColor Blue

Set-Location "c:\Users\abdul\OneDrive\Documents\BasmahApp"

Write-Host "`n📝 تشغيل اختبار البحث والفلترة..." -ForegroundColor Yellow
php test_lesson_search.php

Write-Host "`n🔧 تحديث ذاكرة التخزين المؤقت..." -ForegroundColor Yellow
php artisan view:cache
php artisan config:cache

Write-Host "`n✨ المميزات الجديدة المضافة:" -ForegroundColor Cyan
Write-Host "  🔍 بحث متقدم في المادة، الوصف، واسم المعلم" -ForegroundColor White
Write-Host "  📅 فلترة حسب يوم الأسبوع" -ForegroundColor White
Write-Host "  👨‍🏫 فلترة حسب المعلم (للمدير)" -ForegroundColor White
Write-Host "  ⏰ فلترة حسب الوقت (صباحي/مسائي/بعد الظهر)" -ForegroundColor White
Write-Host "  👥 فلترة حسب عدد الطلاب" -ForegroundColor White
Write-Host "  📊 ترتيب متقدم حسب معايير مختلفة" -ForegroundColor White
Write-Host "  🎨 تصميم جميل ومتطور" -ForegroundColor White

Write-Host "`n🌐 للاختبار:" -ForegroundColor Yellow
Write-Host "1. شغل الخادم: php artisan serve" -ForegroundColor White
Write-Host "2. اذهب إلى: http://127.0.0.1:8000/admin/lessons" -ForegroundColor White
Write-Host "3. جرب البحث والفلاتر المختلفة" -ForegroundColor White
Write-Host "4. اختبر الترتيب والفلاتر المدمجة" -ForegroundColor White

Write-Host "`n🎯 نصائح للاستخدام:" -ForegroundColor Green
Write-Host "• استخدم البحث لإيجاد مادة أو معلم محدد" -ForegroundColor Gray
Write-Host "• اختر يوم معين لعرض دروس ذلك اليوم فقط" -ForegroundColor Gray
Write-Host "• فلتر حسب الوقت لتنظيم جدولك" -ForegroundColor Gray
Write-Host "• راقب عدد الطلاب في كل درس" -ForegroundColor Gray
Write-Host "• اضغط Enter للبحث السريع" -ForegroundColor Gray

Write-Host "`nاضغط أي مفتاح للإنهاء..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
