<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherLessonController extends Controller
{
    /**
     * عرض دروس المعلم
     */
    public function index()
    {
        $teacher = auth()->user();
        
        $lessons = Lesson::where('teacher_id', $teacher->id)
            ->with(['students', 'attendances'])
            ->withCount(['students', 'attendances'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(10);

        // إحصائيات سريعة
        $stats = [
            'total_lessons' => $lessons->total(),
            'active_lessons' => $lessons->where('status', 'active')->count(),
            'total_students' => $lessons->sum('students_count'),
            'avg_attendance' => $this->calculateAverageAttendance($teacher->id),
        ];

        return view('teacher.lessons.index', compact('lessons', 'stats', 'teacher'));
    }

    /**
     * عرض نموذج إضافة درس جديد
     */
    public function create()
    {
        $teacher = auth()->user();
        
        // الأيام المتاحة
        $daysOfWeek = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];

        // المواد المتاحة (يمكن إضافة جدول منفصل للمواد لاحقاً)
        $subjects = [
            'الرياضيات',
            'العلوم',
            'اللغة العربية',
            'اللغة الإنجليزية',
            'التاريخ',
            'الجغرافيا',
            'الفيزياء',
            'الكيمياء',
            'الأحياء',
            'الحاسوب',
            'التربية الإسلامية',
            'التربية الوطنية',
        ];

        return view('teacher.lessons.create', compact('teacher', 'daysOfWeek', 'subjects'));
    }    /**
     * حفظ درس جديد
     */
    public function store(Request $request)
    {
        $teacher = auth()->user();
          $validated = $request->validate([
            'subject' => 'required|string|max:100|min:2',
            'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:1000',
        ], [
            'subject.required' => 'المادة مطلوبة',
            'subject.string' => 'المادة يجب أن تكون نص',
            'subject.max' => 'اسم المادة طويل جداً (الحد الأقصى 100 حرف)',
            'subject.min' => 'اسم المادة قصير جداً (الحد الأدنى حرفان)',
            'day_of_week.required' => 'يوم الأسبوع مطلوب',
            'start_time.required' => 'وقت البداية مطلوب',
            'end_time.required' => 'وقت النهاية مطلوب',
            'end_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية',
            'start_time.date_format' => 'تنسيق وقت البداية غير صحيح',
            'end_time.date_format' => 'تنسيق وقت النهاية غير صحيح',
        ]);

        // التحقق من عدم تعارض الأوقات
        $conflictingLesson = Lesson::where('teacher_id', $teacher->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
            })->first();

        if ($conflictingLesson) {
            return back()->withErrors([
                'time_conflict' => 'يوجد تعارض في الوقت مع درس آخر: ' . $conflictingLesson->subject
            ])->withInput();
        }        // تنظيف اسم المادة
        $subject = trim($validated['subject']);
        $subject = ucfirst($subject);

        // إنشاء اسم للدرس من المادة فقط
        $lessonName = $subject;

        // إنشاء الدرس
        $lesson = Lesson::create([
            'name' => $lessonName,
            'subject' => $subject,
            'teacher_id' => $teacher->id,
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'] . ':00',
            'end_time' => $validated['end_time'] . ':00',
            'description' => $validated['description'],
            'students_count' => 0,
            'status' => 'active',
        ]);

        return redirect()->route('teacher.lessons.index')
            ->with('success', 'تم إنشاء الدرس بنجاح: ' . $lesson->name);
    }

    /**
     * عرض تفاصيل درس محدد
     */
    public function show(Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // التأكد من أن المعلم يملك هذا الدرس
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لعرض هذا الدرس');
        }

        $lesson->load(['students', 'attendances.student']);
        
        // إحصائيات الدرس
        $attendanceStats = [
            'total' => $lesson->attendances->count(),
            'present' => $lesson->attendances->where('status', 'present')->count(),
            'absent' => $lesson->attendances->where('status', 'absent')->count(),
            'late' => $lesson->attendances->where('status', 'late')->count(),
            'excused' => $lesson->attendances->where('status', 'excused')->count(),
        ];

        $attendanceStats['attendance_rate'] = $attendanceStats['total'] > 0 
            ? round(($attendanceStats['present'] / $attendanceStats['total']) * 100, 1) 
            : 0;

        // أحدث سجلات الحضور
        $recentAttendances = $lesson->attendances()
            ->with('student')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('teacher.lessons.show', compact('lesson', 'attendanceStats', 'recentAttendances', 'teacher'));
    }

    /**
     * عرض نموذج تعديل الدرس
     */
    public function edit(Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // التأكد من أن المعلم يملك هذا الدرس
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الدرس');
        }

        // الأيام المتاحة
        $daysOfWeek = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];

        // المواد المتاحة
        $subjects = [
            'الرياضيات',
            'العلوم',
            'اللغة العربية',
            'اللغة الإنجليزية',
            'التاريخ',
            'الجغرافيا',
            'الفيزياء',
            'الكيمياء',
            'الأحياء',
            'الحاسوب',
            'التربية الإسلامية',
            'التربية الوطنية',
        ];

        return view('teacher.lessons.edit', compact('lesson', 'teacher', 'daysOfWeek', 'subjects'));
    }

    /**
     * تحديث الدرس
     */
    public function update(Request $request, Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // التأكد من أن المعلم يملك هذا الدرس
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الدرس');
        }        $validated = $request->validate([
            'subject' => 'required|string|max:100',
            'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:active,inactive',
        ], [
            'subject.required' => 'المادة مطلوبة',
            'day_of_week.required' => 'يوم الأسبوع مطلوب',
            'start_time.required' => 'وقت البداية مطلوب',
            'end_time.required' => 'وقت النهاية مطلوب',
            'end_time.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية',
        ]);

        // التحقق من عدم تعارض الأوقات (باستثناء الدرس الحالي)
        $conflictingLesson = Lesson::where('teacher_id', $teacher->id)
            ->where('id', '!=', $lesson->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
            })->first();        if ($conflictingLesson) {
            return back()->withErrors([
                'time_conflict' => 'يوجد تعارض في الوقت مع درس آخر: ' . $conflictingLesson->subject
            ])->withInput();
        }        // تحديث الدرس
        $updateData = [
            'name' => $validated['subject'],
            'subject' => $validated['subject'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'] . ':00',
            'end_time' => $validated['end_time'] . ':00',
            'description' => $validated['description'],
        ];

        // إضافة الحالة إذا تم تمريرها
        if (isset($validated['status'])) {
            $updateData['status'] = $validated['status'];
        }

        $lesson->update($updateData);

        return redirect()->route('teacher.lessons.index')
            ->with('success', 'تم تحديث الدرس بنجاح: ' . $lesson->name);
    }

    /**
     * حذف الدرس
     */
    public function destroy(Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // التأكد من أن المعلم يملك هذا الدرس
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لحذف هذا الدرس');
        }

        // التحقق من وجود سجلات حضور
        if ($lesson->attendances()->count() > 0) {
            return back()->withErrors([
                'delete_error' => 'لا يمكن حذف هذا الدرس لأنه يحتوي على سجلات حضور'
            ]);
        }

        $lessonName = $lesson->name;
        $lesson->delete();

        return redirect()->route('teacher.lessons.index')
            ->with('success', 'تم حذف الدرس بنجاح: ' . $lessonName);
    }

    /**
     * حساب معدل الحضور للمعلم
     */
    private function calculateAverageAttendance($teacherId)
    {
        $lessons = Lesson::where('teacher_id', $teacherId)->get();
        $totalRecords = 0;
        $presentRecords = 0;

        foreach ($lessons as $lesson) {
            $attendances = $lesson->attendances;
            $totalRecords += $attendances->count();
            $presentRecords += $attendances->where('status', 'present')->count();
        }

        return $totalRecords > 0 ? round(($presentRecords / $totalRecords) * 100, 1) : 0;
    }

    /**
     * إدارة طلاب الدرس
     */
    public function manageStudents(Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // التأكد من أن المعلم يملك هذا الدرس
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لإدارة طلاب هذا الدرس');
        }

        $lesson->load(['students', 'attendances']);
        
        // الطلاب غير المسجلين
        $availableStudents = User::where('role', 'student')
            ->whereNotIn('id', $lesson->students->pluck('id'))
            ->orderBy('name')
            ->get();

        // حساب معدل الحضور لكل طالب
        $studentAttendanceRates = [];
        foreach ($lesson->students as $student) {
            $totalAttendances = $lesson->attendances()->where('student_id', $student->id)->count();
            $presentAttendances = $lesson->attendances()
                ->where('student_id', $student->id)
                ->where('status', 'present')
                ->count();
            
            $studentAttendanceRates[$student->id] = $totalAttendances > 0 
                ? round(($presentAttendances / $totalAttendances) * 100, 1) 
                : 0;
        }

        // معدل الحضور العام للدرس
        $totalAttendances = $lesson->attendances()->count();
        $presentAttendances = $lesson->attendances()->where('status', 'present')->count();
        $averageAttendance = $totalAttendances > 0 
            ? round(($presentAttendances / $totalAttendances) * 100, 1) 
            : 0;

        return view('teacher.lessons.manage-students', compact(
            'lesson', 
            'availableStudents', 
            'studentAttendanceRates',
            'averageAttendance'
        ));
    }

    /**
     * إضافة طالب للدرس
     */
    public function addStudent(Request $request, Lesson $lesson)
    {
        $teacher = auth()->user();
        
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لإدارة هذا الدرس');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::findOrFail($validated['student_id']);
        
        // التحقق من أن المستخدم طالب
        if ($student->role !== 'student') {
            return back()->withErrors(['error' => 'المستخدم المحدد ليس طالباً']);
        }        // التحقق من عدم تسجيل الطالب مسبقاً
        if ($lesson->students()->where('users.id', $student->id)->exists()) {
            return back()->withErrors(['error' => 'الطالب مسجل بالفعل في هذا الدرس']);
        }

        // التحقق من الحد الأقصى للطلاب
        if ($lesson->max_students && $lesson->students()->count() >= $lesson->max_students) {
            return back()->withErrors(['error' => 'تم الوصول للحد الأقصى لعدد الطلاب في هذا الدرس']);
        }

        // إضافة الطالب
        $lesson->students()->attach($student->id);

        return back()->with('success', 'تم إضافة الطالب ' . $student->name . ' بنجاح');
    }

    /**
     * إزالة طالب من الدرس
     */
    public function removeStudent(Lesson $lesson, User $student)
    {
        $teacher = auth()->user();
        
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لإدارة هذا الدرس');
        }        // التحقق من أن الطالب مسجل في الدرس
        if (!$lesson->students()->where('users.id', $student->id)->exists()) {
            return back()->withErrors(['error' => 'الطالب غير مسجل في هذا الدرس']);
        }

        // إزالة الطالب وحذف سجلات الحضور المرتبطة
        DB::transaction(function () use ($lesson, $student) {
            $lesson->attendances()->where('student_id', $student->id)->delete();
            $lesson->students()->detach($student->id);
        });

        return back()->with('success', 'تم إزالة الطالب ' . $student->name . ' بنجاح');
    }

    /**
     * إزالة طلاب متعددين من الدرس
     */
    public function removeStudents(Request $request, Lesson $lesson)
    {
        $teacher = auth()->user();
        
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لإدارة هذا الدرس');
        }

        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id'
        ]);

        $studentIds = $validated['student_ids'];
        $students = User::whereIn('id', $studentIds)->get();

        DB::transaction(function () use ($lesson, $studentIds) {
            // حذف سجلات الحضور
            $lesson->attendances()->whereIn('student_id', $studentIds)->delete();
            // إزالة الطلاب من الدرس
            $lesson->students()->detach($studentIds);
        });

        return back()->with('success', 'تم إزالة ' . count($studentIds) . ' طالب بنجاح');
    }

    /**
     * إزالة جميع الطلاب من الدرس
     */
    public function removeAllStudents(Lesson $lesson)
    {
        $teacher = auth()->user();
        
        if ($lesson->teacher_id !== $teacher->id) {
            abort(403, 'ليس لديك صلاحية لإدارة هذا الدرس');
        }

        $studentsCount = $lesson->students()->count();

        DB::transaction(function () use ($lesson) {
            // حذف جميع سجلات الحضور
            $lesson->attendances()->delete();
            // إزالة جميع الطلاب
            $lesson->students()->detach();
        });

        return back()->with('success', 'تم إزالة جميع الطلاب (' . $studentsCount . ' طالب) بنجاح');
    }

    /**
     * الحصول على طلاب الدرس (للـ AJAX)
     */
    public function getStudents(Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // التحقق من الصلاحيات
        if ($lesson->teacher_id !== $teacher->id) {
            return response()->json([
                'success' => false,
                'error' => 'غير مسموح لك بالوصول لهذا الدرس'
            ], 403);
        }
        
        try {
            $students = $lesson->students()->select('id', 'name')->get();
            
            return response()->json([
                'success' => true,
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'خطأ في تحميل بيانات الطلاب: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب دروس الطالب
     */
    public function getStudentLessons(User $student)
    {
        $teacher = auth()->user();
        
        // التحقق من أن المستخدم المحدد هو طالب
        if ($student->role !== 'student') {
            return response()->json([
                'success' => false,
                'error' => 'المستخدم المحدد ليس طالباً'
            ], 400);
        }
        
        try {
            // جلب دروس الطالب التي يدرسها هذا المعلم فقط
            $lessons = $student->lessons()
                ->where('teacher_id', $teacher->id)
                ->select('id', 'subject', 'name')
                ->get();
            
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
}
