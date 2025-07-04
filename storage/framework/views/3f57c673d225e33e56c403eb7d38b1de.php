<?php $__env->startSection('title', 'تسجيل طالب جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="text-center mb-4">
    <div class="mb-3">
        <i class="fas fa-user-plus text-gradient" style="font-size: 3rem;"></i>
    </div>
    <h2 class="text-gradient mb-2">تسجيل طالب جديد</h2>
    <p class="text-muted">أنشئ حسابك للانضمام إلى نظام BasmahApp</p>
</div>

<!-- Error messages -->
<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-custom mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>يرجى تصحيح الأخطاء التالية:</strong>
        <ul class="mb-0 mt-2">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('student.register')); ?>" autocomplete="off">
    <?php echo csrf_field(); ?>

    <!-- Name -->
    <div class="mb-3">
        <label for="name" class="form-label">
            <i class="fas fa-user me-1"></i>
            الاسم الكامل *
        </label>
        <input id="name" type="text" 
               class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               name="name" 
               value="<?php echo e(old('name')); ?>" 
               required 
               autocomplete="off"
               placeholder="أدخل اسمك الكامل">
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="fas fa-envelope me-1"></i>
            البريد الإلكتروني *
        </label>
        <input id="email" type="email" 
               class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               name="email" 
               value="<?php echo e(old('email')); ?>" 
               required 
               autocomplete="off"
               placeholder="example@domain.com">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="fas fa-lock me-1"></i>
            كلمة المرور *
        </label>
        <input id="password" type="password" 
               class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
               name="password" 
               required 
               autocomplete="new-password"
               placeholder="8 أحرف على الأقل">
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
               autocomplete="new-password"
               placeholder="أعد إدخال كلمة المرور">
    </div>

    <div class="d-grid gap-2 mb-4">
        <button type="submit" class="btn btn-primary-custom btn-custom">
            <i class="fas fa-user-plus me-2"></i>
            إنشاء الحساب
        </button>
    </div>
</form>

<div class="text-center">
    <p class="mb-2">
        لديك حساب بالفعل؟ 
        <a href="<?php echo e(route('admin.login')); ?>" class="text-decoration-none" style="color: #667eea;">
            تسجيل الدخول
        </a>
    </p>
    <hr>
    <div class="alert-custom" style="background: rgba(255, 107, 107, 0.1);">
        <p class="text-muted small mb-0">
            <i class="fas fa-info-circle me-1"></i>
            <strong>مهم:</strong> تسجيل الطلاب فقط - المعلمين يتم إنشاء حساباتهم من قبل الإدارة
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// تحسين تجربة المستخدم ومنع auto-fill
document.addEventListener('DOMContentLoaded', function() {
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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/auth/student-register-new.blade.php ENDPATH**/ ?>