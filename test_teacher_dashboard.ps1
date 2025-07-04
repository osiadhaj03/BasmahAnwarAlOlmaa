#!/usr/bin/env pwsh
<#
.SYNOPSIS
اختبار لوحة تحكم المعلم - BasmahApp

.DESCRIPTION
هذا السكريبت يقوم بإجراء اختبار شامل للوحة تحكم المعلم
ويتحقق من جميع المكونات والوظائف

.EXAMPLE
.\test_teacher_dashboard.ps1
#>

Write-Host "===============================================" -ForegroundColor Cyan
Write-Host "اختبار لوحة تحكم المعلم - BasmahApp" -ForegroundColor Yellow
Write-Host "===============================================" -ForegroundColor Cyan
Write-Host ""

# تغيير المجلد إلى مجلد المشروع
$ProjectPath = "c:\Users\abdul\OneDrive\Documents\BasmahApp"
Set-Location $ProjectPath

Write-Host "🔍 فحص ملفات النظام..." -ForegroundColor Green
Write-Host ""

# 1. فحص Controllers
Write-Host "1. فحص Controllers:" -ForegroundColor Blue
$TeacherControllerPath = "app\Http\Controllers\Teacher\TeacherDashboardController.php"
if (Test-Path $TeacherControllerPath) {
    Write-Host "   ✅ TeacherDashboardController.php موجود" -ForegroundColor Green
} else {
    Write-Host "   ❌ TeacherDashboardController.php غير موجود" -ForegroundColor Red
}

# 2. فحص Middleware
Write-Host "`n2. فحص Middleware:" -ForegroundColor Blue
$TeacherMiddlewarePath = "app\Http\Middleware\TeacherMiddleware.php"
if (Test-Path $TeacherMiddlewarePath) {
    Write-Host "   ✅ TeacherMiddleware.php موجود" -ForegroundColor Green
} else {
    Write-Host "   ❌ TeacherMiddleware.php غير موجود" -ForegroundColor Red
}

# 3. فحص Views
Write-Host "`n3. فحص Views:" -ForegroundColor Blue
$TeacherDashboardViewPath = "resources\views\teacher\dashboard.blade.php"
if (Test-Path $TeacherDashboardViewPath) {
    Write-Host "   ✅ teacher/dashboard.blade.php موجود" -ForegroundColor Green
} else {
    Write-Host "   ❌ teacher/dashboard.blade.php غير موجود" -ForegroundColor Red
}

$AdminLayoutPath = "resources\views\layouts\admin.blade.php"
if (Test-Path $AdminLayoutPath) {
    Write-Host "   ✅ layouts/admin.blade.php موجود" -ForegroundColor Green
    
    # فحص محتوى الـ layout للتأكد من وجود navigation المعلم
    $layoutContent = Get-Content $AdminLayoutPath -Raw
    if ($layoutContent -match "teacher\.dashboard") {
        Write-Host "   ✅ قائمة تنقل المعلم مضافة" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️  قائمة تنقل المعلم غير مضافة" -ForegroundColor Yellow
    }
} else {
    Write-Host "   ❌ layouts/admin.blade.php غير موجود" -ForegroundColor Red
}

# 4. فحص Routes
Write-Host "`n4. فحص Routes:" -ForegroundColor Blue
$RoutesPath = "routes\web.php"
if (Test-Path $RoutesPath) {
    $routesContent = Get-Content $RoutesPath -Raw
    
    if ($routesContent -match "teacher/dashboard") {
        Write-Host "   ✅ teacher/dashboard route موجود" -ForegroundColor Green
    } else {
        Write-Host "   ❌ teacher/dashboard route غير موجود" -ForegroundColor Red
    }
    
    if ($routesContent -match "TeacherDashboardController") {
        Write-Host "   ✅ TeacherDashboardController مربوط" -ForegroundColor Green
    } else {
        Write-Host "   ❌ TeacherDashboardController غير مربوط" -ForegroundColor Red
    }
    
    if ($routesContent -match "teacher.*middleware") {
        Write-Host "   ✅ Teacher middleware مطبق" -ForegroundColor Green
    } else {
        Write-Host "   ❌ Teacher middleware غير مطبق" -ForegroundColor Red
    }
} else {
    Write-Host "   ❌ routes/web.php غير موجود" -ForegroundColor Red
}

