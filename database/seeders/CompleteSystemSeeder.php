<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CompleteSystemSeeder extends Seeder
{
    /**
     * تشغيل البذور.
     */
    public function run(): void
    {
        $this->command->info('بدء إنشاء بيانات النظام الكاملة...');

        // ======================================
        // إنشاء المستخدمين (المدراء والمعلمين والطلاب)
        // ======================================
        $this->command->info('إنشاء المستخدمين...');

        // إنشاء المدراء
        $admin1 = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@basmahapp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $admin2 = User::create([
            'name' => 'مدير المدرسة',
            'email' => 'principal@basmahapp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // إنشاء المعلمين
        $teachers = [];
        $teacherSubjects = [
            'الرياضيات', 'العلوم', 'اللغة العربية', 'اللغة الإنجليزية',
            'التاريخ', 'الجغرافيا', 'الفيزياء', 'الكيمياء', 'الأحياء', 'الحاسوب'
        ];

        for ($i = 0; $i < 10; $i++) {
            $teacherNumber = $i + 1;
            $teacher = User::create([
                'name' => 'معلم ' . $teacherNumber . ' - ' . $teacherSubjects[$i],
                'email' => 'teacher' . $teacherNumber . '@basmahapp.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]);
            $teachers[] = $teacher;
            $this->command->info("  ✓ تم إنشاء المعلم: {$teacher->name}");
        }

        // إنشاء الطلاب
        $students = [];
        for ($i = 0; $i < 50; $i++) {
            $studentNumber = $i + 1;
            $student = User::create([
                'name' => 'طالب ' . $studentNumber,
                'email' => 'student' . $studentNumber . '@basmahapp.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
            $students[] = $student;
        }
        $this->command->info("  ✓ تم إنشاء 50 طالب");

        // ======================================
        // إنشاء الدروس
        // ======================================
        $this->command->info('إنشاء الدروس...');

        $daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        $timePeriods = [
            ['08:00', '09:30'],
            ['09:45', '11:15'],
            ['11:30', '13:00'],
            ['13:30', '15:00'],
            ['15:15', '16:45'],
            ['17:00', '18:30'],
        ];

        $lessons = [];
        $lessonCount = 0;

        // إنشاء 10 مواد × 5 أيام × 6 فترات زمنية = 300 درس
        foreach ($teachers as $index => $teacher) {
            $subject = $teacherSubjects[$index];
            
            foreach ($daysOfWeek as $day) {
                foreach ($timePeriods as $periodIndex => $period) {
                    // التوزيع بناءً على المادة واليوم والفترة - ليس كل مادة لديها درس في كل فترة
                    if (($index + ord($day[0]) + $periodIndex) % 3 !== 0) {
                        $startTime = $period[0];
                        $endTime = $period[1];
                        
                        $lesson = Lesson::create([
                            'name' => $subject . ' - ' . $this->getArabicDayName($day) . ' - ' . $startTime,
                            'subject' => $subject,
                            'teacher_id' => $teacher->id,
                            'day_of_week' => $day,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'description' => 'وصف درس ' . $subject . ' في يوم ' . $this->getArabicDayName($day),
                        ]);
                        
                        $lessons[] = $lesson;
                        $lessonCount++;
                    }
                }
            }
        }

        // إضافة بعض الدروس العشوائية حتى نصل إلى 375 درس على الأقل
        while ($lessonCount < 375) {
            $teacher = $teachers[array_rand($teachers)];
            $subject = $teacherSubjects[array_rand($teacherSubjects)];
            $day = $daysOfWeek[array_rand($daysOfWeek)];
            $period = $timePeriods[array_rand($timePeriods)];
            
            $lesson = Lesson::create([
                'name' => 'إضافي: ' . $subject . ' - ' . $this->getArabicDayName($day) . ' - ' . $period[0],
                'subject' => $subject,
                'teacher_id' => $teacher->id,
                'day_of_week' => $day,
                'start_time' => $period[0],
                'end_time' => $period[1],
                'description' => 'درس إضافي في ' . $subject,
            ]);
            
            $lessons[] = $lesson;
            $lessonCount++;
        }

        $this->command->info("  ✓ تم إنشاء {$lessonCount} درس");

        // ======================================
        // ربط الطلاب بالدروس
        // ======================================
        $this->command->info('ربط الطلاب بالدروس...');
        
        $registrationCount = 0;
        
        foreach ($lessons as $lesson) {
            // تحديد عدد عشوائي من الطلاب لكل درس (بين 8 و 20)
            $numStudents = rand(8, 20);
            
            // خلط الطلاب وأخذ العدد المطلوب منهم
            $shuffledStudents = $students;
            shuffle($shuffledStudents);
            $selectedStudents = array_slice($shuffledStudents, 0, $numStudents);
            
            // ربط الطلاب المختارين بالدرس
            $studentIds = array_map(function($student) {
                return $student->id;
            }, $selectedStudents);
            
            $lesson->students()->attach($studentIds);
            $registrationCount += count($studentIds);
        }
        
        $this->command->info("  ✓ تم إنشاء {$registrationCount} تسجيل طالب في الدروس");

        // ======================================
        // إنشاء سجلات الحضور
        // ======================================
        $this->command->info('إنشاء سجلات الحضور...');
        
        $attendanceCount = 0;
        $now = Carbon::now();
        
        // إنشاء سجلات حضور للأسابيع الماضية
        for ($week = 1; $week <= 4; $week++) {
            $weekStart = $now->copy()->subWeeks($week)->startOfWeek();
            
            foreach ($lessons as $lesson) {
                // تحديد يوم الدرس في الأسبوع
                $lessonDayOfWeek = $this->getDayNumber($lesson->day_of_week);
                $lessonDate = $weekStart->copy()->addDays($lessonDayOfWeek);
                
                // الحصول على طلاب الدرس
                $lessonStudents = $lesson->students;
                
                foreach ($lessonStudents as $student) {
                    // احتمالية الحضور 80%، الغياب 20%
                    $isPresent = (rand(1, 100) <= 80);
                    
                    if ($isPresent) {
                        $status = (rand(1, 100) <= 90) ? 'present' : 'late';
                        $notes = ($status === 'present') 
                            ? 'حضر في الوقت المحدد' 
                            : 'حضر متأخراً ' . rand(5, 20) . ' دقيقة';
                    } else {
                        $status = 'absent';
                        $notes = 'غائب';
                    }
                    
                    Attendance::create([
                        'student_id' => $student->id,
                        'lesson_id' => $lesson->id,
                        'date' => $lessonDate->format('Y-m-d'),
                        'status' => $status,
                        'notes' => $notes,
                    ]);
                    
                    $attendanceCount++;
                }
            }
        }
        
        $this->command->info("  ✓ تم إنشاء {$attendanceCount} سجل حضور");

        // ======================================
        // إنشاء QR Codes للدروس
        // ======================================
        $this->command->info('إنشاء QR Codes لبعض الدروس...');
        
        $qrCodeCount = 0;
        // إنشاء QR Codes لبعض الدروس المختارة عشوائياً
        $randomLessons = $lessons;
        shuffle($randomLessons);
        $qrLessons = array_slice($randomLessons, 0, 50);
        
        foreach ($qrLessons as $lesson) {
            $qrData = [
                'lesson_id' => $lesson->id,
                'date' => now()->format('Y-m-d'),
                'time' => now()->format('H:i:s'),
                'token' => Str::random(32)
            ];
            
            $lesson->qr_code = encrypt(json_encode($qrData));
            $lesson->qr_generated_at = now();
            $lesson->save();
            
            $qrCodeCount++;
        }
        
        $this->command->info("  ✓ تم إنشاء {$qrCodeCount} رمز QR Code");

        $this->command->info('تم إنشاء بيانات النظام الكاملة بنجاح!');
        $this->command->info('────────────────────────────────────────────────────');
        $this->command->info('• المدراء: 2');
        $this->command->info('• المعلمين: 10');
        $this->command->info('• الطلاب: 50');
        $this->command->info('• الدروس: ' . $lessonCount);
        $this->command->info('• التسجيلات: ' . $registrationCount);
        $this->command->info('• سجلات الحضور: ' . $attendanceCount);
        $this->command->info('• رموز QR: ' . $qrCodeCount);
        $this->command->info('────────────────────────────────────────────────────');
        $this->command->info('معلومات تسجيل الدخول:');
        $this->command->info('• مدير: admin@basmahapp.com / password');
        $this->command->info('• معلم: teacher1@basmahapp.com / password');
        $this->command->info('• طالب: student1@basmahapp.com / password');
        $this->command->info('────────────────────────────────────────────────────');
    }

    /**
     * الحصول على اسم اليوم بالعربي
     */
    private function getArabicDayName($englishDay)
    {
        $days = [
            'sunday' => 'الأحد',
            'monday' => 'الإثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];
        
        return $days[$englishDay] ?? $englishDay;
    }

    /**
     * الحصول على رقم اليوم في الأسبوع (0 = الأحد)
     */
    private function getDayNumber($dayName)
    {
        $days = [
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
        ];
        
        return $days[$dayName] ?? 0;
    }
}
