<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherDashboardController extends Controller
{
    public function dashboard()
    {
        $teacher = auth()->user();
        
        // الحصول على دروس المعلم
        $teacherLessons = Lesson::where('teacher_id', $teacher->id)
            ->with(['students', 'attendances'])
            ->get();
        
        $lessonIds = $teacherLessons->pluck('id');
        
        // الإحصائيات الأساسية
        $stats = [
            'total_lessons' => $teacherLessons->count(),
            'total_students' => $teacherLessons->sum('students_count'),
            'today_lessons' => $this->getTodayLessons($teacherLessons),
            'this_week_attendances' => $this->getWeekAttendances($lessonIds),
        ];
        
        // إحصائيات الحضور
        $attendanceStats = $this->getAttendanceStatistics($lessonIds);
        
        // دروس اليوم
        $todayLessons = $this->getTodayLessonsDetailed($teacherLessons);
        
        // آخر سجلات الحضور
        $recentAttendances = Attendance::whereIn('lesson_id', $lessonIds)
            ->with(['student', 'lesson'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // إحصائيات أداء الطلاب
        $studentPerformance = $this->getStudentPerformance($lessonIds);
        
        // الدروس الأكثر حضوراً وغياباً
        $lessonStats = $this->getLessonStatistics($teacherLessons);
        
        return view('teacher.dashboard', compact(
            'teacher',
            'stats', 
            'attendanceStats',
            'todayLessons',
            'recentAttendances',
            'studentPerformance',
            'lessonStats'
        ));
    }
    
    private function getTodayLessons($lessons)
    {
        $today = strtolower(Carbon::today()->format('l')); // monday, tuesday, etc.
        return $lessons->filter(function($lesson) use ($today) {
            return strtolower($lesson->day_of_week) === $today;
        })->count();
    }
    
    private function getTodayLessonsDetailed($lessons)
    {
        $today = strtolower(Carbon::today()->format('l'));
        return $lessons->filter(function($lesson) use ($today) {
            return strtolower($lesson->day_of_week) === $today;
        })->sortBy('start_time');
    }
    
    private function getWeekAttendances($lessonIds)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        return Attendance::whereIn('lesson_id', $lessonIds)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->count();
    }
    
    private function getAttendanceStatistics($lessonIds)
    {
        $attendances = Attendance::whereIn('lesson_id', $lessonIds)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $total = array_sum($attendances);
        
        return [
            'present' => $attendances['present'] ?? 0,
            'absent' => $attendances['absent'] ?? 0,
            'late' => $attendances['late'] ?? 0,
            'excused' => $attendances['excused'] ?? 0,
            'total' => $total,
            'attendance_rate' => $total > 0 ? round((($attendances['present'] ?? 0) / $total) * 100, 1) : 0
        ];
    }
    
    private function getStudentPerformance($lessonIds)
    {
        return DB::table('attendances')
            ->join('users', 'attendances.student_id', '=', 'users.id')
            ->whereIn('attendances.lesson_id', $lessonIds)
            ->select('users.name as student_name', 'users.id as student_id')
            ->selectRaw('COUNT(*) as total_records')
            ->selectRaw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count')
            ->selectRaw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count')
            ->selectRaw('ROUND((SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as attendance_percentage')
            ->groupBy('users.id', 'users.name')
            ->orderBy('attendance_percentage', 'desc')
            ->limit(10)
            ->get();
    }
    
    private function getLessonStatistics($lessons)
    {
        $lessonStats = [];
        
        foreach ($lessons as $lesson) {
            $attendances = $lesson->attendances;
            $total = $attendances->count();
            $present = $attendances->where('status', 'present')->count();
            
            $lessonStats[] = [
                'lesson' => $lesson,
                'total_records' => $total,
                'present_count' => $present,
                'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0
            ];
        }
        
        return collect($lessonStats)->sortByDesc('attendance_rate')->take(5);
    }
}