# 5. فحص Bootstrap Configuration
Write-Host "`n5. فحص Bootstrap Configuration:" -ForegroundColor Blue
$BootstrapPath = "bootstrap\app.php"
if (Test-Path $BootstrapPath) {
    $bootstrapContent = Get-Content $BootstrapPath -Raw
    
    if ($bootstrapContent -match "TeacherMiddleware") {
        Write-Host "   ✅ TeacherMiddleware مسجل في bootstrap" -ForegroundColor Green
    } else {
        Write-Host "   ❌ TeacherMiddleware غير مسجل في bootstrap" -ForegroundColor Red
    }
} else {
    Write-Host "   ❌ bootstrap/app.php غير موجود" -ForegroundColor Red
}

# 6. اختبار اتصال قاعدة البيانات
Write-Host "`n6. اختبار قاعدة البيانات:" -ForegroundColor Blue
try {
    Write-Host "   🔄 جاري اختبار اتصال قاعدة البيانات..." -ForegroundColor Yellow
    $dbTest = php -r "
        require_once 'vendor/autoload.php';
        \$app = require_once 'bootstrap/app.php';
        \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
        \$kernel->bootstrap();
        
        try {
            \$teachersCount = DB::table('users')->where('role', 'teacher')->count();
            \$lessonsCount = DB::table('lessons')->count();
            \$attendancesCount = DB::table('attendances')->count();
            
            echo \"Teachers: {\$teachersCount}, Lessons: {\$lessonsCount}, Attendances: {\$attendancesCount}\";
        } catch (Exception \$e) {
            echo 'Error: ' . \$e->getMessage();
        }
    "
    
    if ($dbTest -match "Teachers: (\d+), Lessons: (\d+), Attendances: (\d+)") {
        $teachersCount = $matches[1]
        $lessonsCount = $matches[2]
        $attendancesCount = $matches[3]
        
        Write-Host "   ✅ قاعدة البيانات متصلة" -ForegroundColor Green
        Write-Host "   📊 عدد المعلمين: $teachersCount" -ForegroundColor Cyan
        Write-Host "   📚 عدد الدروس: $lessonsCount" -ForegroundColor Cyan
        Write-Host "   📋 عدد سجلات الحضور: $attendancesCount" -ForegroundColor Cyan
    } else {
        Write-Host "   ❌ مشكلة في قاعدة البيانات: $dbTest" -ForegroundColor Red
    }
} catch {
    Write-Host "   ❌ خطأ في اختبار قاعدة البيانات: $($_.Exception.Message)" -ForegroundColor Red
}

