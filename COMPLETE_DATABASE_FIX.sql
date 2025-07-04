-- إصلاح شامل لقاعدة البيانات BasmahApp
-- تشغيل هذه الاستعلامات في phpMyAdmin أو أي أداة إدارة MySQL

-- التحقق من الجداول الموجودة
SHOW TABLES;

-- التحقق من بنية جدول lessons
DESCRIBE lessons;

-- إضافة الأعمدة المفقودة لجدول lessons (إن لم تكن موجودة)
ALTER TABLE lessons 
ADD COLUMN IF NOT EXISTS name VARCHAR(255) AFTER id,
ADD COLUMN IF NOT EXISTS description TEXT NULL AFTER end_time,
ADD COLUMN IF NOT EXISTS schedule_time TIME NULL AFTER description;

-- التحقق من بنية جدول attendances
DESCRIBE attendances;

-- إضافة عمود notes لجدول attendances (إن لم يكن موجوداً)
ALTER TABLE attendances 
ADD COLUMN IF NOT EXISTS notes TEXT NULL AFTER status;

-- تحديث الدروس الموجودة بأسماء افتراضية
UPDATE lessons 
SET name = CONCAT(subject, ' - الدرس') 
WHERE name IS NULL OR name = '';

-- تحديث schedule_time ليطابق start_time حيث يكون فارغاً
UPDATE lessons 
SET schedule_time = start_time 
WHERE schedule_time IS NULL;

-- التحقق من النتائج
SELECT id, name, subject, day_of_week, start_time, end_time, schedule_time, description 
FROM lessons 
LIMIT 5;

SELECT id, student_id, lesson_id, date, status, notes 
FROM attendances 
LIMIT 5;

-- إدراج درس تجريبي للاختبار (اختياري)
INSERT INTO lessons (name, subject, teacher_id, day_of_week, start_time, end_time, schedule_time, description) 
VALUES (
    'اختبار النظام - درس تجريبي', 
    'اختبار', 
    (SELECT id FROM users WHERE role = 'teacher' LIMIT 1),
    'tuesday',
    '14:00:00',
    '15:30:00', 
    '14:00:00',
    'درس تجريبي لاختبار النظام المحدث'
);

-- عرض آخر درس مُدرج
SELECT * FROM lessons ORDER BY id DESC LIMIT 1;
