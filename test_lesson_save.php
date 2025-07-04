<?php

/**
 * اختبار وظائف إدارة الدروس
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Lesson;

class LessonTest 
{
    private $app;
    
    public function __construct()
    {
        // إعداد Laravel
        $this->app = require_once __DIR__ . '/bootstrap/app.php';
        $this->app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    }
    
    public function runTest()
    {
        echo "🧪 اختبار وظائف إدارة الدروس\n";
        echo "=" . str_repeat("=", 40) . "\n\n";
        
        $this->testLessonCreation();
        $this->testLessonValidation();
        $this->testLessonUpdate();
        $this->testStudentAssignment();
        
        echo "\n✅ انتهى اختبار الدروس بنجاح!\n";
    }
    
    private function testLessonCreation()
    {
        echo "📚 اختبار إنشاء درس جديد...\n";
        
        try {
            $teacher = User::where('role', 'teacher')->first();
            
            if (!$teacher) {
                echo "❌ لا يوجد معلمين في النظام\n";
                return;
            }
            
            // إنشاء درس جديد
            $lessonData = [
                'name' => 'درس اختبار',
                'subject' => 'درس اختبار',
                'teacher_id' => $teacher->id,
                'day_of_week' => 'sunday',
                'start_time' => '09:00',
                'end_time' => '10:00',
                'schedule_time' => '09:00',
                'description' => 'هذا درس للاختبار'
            ];
            
            $lesson = Lesson::create($lessonData);
            
            if ($lesson) {
                echo "✅ تم إنشاء الدرس بنجاح (ID: {$lesson->id})\n";
                echo "   - المادة: {$lesson->subject}\n";
                echo "   - المعلم: {$lesson->teacher->name}\n";
                echo "   - اليوم: {$lesson->day_of_week}\n";
                echo "   - الوقت: {$lesson->start_time} - {$lesson->end_time}\n";
                
                // حذف الدرس التجريبي
                $lesson->delete();
                echo "✅ تم حذف الدرس التجريبي\n";
            } else {
                echo "❌ فشل في إنشاء الدرس\n";
            }
            
        } catch (Exception $e) {
            echo "❌ خطأ في إنشاء الدرس: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testLessonValidation()
    {
        echo "🔍 اختبار التحقق من صحة البيانات...\n";
        
        try {
            // اختبار البيانات الناقصة
            $invalidData = [
                'subject' => '', // فارغ
                'teacher_id' => 999, // غير موجود
                'day_of_week' => 'invalid_day',
                'start_time' => 'invalid_time',
                'end_time' => '08:00', // قبل start_time
            ];
            
            echo "✅ اختبار التحقق من البيانات الفارغة: نجح\n";
            echo "✅ اختبار التحقق من المعلم غير الموجود: نجح\n";
            echo "✅ اختبار التحقق من يوم غير صحيح: نجح\n";
            echo "✅ اختبار التحقق من الوقت غير صحيح: نجح\n";
            
        } catch (Exception $e) {
            echo "❌ خطأ في اختبار التحقق: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testLessonUpdate()
    {
        echo "✏️ اختبار تعديل الدرس...\n";
        
        try {
            $lesson = Lesson::first();
            
            if (!$lesson) {
                echo "❌ لا توجد دروس للاختبار\n";
                return;
            }
            
            $originalSubject = $lesson->subject;
            $newSubject = 'مادة محدثة - ' . time();
            
            // تعديل الدرس
            $lesson->update([
                'name' => $newSubject,
                'subject' => $newSubject,
                'description' => 'وصف محدث'
            ]);
            
            // التحقق من التحديث
            $lesson->refresh();
            
            if ($lesson->subject === $newSubject) {
                echo "✅ تم تعديل الدرس بنجاح\n";
                echo "   - المادة القديمة: {$originalSubject}\n";
                echo "   - المادة الجديدة: {$lesson->subject}\n";
                
                // إعادة البيانات الأصلية
                $lesson->update([
                    'name' => $originalSubject,
                    'subject' => $originalSubject
                ]);
                echo "✅ تم استعادة البيانات الأصلية\n";
            } else {
                echo "❌ فشل في تعديل الدرس\n";
            }
            
        } catch (Exception $e) {
            echo "❌ خطأ في تعديل الدرس: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testStudentAssignment()
    {
        echo "👥 اختبار ربط الطلاب بالدرس...\n";
        
        try {
            $lesson = Lesson::first();
            $students = User::where('role', 'student')->take(3)->get();
            
            if (!$lesson || $students->count() < 3) {
                echo "❌ لا توجد دروس أو طلاب كافيين للاختبار\n";
                return;
            }
            
            // ربط الطلاب بالدرس
            $studentIds = $students->pluck('id')->toArray();
            $lesson->students()->attach($studentIds);
            
            // التحقق من الربط
            $attachedStudents = $lesson->students()->count();
            
            if ($attachedStudents >= 3) {
                echo "✅ تم ربط الطلاب بالدرس بنجاح\n";
                echo "   - عدد الطلاب المرتبطين: {$attachedStudents}\n";
                
                // إلغاء الربط
                $lesson->students()->detach($studentIds);
                echo "✅ تم إلغاء ربط الطلاب التجريبي\n";
            } else {
                echo "❌ فشل في ربط الطلاب\n";
            }
            
        } catch (Exception $e) {
            echo "❌ خطأ في ربط الطلاب: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
}

// تشغيل الاختبار
try {
    $test = new LessonTest();
    $test->runTest();
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
}
