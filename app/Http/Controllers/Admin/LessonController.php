<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Lesson::with(['teacher'])->withCount(['attendances']);
        
        // إذا كان المستخدم معلم، اعرض دروسه فقط
        if ($user->role === 'teacher') {
            $query->where('teacher_id', $user->id);
        }
        
        // البحث في اسم المادة أو وصف الدرس
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('teacher', function($teacherQuery) use ($search) {
                      $teacherQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // فلترة حسب يوم الأسبوع
        if ($request->filled('day')) {
            $query->where('day_of_week', $request->day);
        }
          // فلترة حسب المعلم (للمدير فقط)
        if ($request->filled('teacher_id') && $user->role === 'admin') {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        // فلترة حسب حالة الدرس
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // ترتيب النتائج
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $query->orderBy($sortBy, $sortDirection);
          $lessons = $query->paginate(15)->appends($request->query());
        
        // إحصائيات الدروس
        $baseQuery = Lesson::query();
        if ($user->role === 'teacher') {
            $baseQuery->where('teacher_id', $user->id);
        }
        
        $totalLessons = $baseQuery->count();
        $scheduledLessons = $baseQuery->where('status', 'scheduled')->count();
        $activeLessons = $baseQuery->where('status', 'active')->count();
        $completedLessons = $baseQuery->where('status', 'completed')->count();
        
        // بيانات إضافية للفلاتر
        $teachers = $user->role === 'admin' ? User::where('role', 'teacher')->get() : collect();
        $days = [
            'sunday' => 'الأحد',
            'monday' => 'الإثنين', 
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت'
        ];
        
        $statuses = [
            'scheduled' => 'مجدول',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ];
        
        return view('admin.lessons.index', compact(
            'lessons', 
            'teachers', 
            'days', 
            'statuses',
            'totalLessons',
            'scheduledLessons',
            'activeLessons',
            'completedLessons'
        ));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();
        
        return view('admin.lessons.create', compact('teachers', 'students'));
    }    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'status' => 'required|string|in:scheduled,active,completed,cancelled',
            'students' => 'array',
            'students.*' => 'exists:users,id',
        ]);

        $user = auth()->user();
        
        // إذا كان المستخدم معلم، يجب أن يكون هو المعلم المخصص للدرس
        if ($user->role === 'teacher') {
            $request->merge(['teacher_id' => $user->id]);
        }

        $lesson = Lesson::create([
            'name' => $request->subject, // استخدام subject كاسم للدرس
            'subject' => $request->subject,
            'teacher_id' => $request->teacher_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,            'status' => $request->status,
        ]);

        if ($request->has('students')) {
            $lesson->students()->attach($request->students);
        }

        return redirect()->route('admin.lessons.index')
            ->with('success', 'تم إنشاء الدرس بنجاح');
    }

    public function show(Lesson $lesson)
    {
        $this->authorizeLesson($lesson);
        
        $lesson->load(['teacher', 'students', 'attendances.student']);
        
        return view('admin.lessons.show', compact('lesson'));
    }

    public function edit(Lesson $lesson)
    {
        $this->authorizeLesson($lesson);
        
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();
        
        return view('admin.lessons.edit', compact('lesson', 'teachers', 'students'));    }    public function update(Request $request, Lesson $lesson)
    {
        $this->authorizeLesson($lesson);
          $request->validate([
            'subject' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'day_of_week' => 'required|string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'status' => 'required|string|in:scheduled,active,completed,cancelled',
            'students' => 'array',
            'students.*' => 'exists:users,id',
        ]);

        $user = auth()->user();
        
        // إذا كان المستخدم معلم، يجب أن يبقى هو المعلم المخصص للدرس
        if ($user->role === 'teacher') {
            $request->merge(['teacher_id' => $user->id]);
        }

        $lesson->update([
            'name' => $request->subject, // استخدام subject كاسم للدرس
            'subject' => $request->subject,
            'teacher_id' => $request->teacher_id,            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        if ($request->has('students')) {
            $lesson->students()->sync($request->students);
        } else {
            $lesson->students()->detach();
        }        return redirect()->route('admin.lessons.index')
            ->with('success', 'تم تحديث الدرس بنجاح');
    }

    public function destroy(Lesson $lesson)
    {
        $this->authorizeLesson($lesson);
        
        $lesson->delete();
        
        return redirect()->route('admin.lessons.index')
            ->with('success', 'تم حذف الدرس بنجاح');
    }

    private function authorizeLesson(Lesson $lesson)
    {
        $user = auth()->user();
        
        if ($user->role === 'teacher' && $lesson->teacher_id !== $user->id) {
            abort(403, 'غير مسموح لك بالوصول لهذا الدرس');
        }
    }
}
