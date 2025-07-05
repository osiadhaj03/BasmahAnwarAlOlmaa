@extends('layouts.app')

@section('title', 'تسجيل طالب جديد')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-anwar">
                <div class="card-anwar-header text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        تسجيل طالب جديد
                    </h4>
                    <small>أنشئ حسابك للانضمام إلى أنوار العلوم</small>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('student.register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="form-group-anwar">
                            <label for="name" class="form-label-anwar">
                                <i class="fas fa-user me-2 text-anwar-gold"></i>
                                الاسم الكامل *
                            </label>
                            <input id="name" type="text" 
                                   class="form-control-anwar @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   placeholder="أدخل اسمك الكامل">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group-anwar">
                            <label for="email" class="form-label-anwar">
                                <i class="fas fa-envelope me-2 text-anwar-gold"></i>
                                البريد الإلكتروني *
                            </label>
                            <input id="email" type="email" 
                                   class="form-control-anwar @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="example@domain.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Student ID -->
                        <div class="form-group-anwar">
                            <label for="student_id" class="form-label-anwar">
                                <i class="fas fa-id-card me-2 text-anwar-teal"></i>
                                رقم الطالب (اختياري)
                            </label>
                            <input id="student_id" type="text" 
                                   class="form-control-anwar @error('student_id') is-invalid @enderror" 
                                   name="student_id" 
                                   value="{{ old('student_id') }}" 
                                   placeholder="أدخل رقمك الجامعي إن وجد">
                            @error('student_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group-anwar">
                            <label for="phone" class="form-label-anwar">
                                <i class="fas fa-phone me-2 text-anwar-gold"></i>
                                رقم الهاتف *
                            </label>
                            <input id="phone" type="text" 
                                   class="form-control-anwar @error('phone') is-invalid @enderror" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   required
                                   placeholder="05xxxxxxxx">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group-anwar">
                            <label for="password" class="form-label-anwar">
                                <i class="fas fa-lock me-2 text-anwar-teal"></i>
                                كلمة المرور *
                            </label>
                            <input id="password" type="password" 
                                   class="form-control-anwar @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   placeholder="8 أحرف على الأقل">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group-anwar">
                            <label for="password_confirmation" class="form-label-anwar">
                                <i class="fas fa-lock me-2 text-anwar-teal"></i>
                                تأكيد كلمة المرور *
                            </label>
                            <input id="password_confirmation" type="password" 
                                   class="form-control-anwar" 
                                   name="password_confirmation" 
                                   required 
                                   placeholder="أعد إدخال كلمة المرور">
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn-anwar-gold btn-anwar-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                إنشاء الحساب
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center" style="background: var(--anwar-overlay-warm); border-radius: 0 0 25px 25px; border-top: 2px solid var(--anwar-gold-light);">
                    <p class="mb-2 text-anwar-gray-dark">
                        لديك حساب بالفعل؟ 
                        <a href="{{ route('login') }}" class="text-decoration-none text-anwar-teal fw-bold">
                            تسجيل الدخول
                        </a>
                    </p>
                    <div class="divider-anwar" style="margin: 1rem 0;"></div>
                    <div class="alert-anwar-info p-3 mb-0" style="border-radius: 15px;">
                        <i class="fas fa-info-circle me-2 text-anwar-teal"></i>
                        <strong>مهم:</strong> تسجيل الطلاب فقط - المعلمين يتم إنشاء حساباتهم من قبل الإدارة
                    </div>
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
