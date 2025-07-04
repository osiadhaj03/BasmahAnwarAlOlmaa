<?php

echo "🚀 إنشاء مجموعة كبيرة من الدروس (375+ درس)...\n";
echo "=====================================\n\n";

// تحميل Laravel
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// إنشاء تطبيق Laravel
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

try {
    echo "🔍 التحقق من حالة قاعدة البيانات...\n";
    
    // إحصائيات قبل الإنشاء
    $existingLessons = Lesson::count();
    $existingTeachers = User::where('role', 'teacher')->count();
    $existingStudents = User::where('role', 'student')->count();
    
    echo "📊 البيانات الحالية:\n";
    echo "   - الدروس: $existingLessons\n";
    echo "   - المعلمين: $existingTeachers\n";
    echo "   - الطلاب: $existingStudents\n\n";
    
    // إنشاء معلمين إضافيين إذا لزم الأمر
    $teachers = [
        ['name' => 'أ. محمد أحمد الصالح', 'email' => 'mohammed.ahmed@basmahapp.com'],
        ['name' => 'أ. فاطمة علي النور', 'email' => 'fatima.ali@basmahapp.com'],
        ['name' => 'أ. عبدالله حسن المبارك', 'email' => 'abdullah.hassan@basmahapp.com'],
        ['name' => 'أ. زينب محمد الزهراء', 'email' => 'zeinab.mohammed@basmahapp.com'],
        ['name' => 'أ. أحمد عبدالرحمن الكريم', 'email' => 'ahmed.rahman@basmahapp.com'],
        ['name' => 'أ. مريم خالد الجميلة', 'email' => 'mariam.khalid@basmahapp.com'],
        ['name' => 'أ. عمر سالم الطيب', 'email' => 'omar.salem@basmahapp.com'],
        ['name' => 'أ. نورا إبراهيم السعيدة', 'email' => 'nora.ibrahim@basmahapp.com'],
        ['name' => 'أ. يوسف عثمان الحكيم', 'email' => 'youssef.othman@basmahapp.com'],
        ['name' => 'أ. هالة سعد الرشيدة', 'email' => 'hala.saad@basmahapp.com'],
        ['name' => 'أ. طارق فؤاد العالم', 'email' => 'tarek.fouad@basmahapp.com'],
        ['name' => 'أ. ليلى حامد الفاضلة', 'email' => 'laila.hamed@basmahapp.com'],
    ];

    echo "👨‍🏫 إنشاء المعلمين...\n";
    $teacherIds = [];
    foreach ($teachers as $teacher) {
        $user = User::firstOrCreate(
            ['email' => $teacher['email']],
            [
                'name' => $teacher['name'],
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]
        );
        $teacherIds[] = $user->id;
        echo "   ✓ " . $teacher['name'] . "\n";
    }

    // إنشاء طلاب إضافيين (75 طالب)
    echo "\n👨‍🎓 إنشاء الطلاب...\n";
    $studentIds = [];
    for ($i = 1; $i <= 75; $i++) {
        $user = User::firstOrCreate(
            ['email' => "student$i@basmahapp.com"],
            [
                'name' => "طالب رقم $i",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );
        $studentIds[] = $user->id;
        if ($i % 10 == 0) {
            echo "   ✓ تم إنشاء $i طالب\n";
        }
    }

    // الحصول على جميع المعلمين والطلاب
    $allTeacherIds = User::where('role', 'teacher')->pluck('id')->toArray();
    $allStudentIds = User::where('role', 'student')->pluck('id')->toArray();

    echo "\n📚 إنشاء الدروس الشاملة...\n";
    
    // مواد دراسية متنوعة وشاملة
    $subjects = [
        'الرياضيات', 'الجبر', 'الهندسة', 'التفاضل والتكامل', 'الإحصاء',
        'الفيزياء', 'الكيمياء', 'الأحياء', 'علوم الأرض', 'الفلك',
        'اللغة العربية', 'الأدب العربي', 'النحو والصرف', 'البلاغة', 'الشعر',
        'اللغة الإنجليزية', 'الأدب الإنجليزي', 'القواعد الإنجليزية', 'المحادثة', 'الكتابة',
        'التاريخ الإسلامي', 'التاريخ العربي', 'التاريخ العالمي', 'الحضارات القديمة',
        'الجغرافيا', 'الخرائط', 'المناخ', 'الجيولوجيا',
        'التربية الإسلامية', 'القرآن الكريم', 'الحديث الشريف', 'الفقه', 'السيرة',
        'الحاسوب', 'البرمجة', 'شبكات الحاسوب', 'أمن المعلومات', 'الذكاء الاصطناعي',
        'التربية الفنية', 'الرسم', 'النحت', 'التصميم', 'الخط العربي',
        'التربية الرياضية', 'كرة القدم', 'كرة السلة', 'السباحة', 'الجمباز',
        'الموسيقى', 'العود', 'البيانو', 'الكمان', 'الإيقاع',
        'الفلسفة', 'علم النفس', 'علم الاجتماع', 'الاقتصاد', 'إدارة الأعمال'
    ];

    // أيام الأسبوع
    $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    $dayNames = [
        'sunday' => 'الأحد',
        'monday' => 'الاثنين', 
        'tuesday' => 'الثلاثاء',
        'wednesday' => 'الأربعاء',
        'thursday' => 'الخميس',
        'friday' => 'الجمعة'
    ];

    // أوقات الحصص (8 فترات يومية)
    $timeslots = [
        ['start' => '07:30', 'end' => '08:15', 'name' => 'الحصة الأولى'],
        ['start' => '08:30', 'end' => '09:15', 'name' => 'الحصة الثانية'],
        ['start' => '09:30', 'end' => '10:15', 'name' => 'الحصة الثالثة'],
        ['start' => '10:45', 'end' => '11:30', 'name' => 'الحصة الرابعة'],
        ['start' => '11:45', 'end' => '12:30', 'name' => 'الحصة الخامسة'],
        ['start' => '13:30', 'end' => '14:15', 'name' => 'الحصة السادسة'],
        ['start' => '14:30', 'end' => '15:15', 'name' => 'الحصة السابعة'],
        ['start' => '15:30', 'end' => '16:15', 'name' => 'الحصة الثامنة'],
    ];

    $lessonCount = 0;
    $targetLessons = 400; // هدف أعلى للتأكد من تحقيق 375+

    // إنشاء دروس متنوعة
    foreach ($days as $dayIndex => $day) {
        echo "📅 إنشاء دروس يوم " . $dayNames[$day] . "...\n";
        
        foreach ($timeslots as $timeslotIndex => $timeslot) {
            foreach ($subjects as $subjectIndex => $subject) {
                if ($lessonCount >= $targetLessons) break 3;
                
                // اختيار معلم عشوائي
                $teacherId = $allTeacherIds[array_rand($allTeacherIds)];
                
                // إنشاء اسم الدرس المتنوع
                $lessonTypes = ['أساسي', 'متقدم', 'تطبيقي', 'نظري', 'عملي'];
                $lessonType = $lessonTypes[array_rand($lessonTypes)];
                
                $levels = ['المبتدئ', 'المتوسط', 'المتقدم', 'العالي'];
                $level = $levels[array_rand($levels)];
                
                $lessonName = "$subject ($lessonType) - مستوى $level - " . $timeslot['name'];
                
                // وصف متنوع للدرس
                $descriptions = [
                    "درس شامل في $subject يغطي الأساسيات والتطبيقات العملية",
                    "محاضرة تفاعلية في $subject مع أمثلة عملية ومناقشات",
                    "جلسة تدريبية متقدمة في $subject تتضمن تمارين وحلول",
                    "ورشة عمل في $subject تركز على التطبيق العملي والمشاريع",
                    "درس تفصيلي في $subject مع استخدام التقنيات الحديثة"
                ];
                
                $description = $descriptions[array_rand($descriptions)];
                
                // إنشاء الدرس
                $lesson = Lesson::create([
                    'name' => $lessonName,
                    'subject' => $subject,
                    'teacher_id' => $teacherId,
                    'day_of_week' => $day,
                    'start_time' => $timeslot['start'],
                    'end_time' => $timeslot['end'],
                    'schedule_time' => $timeslot['start'],
                    'description' => $description,
                ]);

                // تسجيل طلاب عشوائيين (8-20 طالب لكل درس)
                $numStudents = rand(8, 20);
                $selectedStudents = array_rand(array_flip($allStudentIds), min($numStudents, count($allStudentIds)));
                
                if (!is_array($selectedStudents)) {
                    $selectedStudents = [$selectedStudents];
                }
                
                $lesson->students()->attach($selectedStudents);
                
                $lessonCount++;
                
                if ($lessonCount % 25 == 0) {
                    echo "   ✓ تم إنشاء $lessonCount درس\n";
                }
            }
        }
    }

    // إحصائيات نهائية
    echo "\n🎉 تم الانتهاء من إنشاء البيانات!\n";
    echo "===============================\n";
    
    $finalStats = [
        'lessons' => Lesson::count(),
        'teachers' => User::where('role', 'teacher')->count(),
        'students' => User::where('role', 'student')->count(),
        'enrollments' => \DB::table('lesson_student')->count(),
        'subjects' => Lesson::distinct('subject')->count(),
    ];
    
    echo "📊 الإحصائيات النهائية:\n";
    echo "   📚 إجمالي الدروس: " . $finalStats['lessons'] . "\n";
    echo "   👨‍🏫 المعلمين: " . $finalStats['teachers'] . "\n";
    echo "   👨‍🎓 الطلاب: " . $finalStats['students'] . "\n";
    echo "   📝 التسجيلات: " . $finalStats['enrollments'] . "\n";
    echo "   🎯 المواد: " . $finalStats['subjects'] . "\n";
    
    // تفصيل الدروس حسب الأيام
    echo "\n📅 توزيع الدروس حسب الأيام:\n";
    foreach ($days as $day) {
        $dayCount = Lesson::where('day_of_week', $day)->count();
        echo "   " . $dayNames[$day] . ": $dayCount درس\n";
    }
    
    // أهم المواد
    echo "\n🏆 أهم المواد (أعلى 10):\n";
    $topSubjects = Lesson::select('subject', \DB::raw('count(*) as total'))
        ->groupBy('subject')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get();
    
    foreach ($topSubjects as $subject) {
        echo "   " . $subject->subject . ": " . $subject->total . " درس\n";
    }
    
    // تحقق من الهدف
    echo "\n🎯 تقييم الهدف:\n";
    if ($finalStats['lessons'] >= 375) {
        echo "   ✅ تم تحقيق الهدف بنجاح: " . $finalStats['lessons'] . " درس (المطلوب 375+)\n";
        echo "   🎊 زائد " . ($finalStats['lessons'] - 375) . " درس إضافي!\n";
    } else {
        echo "   ⚠️ لم يتم تحقيق الهدف كاملاً: " . $finalStats['lessons'] . " درس\n";
    }
    
    echo "\n🔑 بيانات تسجيل الدخول:\n";
    echo "   المدير: admin@basmahapp.com / password\n";
    echo "   المعلمين: teacher1@basmahapp.com, mohammed.ahmed@basmahapp.com, ... / password\n";
    echo "   الطلاب: student1@basmahapp.com - student75@basmahapp.com / password\n";
    
    echo "\n🌐 الروابط:\n";
    echo "   لوحة الإدارة: http://localhost/admin\n";
    echo "   لوحة الطلاب: http://localhost/student/dashboard\n";
    echo "   ماسح QR: http://localhost/qr-scanner\n";
    
    echo "\n✨ النظام جاهز للاستخدام!\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "التفاصيل: " . $e->getTraceAsString() . "\n";
}
