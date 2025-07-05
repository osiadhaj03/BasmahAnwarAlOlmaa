@extends('layouts.app')

@section('title', 'تسجيل طالب جديد - أنوار العلماء')

@section('content')
<div class="text-center mb-4 fade-in">
    <div class="mb-3">
        <i class="fas fa-user-plus logo-anwar-large"></i>
    </div>
    <h2 class="text-anwar-gradient mb-2 heading-anwar-center">تسجيل طالب جديد</h2>
    <p class="text-anwar-teal">أنشئ حسابك للانضمام إلى أنوار العلماء</p>
</div>

<!-- Error messages -->
@if ($errors->any())
    <div class="alert-anwar-danger mb-4">
        <i class="fas fa-exclamation-triangle alert-icon"></i>
        <strong>يرجى تصحيح الأخطاء التالية:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('student.register') }}" autocomplete="off">
    @csrf

    <!-- Name -->
    <div class="form-group-anwar">
        <label for="name" class="form-label-anwar">
            <i class="fas fa-user me-2 text-anwar-gold"></i>
            الاسم الكامل *
        </label>
        <input id="name" type="text" 
               class="form-control-anwar @error('name') is-invalid @enderror hover-lift" 
               name="name" 
               value="{{ old('name') }}" 
               required 
               autocomplete="off"
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
               class="form-control-anwar @error('email') is-invalid @enderror hover-lift" 
               name="email" 
               value="{{ old('email') }}" 
               required 
               autocomplete="off"
               placeholder="example@domain.com">
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Phone -->
    <div class="form-group-anwar">
        <label for="phone" class="form-label-anwar">
            <i class="fas fa-phone me-2 text-anwar-teal"></i>
            رقم الهاتف *
        </label>
        <input id="phone" type="tel" 
               class="form-control-anwar @error('phone') is-invalid @enderror hover-lift" 
               name="phone" 
               value="{{ old('phone') }}" 
               required 
               autocomplete="off"
               placeholder="05xxxxxxxx">
        @error('phone')
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
               class="form-control-anwar @error('student_id') is-invalid @enderror hover-lift" 
               name="student_id" 
               value="{{ old('student_id') }}" 
               autocomplete="off"
               placeholder="أدخل رقمك الجامعي إن وجد">
        @error('student_id')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group-anwar">
        <label for="password" class="form-label-anwar">
            <i class="fas fa-lock me-2 text-anwar-gold"></i>
            كلمة المرور *
        </label>
        <input id="password" type="password" 
               class="form-control-anwar @error('password') is-invalid @enderror hover-lift" 
               name="password" 
               required 
               autocomplete="new-password"
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
               class="form-control-anwar hover-lift" 
               name="password_confirmation" 
               required 
               autocomplete="new-password"
               placeholder="أعد إدخال كلمة المرور">
    </div>

    <div class="d-grid gap-2 mb-4">
        <button type="submit" class="btn-anwar-gold btn-anwar-lg hover-lift">
            <i class="fas fa-user-plus me-2"></i>
            إنشاء الحساب
        </button>
    </div>
</form>

<div class="text-center fade-in">
    <p class="mb-3 text-anwar-gray-dark">
        لديك حساب بالفعل؟ 
        <a href="{{ route('admin.login') }}" class="text-decoration-none text-anwar-teal fw-bold">
            تسجيل الدخول
        </a>
    </p>
    <div class="divider-anwar-animated"></div>
    <div class="alert-anwar-info p-3">
        <i class="fas fa-info-circle alert-icon"></i>
        <strong>مهم:</strong> تسجيل الطلاب فقط - المعلمين يتم إنشاء حساباتهم من قبل الإدارة
    </div>
</div>
@endsection

@section('scripts')
<script>
// تحسين تجربة المستخدم مع التأثيرات البصرية البسيطة
document.addEventListener('DOMContentLoaded', function() {
    // تأثير fade-in للعناصر
    const elements = document.querySelectorAll('.fade-in');
    elements.forEach((element, index) => {
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 200);
    });
    
    // منع auto-fill للحقول
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        // مسح أي قيم محفوظة
        setTimeout(() => {
            if (!input.value || input.value === '') {
                input.value = '';
            }
        }, 100);
        
        // منع auto-complete
        input.setAttribute('autocomplete', 'off');
        input.setAttribute('data-lpignore', 'true'); // لـ LastPass
        input.setAttribute('data-form-type', 'other'); // لبرامج إدارة كلمات المرور
    });
    
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
    
    // تنظيف الحقول عند التحميل (لمنع auto-fill)
    setTimeout(() => {
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';
    }, 500);
});

// إضافة تأثير fade-in بسيط
if (!document.querySelector('#simple-animation-styles')) {
    const style = document.createElement('style');
    style.id = 'simple-animation-styles';
    style.textContent = `
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }
    `;
    document.head.appendChild(style);
}
</script>
@endsection
