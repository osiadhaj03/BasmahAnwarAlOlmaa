<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class StudentRegisterController extends Controller
{
    public function __construct()
    {
        // تم نقل middleware إلى routes/web.php
    }    /**
     * Show the student registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.student-register-new');
    }/**
     * Handle a registration request for students only.
     */
    public function register(Request $request)
    {
        // التحقق من أن التطبيق يسمح بتسجيل الطلاب فقط
        if ($request->has('role') && $request->input('role') !== 'student') {
            return redirect()->back()
                ->withErrors(['error' => 'التسجيل متاح للطلاب فقط. المعلمين يتم إنشاؤهم من قبل الإدارة.'])
                ->withInput();
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('student.dashboard')
            ->with('success', 'تم إنشاء حسابك بنجاح! مرحباً بك في نظام BasmahApp');
    }

    /**
     * Get a validator for an incoming registration request.
     */    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]*$/'],
            'student_id' => ['nullable', 'string', 'max:50', 'unique:users,student_id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['prohibited'], // منع تمرير role من الأساس
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صحيحاً',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'رقم الهاتف يجب أن يحتوي على أرقام وعلامات فقط',
            'student_id.unique' => 'رقم الطالب مستخدم مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'role.prohibited' => 'غير مسموح بتحديد الدور - التسجيل للطلاب فقط',
        ]);
    }

    /**
     * Create a new student user instance after a valid registration.
     */    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => isset($data['phone']) ? $data['phone'] : null,
            'student_id' => isset($data['student_id']) && !empty($data['student_id']) ? $data['student_id'] : null,
            'password' => Hash::make($data['password']),
            'role' => 'student', // فقط الطلاب يمكنهم التسجيل
        ]);
    }
}
