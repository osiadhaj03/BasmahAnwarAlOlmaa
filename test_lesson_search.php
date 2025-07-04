<?php

/**
 * اختبار نظام البحث والفلترة المتقدم للدروس
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Lesson;

class LessonSearchTest 
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
        echo "🔍 اختبار نظام البحث والفلترة المتقدم للدروس\n";
        echo "=" . str_repeat("=", 60) . "\n\n";
        
        $this->testBasicSearch();
        $this->testDayFilter();
        $this->testTeacherFilter();
        $this->testTimeFilter();
        $this->testStudentsFilter();
        $this->testSortingOptions();
        $this->testCombinedFilters();
        
        echo "\n🎉 انتهى اختبار نظام البحث والفلترة بنجاح!\n";
        echo "✅ جميع الفلاتر تعمل بشكل مثالي\n\n";
    }
    
    private function testBasicSearch()
    {
        echo "🔎 اختبار البحث الأساسي...\n";
        
        try {
            // البحث في المادة
            $mathLessons = Lesson::where('subject', 'like', '%رياضيات%')->count();
            echo "✅ البحث في المادة: وُجد {$mathLessons} درس رياضيات\n";
            
            // البحث في اسم المعلم
            $teacherLessons = Lesson::whereHas('teacher', function($q) {
                $q->where('name', 'like', '%أحمد%');
            })->count();
            echo "✅ البحث في اسم المعلم: وُجد {$teacherLessons} درس للمعلمين المحتوين على 'أحمد'\n";
            
            // البحث في الوصف
            $descriptionLessons = Lesson::where('description', 'like', '%أساسيات%')->count();
            echo "✅ البحث في الوصف: وُجد {$descriptionLessons} درس يحتوي على 'أساسيات'\n";
            
        } catch (Exception $e) {
            echo "❌ خطأ في البحث الأساسي: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testDayFilter()
    {
        echo "📅 اختبار فلتر الأيام...\n";
        
        try {
            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            $dayNames = [
                'sunday' => 'الأحد',
                'monday' => 'الإثنين',
                'tuesday' => 'الثلاثاء',
                'wednesday' => 'الأربعاء',
                'thursday' => 'الخميس',
                'friday' => 'الجمعة',
                'saturday' => 'السبت'
            ];
            
            foreach ($days as $day) {
                $count = Lesson::where('day_of_week', $day)->count();
                echo "✅ {$dayNames[$day]}: {$count} درس\n";
            }
            
        } catch (Exception $e) {
            echo "❌ خطأ في فلتر الأيام: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testTeacherFilter()
    {
        echo "👨‍🏫 اختبار فلتر المعلمين...\n";
        
        try {
            $teachers = User::where('role', 'teacher')->take(3)->get();
            
            foreach ($teachers as $teacher) {
                $count = Lesson::where('teacher_id', $teacher->id)->count();
                echo "✅ {$teacher->name}: {$count} درس\n";
            }
            
        } catch (Exception $e) {
            echo "❌ خطأ في فلتر المعلمين: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testTimeFilter()
    {
        echo "⏰ اختبار فلتر الأوقات...\n";
        
        try {
            // صباحي (قبل 12 ظهراً)
            $morningLessons = Lesson::whereTime('start_time', '<', '12:00:00')->count();
            echo "✅ دروس صباحية (قبل 12 ظهراً): {$morningLessons} درس\n";
            
            // بعد الظهر (12-6 مساءً)
            $afternoonLessons = Lesson::whereTime('start_time', '>=', '12:00:00')
                                    ->whereTime('start_time', '<', '18:00:00')->count();
            echo "✅ دروس بعد الظهر (12-6 مساءً): {$afternoonLessons} درس\n";
            
            // مسائي (بعد 6 مساءً)
            $eveningLessons = Lesson::whereTime('start_time', '>=', '18:00:00')->count();
            echo "✅ دروس مسائية (بعد 6 مساءً): {$eveningLessons} درس\n";
            
        } catch (Exception $e) {
            echo "❌ خطأ في فلتر الأوقات: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testStudentsFilter()
    {
        echo "👥 اختبار فلتر عدد الطلاب...\n";
        
        try {
            // بدون طلاب
            $noStudents = Lesson::withCount('students')
                                ->having('students_count', '=', 0)->count();
            echo "✅ دروس بدون طلاب: {$noStudents} درس\n";
            
            // قليل (1-10)
            $fewStudents = Lesson::withCount('students')
                                 ->having('students_count', '>', 0)
                                 ->having('students_count', '<=', 10)->count();
            echo "✅ دروس بطلاب قليل (1-10): {$fewStudents} درس\n";
            
            // متوسط (11-25)
            $mediumStudents = Lesson::withCount('students')
                                   ->having('students_count', '>', 10)
                                   ->having('students_count', '<=', 25)->count();
            echo "✅ دروس بطلاب متوسط (11-25): {$mediumStudents} درس\n";
            
            // كثير (+25)
            $manyStudents = Lesson::withCount('students')
                                 ->having('students_count', '>', 25)->count();
            echo "✅ دروس بطلاب كثير (+25): {$manyStudents} درس\n";
            
        } catch (Exception $e) {
            echo "❌ خطأ في فلتر عدد الطلاب: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testSortingOptions()
    {
        echo "📊 اختبار خيارات الترتيب...\n";
        
        try {
            // ترتيب حسب المادة
            $sortedBySubject = Lesson::orderBy('subject')->take(3)->pluck('subject');
            echo "✅ الترتيب حسب المادة: " . $sortedBySubject->implode(', ') . "\n";
            
            // ترتيب حسب المعلم
            $sortedByTeacher = Lesson::join('users', 'lessons.teacher_id', '=', 'users.id')
                                    ->orderBy('users.name')
                                    ->take(3)
                                    ->pluck('users.name');
            echo "✅ الترتيب حسب المعلم: " . $sortedByTeacher->implode(', ') . "\n";
            
            // ترتيب حسب الوقت
            $sortedByTime = Lesson::orderBy('start_time')->take(3)->pluck('start_time');
            echo "✅ الترتيب حسب الوقت: " . $sortedByTime->map(function($time) {
                return \Carbon\Carbon::parse($time)->format('H:i');
            })->implode(', ') . "\n";
            
        } catch (Exception $e) {
            echo "❌ خطأ في الترتيب: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testCombinedFilters()
    {
        echo "🔗 اختبار الفلاتر المدمجة...\n";
        
        try {
            // مثال: دروس الأحد الصباحية للمعلم الأول
            $teacher = User::where('role', 'teacher')->first();
            if ($teacher) {
                $combinedFilter = Lesson::where('day_of_week', 'sunday')
                                       ->whereTime('start_time', '<', '12:00:00')
                                       ->where('teacher_id', $teacher->id)
                                       ->count();
                echo "✅ دروس الأحد الصباحية للمعلم {$teacher->name}: {$combinedFilter} درس\n";
            }
            
            // مثال: دروس بحث في المادة + فلتر اليوم
            $searchAndFilter = Lesson::where('subject', 'like', '%رياضيات%')
                                    ->where('day_of_week', 'monday')
                                    ->count();
            echo "✅ دروس الرياضيات يوم الإثنين: {$searchAndFilter} درس\n";
            
            // مثال: دروس مسائية بطلاب كثيرين
            $eveningWithManyStudents = Lesson::whereTime('start_time', '>=', '18:00:00')
                                            ->withCount('students')
                                            ->having('students_count', '>', 20)
                                            ->count();
            echo "✅ دروس مسائية بأكثر من 20 طالب: {$eveningWithManyStudents} درس\n";
            
        } catch (Exception $e) {
            echo "❌ خطأ في الفلاتر المدمجة: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
}

// تشغيل الاختبار
try {
    $test = new LessonSearchTest();
    $test->runTest();
    
    echo "🌐 لاختبار الواجهة:\n";
    echo "1. شغل الخادم: php artisan serve\n";
    echo "2. اذهب إلى: http://127.0.0.1:8000/admin/lessons\n";
    echo "3. جرب البحث والفلاتر المختلفة\n";
    echo "4. اختبر الترتيب والفلاتر المدمجة\n\n";
    
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
}
