<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Attendance;
use Illuminate\Console\Command;

class TestBasmahDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basmah:test-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test BasmahApp database structure and relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing BasmahApp Database Structure...');
        $this->newLine();

        // Test Users by role
        $this->info('=== USERS ===');
        $admins = User::where('role', 'admin')->count();
        $teachers = User::where('role', 'teacher')->count();
        $students = User::where('role', 'student')->count();
        
        $this->line("Admins: {$admins}");
        $this->line("Teachers: {$teachers}");
        $this->line("Students: {$students}");
        $this->newLine();

        // Test Lessons
        $this->info('=== LESSONS ===');
        $lessons = Lesson::with('teacher')->get();
        foreach ($lessons as $lesson) {
            $studentCount = $lesson->students()->count();
            $this->line("Subject: {$lesson->subject}");
            $this->line("Teacher: {$lesson->teacher->name}");
            $this->line("Day: {$lesson->day_of_week}");
            $this->line("Time: {$lesson->start_time} - {$lesson->end_time}");
            $this->line("Enrolled Students: {$studentCount}");
            $this->newLine();
        }

        // Test Attendances
        $this->info('=== ATTENDANCE SUMMARY ===');
        $totalAttendances = Attendance::count();
        $presentCount = Attendance::where('status', 'present')->count();
        $absentCount = Attendance::where('status', 'absent')->count();
        $lateCount = Attendance::where('status', 'late')->count();
        $excusedCount = Attendance::where('status', 'excused')->count();

        $this->line("Total Attendance Records: {$totalAttendances}");
        $this->line("Present: {$presentCount}");
        $this->line("Absent: {$absentCount}");
        $this->line("Late: {$lateCount}");
        $this->line("Excused: {$excusedCount}");
        $this->newLine();

        // Test Relationships
        $this->info('=== TESTING RELATIONSHIPS ===');
        
        // Get first teacher and their lessons
        $teacher = User::where('role', 'teacher')->first();
        if ($teacher) {
            $teacherLessons = $teacher->teacherLessons()->count();
            $this->line("Teacher '{$teacher->name}' has {$teacherLessons} lessons");
        }

        // Get first student and their lessons/attendances
        $student = User::where('role', 'student')->first();
        if ($student) {
            $enrolledLessons = $student->studentLessons()->count();
            $attendanceRecords = $student->attendances()->count();
            $this->line("Student '{$student->name}' is enrolled in {$enrolledLessons} lessons");
            $this->line("Student '{$student->name}' has {$attendanceRecords} attendance records");
        }

        $this->newLine();
        $this->info('Database test completed successfully! ✅');
    }
}
