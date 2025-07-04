# تشغيل نظام QR Code الكامل لـ BasmahApp

Write-Host "🚀 بدء إعداد نظام QR Code للحضور..." -ForegroundColor Green

# تثبيت حزمة QR Code
Write-Host "📦 تثبيت حزمة QR Code..." -ForegroundColor Yellow
composer require simplesoftwareio/simple-qrcode

# تشغيل Migration للقاعدة بيانات
Write-Host "🗄️ تحديث قاعدة البيانات..." -ForegroundColor Yellow
php artisan migrate

# إنشاء بيانات الدروس الكبيرة
Write-Host "📚 إنشاء مجموعة كبيرة من الدروس..." -ForegroundColor Yellow
php create_large_lessons_dataset.php

# تنظيف و إعادة تحميل Composer
Write-Host "🔄 تحديث Composer..." -ForegroundColor Yellow
composer dump-autoload

# تسجيل مقدم الخدمة
Write-Host "⚙️ تسجيل مقدم خدمة QR Code..." -ForegroundColor Yellow

# إضافة Service Provider إلى config/app.php إذا لم يكن موجوداً
$configPath = "config/app.php"
$content = Get-Content $configPath -Raw
if ($content -notmatch "SimpleSoftwareIO\\QrCode\\QrCodeServiceProvider") {
    Write-Host "إضافة Service Provider..." -ForegroundColor Cyan
    # سيتم إضافته تلقائياً في Laravel 11
}

Write-Host "✅ تم إعداد نظام QR Code بنجاح!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 ملخص الميزات الجديدة:" -ForegroundColor Cyan
Write-Host "   🔹 مجموعة كبيرة من الدروس (375+ درس)" -ForegroundColor White
Write-Host "   🔹 نظام QR Code للمعلمين" -ForegroundColor White
Write-Host "   🔹 ماسح QR Code للطلاب" -ForegroundColor White
Write-Host "   🔹 نافذة حضور محددة (15 دقيقة)" -ForegroundColor White
Write-Host "   🔹 تحقق من الصلاحيات والأمان" -ForegroundColor White
Write-Host ""
Write-Host "🎯 كيفية الاستخدام:" -ForegroundColor Cyan
Write-Host "   المعلم: انتقل إلى قائمة الدروس ← اضغط زر QR Code ← عرض على الشاشة" -ForegroundColor White
Write-Host "   الطالب: لوحة التحكم ← فتح ماسح QR Code ← امسح الكود" -ForegroundColor White
Write-Host ""
Write-Host "🔑 بيانات تسجيل الدخول:" -ForegroundColor Cyan
Write-Host "   المدير: admin@basmahapp.com / password" -ForegroundColor White
Write-Host "   المعلمين: teacher1@basmahapp.com - teacher{n}@basmahapp.com / password" -ForegroundColor White
Write-Host "   الطلاب: student1@basmahapp.com - student50@basmahapp.com / password" -ForegroundColor White
Write-Host ""
Write-Host "🌐 الروابط المهمة:" -ForegroundColor Cyan
Write-Host "   http://localhost/admin - لوحة المعلمين والإدارة" -ForegroundColor White
Write-Host "   http://localhost/student/dashboard - لوحة الطلاب" -ForegroundColor White
Write-Host "   http://localhost/qr-scanner - ماسح QR Code للطلاب" -ForegroundColor White

Write-Host ""
Write-Host "🎉 نظام BasmahApp جاهز للاستخدام!" -ForegroundColor Green
