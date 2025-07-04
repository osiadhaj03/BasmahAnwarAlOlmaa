# حل مشكلة الأعمدة المفقودة في جدول lessons

## المشكلة
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'name' in 'field list'
```

هذا الخطأ يحدث لأن جدول `lessons` لا يحتوي على الأعمدة الجديدة (`name`, `description`, `schedule_time`).

## الحلول

### الحل 1: تشغيل الميجريشن من Terminal
افتح PowerShell في مجلد المشروع وقم بتشغيل:

```powershell
cd "c:\Users\abdul\OneDrive\Documents\BasmahApp"
php artisan migrate
```

### الحل 2: تشغيل الميجريشن يدوياً من phpMyAdmin أو MySQL
قم بفتح phpMyAdmin أو أي أداة إدارة MySQL وتشغيل هذه الاستعلامات:

```sql
-- إضافة الأعمدة المفقودة لجدول lessons
ALTER TABLE lessons ADD COLUMN name VARCHAR(255) AFTER id;
ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER end_time;
ALTER TABLE lessons ADD COLUMN schedule_time TIME NULL AFTER description;

-- إضافة عمود notes لجدول attendances
ALTER TABLE attendances ADD COLUMN notes TEXT NULL AFTER status;

-- تحديث الدروس الموجودة بأسماء افتراضية
UPDATE lessons SET name = CONCAT(subject, ' - الدرس') WHERE name IS NULL OR name = '';
```

### الحل 3: إعادة إنشاء قاعدة البيانات
إذا لم تكن تمانع في فقدان البيانات الموجودة:

```powershell
cd "c:\Users\abdul\OneDrive\Documents\BasmahApp"
php artisan migrate:fresh --seed
```

## التحقق من نجاح الحل
بعد تطبيق أي من الحلول أعلاه، يجب أن تحتوي جداول قاعدة البيانات على:

### جدول lessons:
- id
- name (جديد)
- subject
- teacher_id
- day_of_week
- start_time
- end_time
- description (جديد)
- schedule_time (جديد)
- created_at
- updated_at

### جدول attendances:
- id
- student_id
- lesson_id
- date
- status
- notes (جديد)
- created_at
- updated_at

## اختبار النظام
بعد إصلاح قاعدة البيانات:

1. انتقل إلى: http://127.0.0.1:8000/admin/login
2. سجل دخول بحساب الإدارة: admin@basmahapp.com / password
3. جرب تحديث أي درس - يجب أن يعمل بدون أخطاء
4. سجل خروج وادخل بحساب طالب: student1@basmahapp.com / password
5. جرب تسجيل الحضور

## ملاحظات مهمة
- تأكد من تشغيل خادم MySQL قبل تطبيق الحلول
- اعمل نسخة احتياطية من قاعدة البيانات قبل تطبيق أي تغييرات
- إذا كانت لديك بيانات مهمة، استخدم الحل 1 أو 2 وليس الحل 3