# 7. اختبار الأداء
Write-Host "`n7. اختبار الأداء:" -ForegroundColor Blue
try {
    Write-Host "   🔄 اختبار سرعة تحميل Controller..." -ForegroundColor Yellow
    $performanceTest = Measure-Command {
        php test_teacher_dashboard.php 2>$null | Out-Null
    }
    
    $executionTime = [math]::Round($performanceTest.TotalSeconds, 2)
    if ($executionTime -lt 5) {
        Write-Host "   ✅ الأداء جيد: $executionTime ثانية" -ForegroundColor Green
    } elseif ($executionTime -lt 10) {
        Write-Host "   ⚠️  الأداء متوسط: $executionTime ثانية" -ForegroundColor Yellow
    } else {
        Write-Host "   ❌ الأداء بطيء: $executionTime ثانية" -ForegroundColor Red
    }
} catch {
    Write-Host "   ⚠️  لا يمكن قياس الأداء" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "===============================================" -ForegroundColor Cyan
Write-Host "نتيجة الاختبار" -ForegroundColor Yellow
Write-Host "===============================================" -ForegroundColor Cyan

# حساب النتيجة الإجمالية
$totalTests = 10
$passedTests = 0

# فحص النتائج
if (Test-Path $TeacherControllerPath) { $passedTests++ }
if (Test-Path $TeacherMiddlewarePath) { $passedTests++ }
if (Test-Path $TeacherDashboardViewPath) { $passedTests++ }
if (Test-Path $AdminLayoutPath) { $passedTests++ }

$routesContent = Get-Content $RoutesPath -Raw -ErrorAction SilentlyContinue
if ($routesContent -match "teacher/dashboard") { $passedTests++ }
if ($routesContent -match "TeacherDashboardController") { $passedTests++ }

$bootstrapContent = Get-Content $BootstrapPath -Raw -ErrorAction SilentlyContinue
if ($bootstrapContent -match "TeacherMiddleware") { $passedTests++ }

if ($dbTest -match "Teachers:") { $passedTests += 3 }

$successRate = [math]::Round(($passedTests / $totalTests) * 100, 1)

if ($successRate -ge 90) {
    Write-Host "🎉 ممتاز! نجح $passedTests من $totalTests اختبار ($successRate%)" -ForegroundColor Green
    Write-Host "✅ لوحة تحكم المعلم جاهزة للاستخدام" -ForegroundColor Green
} elseif ($successRate -ge 70) {
    Write-Host "⚠️  جيد! نجح $passedTests من $totalTests اختبار ($successRate%)" -ForegroundColor Yellow
    Write-Host "🔧 يحتاج بعض التعديلات البسيطة" -ForegroundColor Yellow
} else {
    Write-Host "❌ يحتاج عمل! نجح $passedTests من $totalTests اختبار ($successRate%)" -ForegroundColor Red
    Write-Host "🛠️  يحتاج إصلاحات أساسية" -ForegroundColor Red
}

Write-Host ""
Write-Host "📖 خطوات الاختبار التالية:" -ForegroundColor Blue
Write-Host "   1. تشغيل الخادم: php artisan serve" -ForegroundColor White
Write-Host "   2. تسجيل الدخول كمعلم: /admin/login" -ForegroundColor White
Write-Host "   3. الانتقال للوحة التحكم: /teacher/dashboard" -ForegroundColor White
Write-Host "   4. اختبار الوظائف: تسجيل حضور، عرض الإحصائيات" -ForegroundColor White
Write-Host ""

# إنشاء ملف تقرير
$reportPath = "teacher_dashboard_test_report.txt"
$reportContent = @"
تقرير اختبار لوحة تحكم المعلم - BasmahApp
=============================================
تاريخ الاختبار: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')
معدل النجاح: $successRate%
الاختبارات الناجحة: $passedTests من $totalTests

تفاصيل الاختبارات:
- Controllers: $(if (Test-Path $TeacherControllerPath) { '✅' } else { '❌' })
- Middleware: $(if (Test-Path $TeacherMiddlewarePath) { '✅' } else { '❌' })
- Views: $(if (Test-Path $TeacherDashboardViewPath) { '✅' } else { '❌' })
- Routes: $(if ($routesContent -match 'teacher/dashboard') { '✅' } else { '❌' })
- Database: $(if ($dbTest -match 'Teachers:') { '✅' } else { '❌' })

التوصيات:
$(if ($successRate -ge 90) { 'النظام جاهز للإنتاج' } 
elseif ($successRate -ge 70) { 'يحتاج تحسينات بسيطة' } 
else { 'يحتاج مراجعة شاملة' })
"@

$reportContent | Out-File -FilePath $reportPath -Encoding UTF8
Write-Host "📄 تم حفظ التقرير في: $reportPath" -ForegroundColor Cyan
Write-Host ""

Write-Host "🚀 انتهى الاختبار!" -ForegroundColor Green
