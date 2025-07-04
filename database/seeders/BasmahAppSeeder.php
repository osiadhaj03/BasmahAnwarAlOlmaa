<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Attendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BasmahAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@basmahapp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Teachers
        $teacher1 = User::create([
            'name' => 'أحمد محمد',
            'email' => 'teacher1@basmahapp.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        $teacher2 = User::create([
            'name' => 'فاطمة علي',
            'email' => 'teacher2@basmahapp.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // Create Students
        $students = [];
        for ($i = 1; $i <= 10; $i++) {
            $students[] = User::create([
                'name' => 'طالب ' . $i,
                'email' => "student{$i}@basmahapp.com",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
        }        // Create Lessons
        $mathLesson = Lesson::create([
            'name' => 'الرياضيات',
            'subject' => 'الرياضيات',
            'teacher_id' => $teacher1->id,
            'day_of_week' => 'sunday',
            'start_time' => '08:00',
            'end_time' => '09:30',
            'schedule_time' => '08:00',
            'description' => 'درس الرياضيات الأساسية للصف الأول، يشمل العمليات الحسابية الأساسية والهندسة البسيطة.',
        ]);

        $scienceLesson = Lesson::create([
            'name' => 'العلوم - الطبيعة والحياة',
            'subject' => 'العلوم',
            'teacher_id' => $teacher1->id,
            'day_of_week' => 'monday',
            'start_time' => '10:00',
            'end_time' => '11:30',
            'schedule_time' => '10:00',
            'description' => 'استكشاف عجائب الطبيعة وأساسيات العلوم الطبيعية للأطفال.',
        ]);

        $arabicLesson = Lesson::create([
            'name' => 'اللغة العربية - القراءة والكتابة',
            'subject' => 'اللغة العربية',
            'teacher_id' => $teacher2->id,
            'day_of_week' => 'tuesday',
            'start_time' => '08:00',
            'end_time' => '09:30',
            'schedule_time' => '08:00',
            'description' => 'تعلم أساسيات القراءة والكتابة في اللغة العربية وقواعد النحو البسيطة.',
        ]);        $englishLesson = Lesson::create([
            'name' => 'اللغة الإنجليزية - المحادثة',
            'subject' => 'اللغة الإنجليزية',
            'teacher_id' => $teacher2->id,
            'day_of_week' => 'wednesday',
            'start_time' => '09:00',
            'end_time' => '10:30',
            'schedule_time' => '09:00',
            'description' => 'تطوير مهارات المحادثة والاستماع في اللغة الإنجليزية من خلال الأنشطة التفاعلية.',
        ]);

        // Create a lesson for today (Tuesday) to test check-in functionality
        $currentTime = now();
        $startTime = $currentTime->addMinutes(5)->format('H:i'); // Start in 5 minutes
        $endTime = $currentTime->addHour()->format('H:i'); // End in 1 hour and 5 minutes
        
        $todayLesson = Lesson::create([
            'name' => 'التاريخ - الحضارات القديمة',
            'subject' => 'التاريخ',
            'teacher_id' => $teacher1->id,
            'day_of_week' => 'tuesday', // Today is Tuesday
            'start_time' => $startTime,
            'end_time' => $endTime,
            'schedule_time' => $startTime,
            'description' => 'درس تجريبي لاختبار نظام تسجيل الحضور.',
        ]);        // Enroll students in lessons
        foreach ($students as $student) {
            // Enroll each student in all lessons
            $mathLesson->students()->attach($student->id);
            $scienceLesson->students()->attach($student->id);
            $arabicLesson->students()->attach($student->id);
            $englishLesson->students()->attach($student->id);
            $todayLesson->students()->attach($student->id); // Enroll in today's lesson
        }        // Create sample attendance records for the last week
        $lessons = [$mathLesson, $scienceLesson, $arabicLesson, $englishLesson, $todayLesson];
        $statuses = ['present', 'absent', 'late', 'excused'];

        foreach ($lessons as $lesson) {
            foreach ($students as $student) {
                for ($day = 1; $day <= 7; $day++) { // Skip today (day 0) for testing
                    $date = now()->subDays($day)->format('Y-m-d');
                    
                    Attendance::create([
                        'student_id' => $student->id,
                        'lesson_id' => $lesson->id,
                        'date' => $date,
                        'status' => $statuses[array_rand($statuses)],
                    ]);
                }
            }
        }
    }
}
