<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{    public function index(Request $request)
    {
        $user = auth()->user();
        
        // بناء الاستعلام الأساسي
        $query = Attendance::with(['student', 'lesson.teacher']);
        
        // تطبيق فلاتر الصلاحيات
        if ($user->role === 'teacher') {
            $query->whereHas('lesson', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }
        
        // فلاتر البحث والتصفية
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('lesson', function($lq) use ($search) {
                    $lq->where('subject', 'like', "%{$search}%")
                       ->orWhere('name', 'like', "%{$search}%");
                });
            });
        }
        
        if ($request->filled('lesson_id')) {
            $query->where('lesson_id', $request->lesson_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }
        
        // ترتيب النتائج
        $attendances = $query->orderBy('date', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);
        
        // جلب الدروس للفلاتر
        if ($user->role === 'admin') {
            $lessons = Lesson::with('teacher')->orderBy('subject')->get();
        } else {
            $lessons = Lesson::where('teacher_id', $user->id)->orderBy('subject')->get();
        }
        
        // حساب الإحصائيات السريعة
        $statsQuery = clone $query;
        $totalRecords = $statsQuery->count();
        $todayRecords = $statsQuery->whereDate('date', today())->count();
        
        $stats = [
            'total' => $totalRecords,
            'today' => $todayRecords,
            'present_today' => $statsQuery->whereDate('date', today())->where('status', 'present')->count(),
            'absent_today' => $statsQuery->whereDate('date', today())->where('status', 'absent')->count(),
        ];
        
        return view('admin.attendances.index', compact('attendances', 'lessons', 'stats'));
    }    public function create()
    {
        // تم إلغاء تسجيل الحضور للمعلمين والإدارة - فقط الطلاب يسجلون الحضور عبر QR Code
        return redirect()->route(auth()->user()->role . '.attendances.index')
            ->with('error', 'تسجيل الحضور متاح للطلاب فقط عبر QR Code');
    }    public function store(Request $request)
    {
        // تم إلغاء تسجيل الحضور للمعلمين والإدارة - فقط الطلاب يسجلون الحضور عبر QR Code
        return redirect()->route(auth()->user()->role . '.attendances.index')
            ->with('error', 'تسجيل الحضور متاح للطلاب فقط عبر QR Code');
    }

    /**
     * عرض تقارير الحضور
     */
    public function reports(Request $request)
    {
        $user = auth()->user();
        
        // جلب الدروس والطلاب للفلاتر
        if ($user->role === 'admin') {
            $lessons = Lesson::with('teacher')->get();
            $students = User::where('role', 'student')->get();
        } else {
            $lessons = Lesson::where('teacher_id', $user->id)->get();
            $students = User::where('role', 'student')
                ->whereHas('lessons', function($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                })->get();
        }

        // بناء الاستعلام
        $query = Attendance::with(['student', 'lesson.teacher']);
        
        // تطبيق فلاتر المعلم
        if ($user->role === 'teacher') {
            $query->whereHas('lesson', function($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }

        // تطبيق الفلاتر المطلوبة
        if ($request->filled('lesson_id')) {
            $query->where('lesson_id', $request->lesson_id);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        // جلب النتائج
        $attendances = $query->orderBy('date', 'desc')->paginate(20);

        // حساب الإحصائيات
        $statisticsQuery = clone $query;
        $allAttendances = $statisticsQuery->get();
        
        $statistics = [
            'present' => $allAttendances->where('status', 'present')->count(),
            'absent' => $allAttendances->where('status', 'absent')->count(),
            'late' => $allAttendances->where('status', 'late')->count(),
            'excused' => $allAttendances->where('status', 'excused')->count(),
        ];

        return view('admin.attendances.reports', compact(
            'attendances', 'lessons', 'students', 'statistics'
        ));
    }

    public function show(Attendance $attendance)
    {
        $this->authorizeAttendance($attendance);
        
        $attendance->load(['student', 'lesson.teacher']);
        
        return view('admin.attendances.show', compact('attendance'));
    }    public function edit(Attendance $attendance)
    {
        $this->authorizeAttendance($attendance);
        
        $user = auth()->user();
        
        // جلب البيانات المطلوبة للتعديل
        if ($user->role === 'admin') {
            $lessons = Lesson::with('teacher')->get();
            $students = User::where('role', 'student')->get();
        } else {
            $lessons = Lesson::where('teacher_id', $user->id)->get();
            $students = User::where('role', 'student')->get();
        }
        
        return view('admin.attendances.edit', compact('attendance', 'lessons', 'students'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $user = auth()->user();
        
        // التحقق من الصلاحيات
        if ($user->role === 'teacher') {
            // التأكد من أن الحضور يخص درس هذا المعلم
            if ($attendance->lesson->teacher_id !== $user->id) {
                abort(403, 'ليس لديك صلاحية لتعديل هذا السجل');
            }
        } elseif ($user->role !== 'admin') {
            abort(403, 'ليس لديك صلاحية لتعديل سجلات الحضور');
        }

        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:255',
        ], [
            'status.required' => 'حالة الحضور مطلوبة',
            'status.in' => 'حالة الحضور غير صحيحة',
            'notes.max' => 'الملاحظات طويلة جداً (الحد الأقصى 255 حرف)',
        ]);

        $attendance->update($validated);

        $redirectRoute = $user->role === 'admin' ? 'admin.attendances.index' : 'teacher.attendances.index';
        
        return redirect()->route($redirectRoute)
            ->with('success', 'تم تحديث حالة الحضور بنجاح');
    }

    public function destroy(Attendance $attendance)
    {
        $this->authorizeAttendance($attendance);
        
        // المدير لا يمكنه حذف الحضور
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.attendances.index')
                ->with('error', 'المدير يمكنه عرض الحضور فقط، لا يمكن الحذف');
        }
        
        $attendance->delete();
        
        return redirect()->route('admin.attendances.index')
            ->with('success', 'تم حذف سجل الحضور بنجاح');
    }    public function getStudents(Lesson $lesson)
    {
        $user = auth()->user();
        
        // التحقق من الصلاحيات
        if ($user->role === 'teacher' && $lesson->teacher_id !== $user->id) {
            return response()->json(['error' => 'غير مسموح لك بالوصول لهذا الدرس'], 403);
        }
        
        $students = $lesson->students()->select('id', 'name')->get();
        
        return response()->json([
            'success' => true,
            'students' => $students
        ]);
    }

    private function authorizeLesson($lessonId)
    {
        $user = auth()->user();
        
        if ($user->role === 'teacher') {
            $lesson = Lesson::findOrFail($lessonId);
            if ($lesson->teacher_id !== $user->id) {
                abort(403, 'غير مسموح لك بالوصول لهذا الدرس');
            }
        }
    }    private function authorizeAttendance(Attendance $attendance)
    {
        $user = auth()->user();
        
        if ($user->role === 'teacher' && $attendance->lesson->teacher_id !== $user->id) {
            abort(403, 'غير مسموح لك بالوصول لهذا السجل');
        }
    }

    /**
     * عرض سجلات الحضور لدرس محدد
     */
    public function lessonAttendance(Lesson $lesson)
    {
        $user = auth()->user();
        
        // التحقق من الصلاحيات - المعلم يستطيع فقط عرض دروسه
        if ($user->role === 'teacher' && $lesson->teacher_id !== $user->id) {
            abort(403, 'غير مسموح لك بالوصول لهذا الدرس');
        }
        
        // جلب سجلات الحضور للدرس
        $attendances = Attendance::with(['student'])
            ->where('lesson_id', $lesson->id)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // إحصائيات الحضور
        $stats = [
            'total' => $attendances->total(),
            'present' => Attendance::where('lesson_id', $lesson->id)->where('status', 'present')->count(),
            'late' => Attendance::where('lesson_id', $lesson->id)->where('status', 'late')->count(),
            'absent' => Attendance::where('lesson_id', $lesson->id)->where('status', 'absent')->count(),
        ];
        
        $stats['present_rate'] = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100, 1) : 0;
        
        return view('teacher.attendances.lesson', compact('lesson', 'attendances', 'stats'));
    }
    
    public function getStudentLessons(User $student)
    {
        $user = auth()->user();
        
        // التحقق من أن المستخدم المحدد هو طالب
        if ($student->role !== 'student') {
            return response()->json(['error' => 'المستخدم المحدد ليس طالباً'], 400);
        }
        
        try {
            // جلب دروس الطالب
            $lessonsQuery = $student->lessons();
            
            // إذا كان المستخدم معلم، نعرض دروسه فقط
            if ($user->role === 'teacher') {
                $lessonsQuery->where('teacher_id', $user->id);
            }
            
            $lessons = $lessonsQuery->select('id', 'subject', 'name')->get();
            
            return response()->json([
                'success' => true,
                'lessons' => $lessons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'خطأ في تحميل بيانات الدروس: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض سجل حضور طالب معين في درس معين
     */
    public function studentAttendance(Lesson $lesson, User $student)
    {
        $user = auth()->user();
        
        // التحقق من الصلاحيات - المعلم يستطيع فقط عرض طلاب دروسه
        if ($user->role === 'teacher' && $lesson->teacher_id !== $user->id) {
            abort(403, 'غير مسموح لك بالوصول لهذا الدرس');
        }
        
        // التحقق من أن الطالب مسجل في هذا الدرس
        if (!$lesson->students()->where('student_id', $student->id)->exists()) {
            abort(404, 'الطالب غير مسجل في هذا الدرس');
        }
        
        // جلب سجلات حضور الطالب في هذا الدرس
        $attendances = Attendance::where('lesson_id', $lesson->id)
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // إحصائيات حضور الطالب
        $stats = [
            'total' => $attendances->total(),
            'present' => Attendance::where('lesson_id', $lesson->id)->where('student_id', $student->id)->where('status', 'present')->count(),
            'late' => Attendance::where('lesson_id', $lesson->id)->where('student_id', $student->id)->where('status', 'late')->count(),
            'absent' => Attendance::where('lesson_id', $lesson->id)->where('student_id', $student->id)->where('status', 'absent')->count(),
            'excused' => Attendance::where('lesson_id', $lesson->id)->where('student_id', $student->id)->where('status', 'excused')->count(),
        ];
        
        $stats['attendance_rate'] = $stats['total'] > 0 ? round((($stats['present'] + $stats['late']) / $stats['total']) * 100, 1) : 0;
        
        return view('teacher.attendances.student', compact('lesson', 'student', 'attendances', 'stats'));
    }
}
