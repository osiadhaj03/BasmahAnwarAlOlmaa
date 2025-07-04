<?php
/**
 * اختبار إنشاء الدروس المبسط - المادة فقط
 * BasmahApp - Teacher Lesson Simple Creation Test
 */

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lesson;
use App\Http\Controllers\Teacher\TeacherLessonController;

class SimpleLessonCreationTest
{
    private $output = [];
    private $teacher;
    
    public function __construct()
    {
        $this->output[] = "=== اختبار إنشاء الدروس المبسط ===\n";
    }

    public function run()
    {
        try {
            $this->testDatabaseConnection();
            $this->setupTestTeacher();
            $this->testSimpleLessonCreation();
            $this->testLessonDisplay();
            $this->testEditingProcess();
            $this->displayResults();
            
        } catch (Exception $e) {
            $this->output[] = "❌ خطأ في الاختبار: " . $e->getMessage();
            $this->displayResults();
        }
    }

    private function testDatabaseConnection()
    {
        $this->output[] = "📋 اختبار الاتصال بقاعدة البيانات...";
        
        try {
            $pdo = new PDO(
                'mysql:host=localhost;dbname=basmah_app;charset=utf8mb4',
                'root',
                '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $this->output[] = "✅ تم الاتصال بقاعدة البيانات بنجاح";
            return $pdo;
            
        } catch (PDOException $e) {
            throw new Exception("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }

    private function setupTestTeacher()
    {
        $this->output[] = "\n📋 إعداد معلم تجريبي...";
        
        $pdo = $this->testDatabaseConnection();
        
        // البحث عن معلم موجود أو إنشاء واحد جديد
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_type = 'teacher' LIMIT 1");
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$teacher) {
            // إنشاء معلم جديد
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, user_type, created_at, updated_at) 
                VALUES (?, ?, ?, 'teacher', NOW(), NOW())
            ");
            $stmt->execute([
                'المعلم التجريبي',
                'test.teacher@basmahapp.com',
                password_hash('password123', PASSWORD_DEFAULT)
            ]);
            
            $teacherId = $pdo->lastInsertId();
            $this->teacher = ['id' => $teacherId];
            $this->output[] = "✅ تم إنشاء معلم تجريبي جديد (ID: $teacherId)";
        } else {
            $this->teacher = $teacher;
            $this->output[] = "✅ تم العثور على معلم تجريبي (ID: {$teacher['id']})";
        }
    }

    private function testSimpleLessonCreation()
    {
        $this->output[] = "\n📋 اختبار إنشاء درس مبسط (المادة فقط)...";
        
        $pdo = $this->testDatabaseConnection();
        $teacherId = $this->teacher['id'];
        
        // تجربة إنشاء دروس مختلفة بالمواد فقط
        $subjects = [
            'الرياضيات',
            'العلوم',
            'اللغة العربية',
            'اللغة الإنجليزية',
            'مادة جديدة غير موجودة'
        ];
        
        foreach ($subjects as $subject) {
            try {
                // إنشاء الدرس
                $stmt = $pdo->prepare("
                    INSERT INTO lessons (name, subject, teacher_id, students_count, status, created_at, updated_at)
                    VALUES (?, ?, ?, 0, 'active', NOW(), NOW())
                ");
                
                $stmt->execute([$subject, $subject, $teacherId]);
                $lessonId = $pdo->lastInsertId();
                
                $this->output[] = "✅ تم إنشاء درس '$subject' (ID: $lessonId)";
                
                // التحقق من الدرس المُنشأ
                $stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
                $stmt->execute([$lessonId]);
                $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($lesson) {
                    $this->output[] = "   📝 اسم الدرس: {$lesson['name']}";
                    $this->output[] = "   📚 المادة: {$lesson['subject']}";
                    $this->output[] = "   👨‍🏫 المعلم: {$lesson['teacher_id']}";
                    $this->output[] = "   📅 اليوم: " . ($lesson['day_of_week'] ?: 'غير محدد');
                    $this->output[] = "   ⏰ الوقت: " . ($lesson['start_time'] ?: 'غير محدد');
                    $this->output[] = "   ✅ الحالة: {$lesson['status']}";
                }
                
            } catch (PDOException $e) {
                $this->output[] = "❌ فشل إنشاء درس '$subject': " . $e->getMessage();
            }
        }
    }

    private function testLessonDisplay()
    {
        $this->output[] = "\n📋 اختبار عرض الدروس...";
        
        $pdo = $this->testDatabaseConnection();
        $teacherId = $this->teacher['id'];
        
        // جلب جميع دروس المعلم
        $stmt = $pdo->prepare("
            SELECT * FROM lessons 
            WHERE teacher_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$teacherId]);
        $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->output[] = "📊 إجمالي الدروس: " . count($lessons);
        
        foreach ($lessons as $lesson) {
            $status = "🔴 غير مكتمل";
            if ($lesson['day_of_week'] && $lesson['start_time']) {
                $status = "🟢 مكتمل";
            }
            
            $this->output[] = "   📖 {$lesson['subject']} - $status";
        }
    }

    private function testEditingProcess()
    {
        $this->output[] = "\n📋 اختبار عملية التعديل...";
        
        $pdo = $this->testDatabaseConnection();
        $teacherId = $this->teacher['id'];
        
        // جلب أول درس للمعلم
        $stmt = $pdo->prepare("
            SELECT * FROM lessons 
            WHERE teacher_id = ? 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$teacherId]);
        $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($lesson) {            // إضافة معلومات إضافية للدرس
            $stmt = $pdo->prepare("
                UPDATE lessons 
                SET day_of_week = ?, start_time = ?, end_time = ?, description = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                'sunday',
                '09:00:00',
                '10:00:00',
                'درس تجريبي مكتمل',
                $lesson['id']
            ]);
            
            $this->output[] = "✅ تم تحديث الدرس '{$lesson['subject']}' بالمعلومات الإضافية";
            
            // التحقق من التحديث
            $stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
            $stmt->execute([$lesson['id']]);
            $updatedLesson = $stmt->fetch(PDO::FETCH_ASSOC);
              $this->output[] = "   📅 اليوم: {$updatedLesson['day_of_week']}";
            $this->output[] = "   ⏰ الوقت: {$updatedLesson['start_time']} - {$updatedLesson['end_time']}";
        }
    }

    private function displayResults()
    {
        echo "\n";
        echo str_repeat("=", 60) . "\n";
        echo "نتائج اختبار إنشاء الدروس المبسط\n";
        echo str_repeat("=", 60) . "\n\n";
        
        foreach ($this->output as $line) {
            echo $line . "\n";
        }
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "انتهى الاختبار - " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 60) . "\n\n";
    }
}

// تشغيل الاختبار
$test = new SimpleLessonCreationTest();
$test->run();
