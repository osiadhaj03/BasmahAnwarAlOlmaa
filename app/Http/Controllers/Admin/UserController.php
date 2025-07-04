<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // فلترة حسب الدور
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }
        
        // البحث في الاسم أو البريد الإلكتروني
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض صفحة إنشاء مستخدم جديد
     */
    public function create()
    {
        return view('admin.users.create');
    }    /**
     * حفظ مستخدم جديد (للإدارة فقط)
     * ملاحظة: هذا المتحكم محمي بـ middleware admin فقط المديرين يمكنهم إنشاء المعلمين
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'teacher', 'student'])],
        ], [
            'role.required' => 'يجب تحديد دور المستخدم',
            'role.in' => 'دور المستخدم غير صحيح',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);        $roleText = $request->role === 'admin' ? 'مدير' : ($request->role === 'teacher' ? 'معلم' : 'طالب');
        
        return redirect()->route('admin.users.index')
            ->with('success', "تم إنشاء {$roleText} جديد بنجاح: {$request->name}");
    }

    /**
     * عرض تفاصيل مستخدم محدد
     */
    public function show(User $user)
    {
        // إحصائيات خاصة بالمستخدم
        $stats = [];
        
        if ($user->role === 'teacher') {
            $stats = [
                'total_lessons' => $user->teachingLessons()->count(),
                'total_students' => $user->teachingLessons()->withCount('students')->get()->sum('students_count'),
            ];
        } elseif ($user->role === 'student') {
            $stats = [
                'total_lessons' => $user->lessons()->count(),
                'total_attendances' => $user->attendances()->count(),
                'present_count' => $user->attendances()->where('status', 'present')->count(),
                'late_count' => $user->attendances()->where('status', 'late')->count(),
                'absent_count' => $user->attendances()->where('status', 'absent')->count(),
            ];
        }
        
        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * عرض صفحة تعديل مستخدم
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'teacher', 'student'])],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // تحديث كلمة المرور فقط إذا تم إدخالها
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    /**
     * حذف مستخدم
     */
    public function destroy(User $user)
    {
        // منع حذف المدير الحالي
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        // إذا كان معلماً، تحقق من وجود دروس مرتبطة به
        if ($user->role === 'teacher' && $user->teachingLessons()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'لا يمكن حذف هذا المعلم لوجود دروس مرتبطة به');
        }

        // إذا كان طالباً، حذف سجلات الحضور المرتبطة به
        if ($user->role === 'student') {
            $user->attendances()->delete();
            $user->lessons()->detach();
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    /**
     * عرض المعلمين فقط
     */
    public function teachers(Request $request)
    {
        $query = User::where('role', 'teacher');
        
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $teachers = $query->withCount('teachingLessons')->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users.teachers', compact('teachers'));
    }

    /**
     * عرض الطلاب فقط
     */
    public function students(Request $request)
    {
        $query = User::where('role', 'student');
        
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $students = $query->withCount(['lessons', 'attendances'])->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users.students', compact('students'));
    }
}
