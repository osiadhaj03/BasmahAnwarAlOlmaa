-- إنشاء دروس يوم الأربعاء مع درس الساعة 4 العصر
-- BasmahApp - Wednesday Lessons Creation

-- التأكد من وجود الأعمدة المطلوبة
ALTER TABLE lessons ADD COLUMN IF NOT EXISTS name VARCHAR(255) AFTER id;
ALTER TABLE lessons ADD COLUMN IF NOT EXISTS description TEXT NULL AFTER end_time;
ALTER TABLE lessons ADD COLUMN IF NOT EXISTS schedule_time TIME NULL AFTER description;

-- حذف دروس الأربعاء الموجودة لتجنب التكرار
DELETE FROM lesson_student WHERE lesson_id IN (SELECT id FROM lessons WHERE day_of_week = 'wednesday');
DELETE FROM lessons WHERE day_of_week = 'wednesday';

-- إدراج دروس يوم الأربعاء
INSERT INTO lessons (name, subject, teacher_id, day_of_week, start_time, end_time, schedule_time, description, created_at, updated_at) VALUES
('الرياضيات - الجبر الأساسي', 'الرياضيات', (SELECT id FROM users WHERE role = 'teacher' LIMIT 1), 'wednesday', '08:00:00', '09:30:00', '08:00:00', 'درس الجبر الأساسي مع التمارين العملية والحلول التفاعلية.', NOW(), NOW()),

('اللغة العربية - النحو والصرف', 'اللغة العربية', (SELECT id FROM users WHERE role = 'teacher' LIMIT 1), 'wednesday', '10:00:00', '11:30:00', '10:00:00', 'تعلم قواعد النحو والصرف في اللغة العربية مع أمثلة تطبيقية.', NOW(), NOW()),

('العلوم - الفيزياء التطبيقية', 'العلوم', (SELECT id FROM users WHERE role = 'teacher' LIMIT 1), 'wednesday', '12:30:00', '14:00:00', '12:30:00', 'استكشاف مبادئ الفيزياء من خلال التجارب العملية والمشاهدات.', NOW(), NOW()),

('التاريخ - الحضارات القديمة', 'التاريخ', (SELECT id FROM users WHERE role = 'teacher' LIMIT 1), 'wednesday', '16:00:00', '17:30:00', '16:00:00', '🎯 درس خاص لاختبار نظام تسجيل الحضور - دراسة الحضارات المصرية والبابلية القديمة.', NOW(), NOW()),

('اللغة الإنجليزية - المحادثة المتقدمة', 'اللغة الإنجليزية', (SELECT id FROM users WHERE role = 'teacher' LIMIT 1), 'wednesday', '18:00:00', '19:30:00', '18:00:00', 'تطوير مهارات المحادثة في اللغة الإنجليزية من خلال المناقشات الجماعية.', NOW(), NOW());

-- تسجيل جميع الطلاب في جميع دروس الأربعاء
INSERT INTO lesson_student (lesson_id, student_id, created_at, updated_at)
SELECT l.id, u.id, NOW(), NOW()
FROM lessons l
CROSS JOIN users u
WHERE l.day_of_week = 'wednesday' 
AND u.role = 'student';

-- عرض الدروس المُنشأة
SELECT 
    l.name as 'اسم الدرس',
    l.subject as 'المادة',
    DATE_FORMAT(l.start_time, '%H:%i') as 'البداية',
    DATE_FORMAT(l.end_time, '%H:%i') as 'النهاية',
    u.name as 'المعلم',
    (SELECT COUNT(*) FROM lesson_student ls WHERE ls.lesson_id = l.id) as 'عدد الطلاب',
    CASE 
        WHEN l.start_time = '16:00:00' THEN '🎯 درس الساعة 4 العصر!'
        ELSE ''
    END as 'ملاحظة'
FROM lessons l
LEFT JOIN users u ON l.teacher_id = u.id
WHERE l.day_of_week = 'wednesday'
ORDER BY l.start_time;

-- التحقق من نجاح العملية
SELECT 
    COUNT(*) as 'عدد دروس الأربعاء',
    (SELECT COUNT(*) FROM lesson_student ls 
     JOIN lessons l ON ls.lesson_id = l.id 
     WHERE l.day_of_week = 'wednesday') as 'إجمالي التسجيلات'
FROM lessons 
WHERE day_of_week = 'wednesday';
