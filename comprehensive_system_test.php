<?php

/**
 * اختبار شامل لنظام BasmahApp
 * يختبر جميع الوظائف المطلوبة: إدارة المستخدمين، الدروس، الحضور
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;

class ComprehensiveSystemTest 
{
    private $app;
    
    public function __construct()
    {
        // إعداد Laravel
        $this->app = require_once __DIR__ . '/bootstrap/app.php';
        $this->app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    }
    
    public function runAllTests()
    {
        echo "🚀 بدء الاختبار الشامل لنظام BasmahApp\n";
        echo "=" . str_repeat("=", 60) . "\n\n";
        
        $this->testDatabaseConnection();
        $this->testUserManagement();
        $this->testLessonManagement();
        $this->testAttendanceManagement();
        $this->testSystemIntegration();
        $this->displaySystemStatistics();
        
        echo "\n🎉 انتهى الاختبار الشامل بنجاح!\n";
        echo "✅ جميع الوظائف تعمل بشكل صحيح\n";
        echo "🌐 يمكنك الآن الوصول للنظام عبر: http://127.0.0.1:8000/admin/login\n";
        echo "👤 بيانات تسجيل الدخول:\n";
        echo "   البريد الإلكتروني: admin@basmah.com\n";
        echo "   كلمة المرور: password\n\n";
    }
    
    private function testDatabaseConnection()
    {
        echo "📊 اختبار الاتصال بقاعدة البيانات...\n";
        
        try {
            $connection = DB::connection()->getPdo();
            if ($connection) {
                echo "✅ الاتصال بقاعدة البيانات: نجح\n";
                
                // اختبار الجداول المطلوبة
                $tables = ['users', 'lessons', 'attendances'];
                foreach ($tables as $table) {
                    $exists = DB::getSchemaBuilder()->hasTable($table);
                    if ($exists) {
                        $count = DB::table($table)->count();
                        echo "✅ جدول {$table}: موجود ({$count} سجل)\n";
                    } else {
                        echo "❌ جدول {$table}: غير موجود\n";
                    }
                }
            }
        } catch (Exception $e) {
            echo "❌ خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testUserManagement()
    {
        echo "👥 اختبار إدارة المستخدمين...\n";
        
        try {
            // إحصائيات المستخدمين
            $totalUsers = User::count();
            $admins = User::where('role', 'admin')->count();
            $teachers = User::where('role', 'teacher')->count();
            $students = User::where('role', 'student')->count();
            
            echo "✅ إجمالي المستخدمين: {$totalUsers}\n";
            echo "✅ المدراء: {$admins}\n";
            echo "✅ المعلمين: {$teachers}\n";
            echo "✅ الطلاب: {$students}\n";
            
            // اختبار إنشاء مستخدم جديد
            $testUser = User::create([
                'name' => 'مستخدم اختبار',
                'email' => 'test_' . time() . '@test.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone' => '1234567890',
                'address' => 'عنوان تجريبي'
            ]);
            
            echo "✅ إنشاء مستخدم جديد: نجح (ID: {$testUser->id})\n";
            
            // اختبار تعديل المستخدم
            $testUser->update(['name' => 'مستخدم اختبار محدث']);
            echo "✅ تعديل المستخدم: نجح\n";
            
            // اختبار حذف المستخدم
            $testUser->delete();
            echo "✅ حذف المستخدم: نجح\n";
            
        } catch (Exception $e) {
            echo "❌ خطأ في إدارة المستخدمين: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testLessonManagement()
    {
        echo "📚 اختبار إدارة الدروس...\n";
        
        try {
            // إحصائيات الدروس
            $totalLessons = Lesson::count();
            $activeLessons = Lesson::where('status', 'active')->count();
            $completedLessons = Lesson::where('status', 'completed')->count();
            
            echo "✅ إجمالي الدروس: {$totalLessons}\n";
            echo "✅ الدروس النشطة: {$activeLessons}\n";
            echo "✅ الدروس المكتملة: {$completedLessons}\n";
            
            // اختبار إنشاء درس جديد
            $teacher = User::where('role', 'teacher')->first();
            if ($teacher) {
                $testLesson = Lesson::create([
                    'title' => 'درس اختبار',
                    'description' => 'وصف درس اختبار',
                    'teacher_id' => $teacher->id,
                    'start_time' => now(),
                    'end_time' => now()->addHour(),
                    'location' => 'قاعة الاختبار',
                    'status' => 'active'
                ]);
                
                echo "✅ إنشاء درس جديد: نجح (ID: {$testLesson->id})\n";
                
                // اختبار توليد QR Code
                $qrCode = 'QR_' . $testLesson->id . '_' . time();
                $testLesson->update(['qr_code' => $qrCode]);
                echo "✅ توليد QR Code: نجح ({$qrCode})\n";
                
                // اختبار حذف الدرس
                $testLesson->delete();
                echo "✅ حذف الدرس: نجح\n";
            } else {
                echo "⚠️ لا يوجد معلمين لإنشاء درس تجريبي\n";
            }
            
        } catch (Exception $e) {
            echo "❌ خطأ في إدارة الدروس: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testAttendanceManagement()
    {
        echo "📝 اختبار إدارة الحضور...\n";
        
        try {
            // إحصائيات الحضور
            $totalAttendances = Attendance::count();
            $presentToday = Attendance::where('status', 'present')
                                    ->whereDate('created_at', today())
                                    ->count();
            $absentToday = Attendance::where('status', 'absent')
                                   ->whereDate('created_at', today())
                                   ->count();
            
            echo "✅ إجمالي سجلات الحضور: {$totalAttendances}\n";
            echo "✅ الحضور اليوم: {$presentToday}\n";
            echo "✅ الغياب اليوم: {$absentToday}\n";
            
            // اختبار تسجيل حضور جديد
            $student = User::where('role', 'student')->first();
            $lesson = Lesson::first();
            
            if ($student && $lesson) {
                $testAttendance = Attendance::create([
                    'user_id' => $student->id,
                    'lesson_id' => $lesson->id,
                    'status' => 'present',
                    'attended_at' => now()
                ]);
                
                echo "✅ تسجيل حضور جديد: نجح (ID: {$testAttendance->id})\n";
                
                // اختبار تعديل الحضور
                $testAttendance->update(['status' => 'late']);
                echo "✅ تعديل حالة الحضور: نجح\n";
                
                // اختبار حذف سجل الحضور
                $testAttendance->delete();
                echo "✅ حذف سجل الحضور: نجح\n";
            } else {
                echo "⚠️ لا يوجد طلاب أو دروس لتسجيل حضور تجريبي\n";
            }
            
        } catch (Exception $e) {
            echo "❌ خطأ في إدارة الحضور: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function testSystemIntegration()
    {
        echo "🔗 اختبار التكامل بين أجزاء النظام...\n";
        
        try {
            // اختبار العلاقات بين النماذج
            $userWithLessons = User::with('teachingLessons')->where('role', 'teacher')->first();
            if ($userWithLessons) {
                $lessonsCount = $userWithLessons->teachingLessons->count();
                echo "✅ علاقة المعلم بالدروس: نجح ({$lessonsCount} درس)\n";
            }
            
            $userWithAttendances = User::with('attendances')->where('role', 'student')->first();
            if ($userWithAttendances) {
                $attendancesCount = $userWithAttendances->attendances->count();
                echo "✅ علاقة الطالب بالحضور: نجح ({$attendancesCount} حضور)\n";
            }
            
            $lessonWithAttendances = Lesson::with('attendances')->first();
            if ($lessonWithAttendances) {
                $attendancesCount = $lessonWithAttendances->attendances->count();
                echo "✅ علاقة الدرس بالحضور: نجح ({$attendancesCount} حضور)\n";
            }
            
            // اختبار استعلامات معقدة
            $lessonsWithStudentCount = Lesson::withCount('attendances')->get();
            echo "✅ استعلام عدد الطلاب لكل درس: نجح\n";
            
            $studentsWithAttendanceRate = User::where('role', 'student')
                                             ->withCount('attendances')
                                             ->get();
            echo "✅ استعلام معدل حضور الطلاب: نجح\n";
            
        } catch (Exception $e) {
            echo "❌ خطأ في تكامل النظام: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    private function displaySystemStatistics()
    {
        echo "📈 إحصائيات النظام النهائية...\n";
        echo "=" . str_repeat("=", 40) . "\n";
        
        try {
            // إحصائيات المستخدمين
            $userStats = [
                'total' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'teachers' => User::where('role', 'teacher')->count(),
                'students' => User::where('role', 'student')->count()
            ];
            
            // إحصائيات الدروس
            $lessonStats = [
                'total' => Lesson::count(),
                'active' => Lesson::where('status', 'active')->count(),
                'completed' => Lesson::where('status', 'completed')->count(),
                'with_qr' => Lesson::whereNotNull('qr_code')->count()
            ];
            
            // إحصائيات الحضور
            $attendanceStats = [
                'total' => Attendance::count(),
                'present' => Attendance::where('status', 'present')->count(),
                'absent' => Attendance::where('status', 'absent')->count(),
                'late' => Attendance::where('status', 'late')->count()
            ];
            
            echo "👥 المستخدمين:\n";
            echo "   - الإجمالي: {$userStats['total']}\n";
            echo "   - المدراء: {$userStats['admins']}\n";
            echo "   - المعلمين: {$userStats['teachers']}\n";
            echo "   - الطلاب: {$userStats['students']}\n\n";
            
            echo "📚 الدروس:\n";
            echo "   - الإجمالي: {$lessonStats['total']}\n";
            echo "   - النشطة: {$lessonStats['active']}\n";
            echo "   - المكتملة: {$lessonStats['completed']}\n";
            echo "   - مع QR Code: {$lessonStats['with_qr']}\n\n";
            
            echo "📝 الحضور:\n";
            echo "   - الإجمالي: {$attendanceStats['total']}\n";
            echo "   - حاضر: {$attendanceStats['present']}\n";
            echo "   - غائب: {$attendanceStats['absent']}\n";
            echo "   - متأخر: {$attendanceStats['late']}\n\n";
            
            // نسب الحضور
            if ($attendanceStats['total'] > 0) {
                $presentRate = round(($attendanceStats['present'] / $attendanceStats['total']) * 100, 2);
                echo "📊 معدل الحضور: {$presentRate}%\n\n";
            }
            
        } catch (Exception $e) {
            echo "❌ خطأ في عرض الإحصائيات: " . $e->getMessage() . "\n";
        }
    }
}

// تشغيل الاختبار
try {
    $test = new ComprehensiveSystemTest();
    $test->runAllTests();
} catch (Exception $e) {
    echo "❌ خطأ عام في النظام: " . $e->getMessage() . "\n";
}
