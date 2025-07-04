@extends('layouts.app')

@section('title', 'تسجيل طالب جديد')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        تسجيل طالب جديد
                    </h4>
                    <small>أنشئ حسابك للانضمام إلى نظام BasmahApp</small>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user me-1"></i>
                                الاسم الكامل *
                            </label>
                            <input id="name" type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   placeholder="أدخل اسمك الكامل">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>
                                البريد الإلكتروني *
                            </label>
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="example@domain.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Student ID -->
                        <div class="mb-3">
                            <label for="student_id" class="form-label">
                                <i class="fas fa-id-card me-1"></i>
                                رقم الطالب (اختياري)
                            </label>
                            <input id="student_id" type="text" 
                                   class="form-control @error('student_id') is-invalid @enderror" 
                                   name="student_id" 
                                   value="{{ old('student_id') }}" 
                                   placeholder="أدخل رقمك الجامعي إن وجد">
                            @error('student_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>
                                رقم الهاتف (اختياري)
                            </label>
                            <input id="phone" type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="05xxxxxxxx">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                كلمة المرور *
                            </label>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   placeholder="8 أحرف على الأقل">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                تأكيد كلمة المرور *
                            </label>
                            <input id="password_confirmation" type="password" 
                                   class="form-control" 
                                   name="password_confirmation" 
                                   required 
                                   placeholder="أعد إدخال كلمة المرور">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                إنشاء الحساب
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">
                        لديك حساب بالفعل؟ 
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            تسجيل الدخول
                        </a>
                    </p>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        تسجيل الطلاب فقط - المعلمين يتم إنشاء حساباتهم من قبل الإدارة
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// تحسين تجربة المستخدم
document.addEventListener('DOMContentLoaded', function() {
    // تأكيد كلمة المرور
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    
    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('كلمات المرور غير متطابقة');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    password.addEventListener('change', validatePassword);
    confirmPassword.addEventListener('keyup', validatePassword);
});
</script>
@endsection
