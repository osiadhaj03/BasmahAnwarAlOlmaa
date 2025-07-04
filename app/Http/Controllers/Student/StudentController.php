<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // التأكد من أن المستخدم طالب
        if ($user->role !== 'student') {
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }
        
        // جلب الدروس التي سجل فيها الطالب
        $lessons = $user->lessons()->with(['teacher', 'attendances' => function($query) use ($user) {
            $query->where('student_id', $user->id);
        }])->get();
          // جلب الدروس التي لديها جلسات اليوم
        $today = Carbon::today();
        $currentDayOfWeek = strtolower($today->format('l')); // monday, tuesday, etc.
          $todayLessons = $lessons->filter(function($lesson) use ($currentDayOfWeek) {
            // تحقق من مطابقة day_of_week للدرس
            return strtolower($lesson->day_of_week) === $currentDayOfWeek;
        });
        
        // احصائيات الطالب
        $totalLessons = $lessons->count();
        $attendanceRecords = Attendance::where('student_id', $user->id)->get();
        $totalAttendances = $attendanceRecords->count();
        $presentCount = $attendanceRecords->where('status', 'present')->count();
        $lateCount = $attendanceRecords->where('status', 'late')->count();
        $absentCount = $attendanceRecords->where('status', 'absent')->count();
        $attendanceRate = $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 1) : 0;
        
        // آخر سجلات الحضور
        $recentAttendances = Attendance::where('student_id', $user->id)
            ->with(['lesson.teacher'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
          return view('student.dashboard', compact(
            'lessons',
            'todayLessons',
            'totalLessons',
            'totalAttendances',
            'presentCount',
            'lateCount',
            'absentCount',
            'attendanceRate',
            'recentAttendances',
            'currentDayOfWeek',
            'today'
        ));
    }
      public function checkIn(Request $request)
    {
        $user = auth()->user();
        
        // التأكد من أن المستخدم طالب
        if ($user->role !== 'student') {
            abort(403, 'غير مسموح لك بالوصول لهذه الصفحة');
        }
        
        $lessonId = $request->get('lesson');
        $studentId = $request->get('student');
        
        // التحقق من صحة البيانات
        if (!$lessonId || !$studentId || $studentId != $user->id) {
            return redirect()->route('student.dashboard')
                ->with('error', 'بيانات غير صحيحة');
        }
        
        $lesson = Lesson::findOrFail($lessonId);
        
        // التحقق من أن الطالب مسجل في هذا الدرس
        if (!$user->lessons()->where('lesson_id', $lessonId)->exists()) {
            return redirect()->route('student.dashboard')
                ->with('error', 'غير مسموح لك بتسجيل الحضور في هذا الدرس - لست مسجلاً فيه');
        }
        
        $today = Carbon::today();
        $currentDayOfWeek = strtolower($today->format('l')); // monday, tuesday, etc.
        
        // التحقق من أن اليوم يطابق day_of_week للدرس
        if ($lesson->day_of_week && strtolower($lesson->day_of_week) !== $currentDayOfWeek) {
            return redirect()->route('student.dashboard')
                ->with('error', 'لا يمكن تسجيل الحضور اليوم - هذا الدرس مجدول يوم ' . $lesson->day_of_week);
        }
        
        // التحقق من عدم وجود سجل حضور مسبق لنفس اليوم
        $existingAttendance = Attendance::where([
            'lesson_id' => $lessonId,
            'student_id' => $studentId,
            'date' => $today,
        ])->first();
        
        if ($existingAttendance) {
            return redirect()->route('student.dashboard')
                ->with('info', 'تم تسجيل حضورك مسبقاً لهذا الدرس اليوم');
        }
        
        $currentTime = Carbon::now();
        $status = 'present';
        $notes = null;
        
        // تحديد حالة الحضور بناءً على الوقت
        if ($lesson->start_time && $lesson->end_time) {
            $startTime = Carbon::createFromFormat('H:i:s', $lesson->start_time);
            $endTime = Carbon::createFromFormat('H:i:s', $lesson->end_time);
            
            // تعديل التاريخ ليكون نفس تاريخ اليوم
            $startTime->setDate($currentTime->year, $currentTime->month, $currentTime->day);
            $endTime->setDate($currentTime->year, $currentTime->month, $currentTime->day);
            
            // إذا كان الوقت الحالي بعد انتهاء الدرس
            if ($currentTime->gt($endTime)) {
                return redirect()->route('student.dashboard')
                    ->with('error', 'لا يمكن تسجيل الحضور - انتهى وقت الدرس');
            }
            
            // إذا كان الوقت الحالي قبل بداية الدرس
            if ($currentTime->lt($startTime)) {
                return redirect()->route('student.dashboard')
                    ->with('error', 'لا يمكن تسجيل الحضور - لم يبدأ الدرس بعد');
            }
            
            // حساب عدد الدقائق من بداية الدرس
            $minutesFromStart = $currentTime->diffInMinutes($startTime);
            
            if ($minutesFromStart <= 15) {
                // خلال أول 15 دقيقة - حضور عادي
                $status = 'present';
                $notes = 'تم تسجيل الحضور في الوقت المحدد';
            } else {
                // بعد 15 دقيقة - متأخر
                $status = 'late';
                $notes = 'متأخر ' . $minutesFromStart . ' دقيقة';
            }
        }
        
        // تسجيل الحضور
        Attendance::create([
            'lesson_id' => $lessonId,
            'student_id' => $studentId,
            'date' => $today,
            'status' => $status,
            'notes' => $notes,
        ]);
        
        $message = $status === 'present' 
            ? 'تم تسجيل حضورك بنجاح!' 
            : 'تم تسجيل حضورك كمتأخر (' . $notes . ')';
            
        return redirect()->route('student.dashboard')
            ->with('success', $message);
    }
}
