<?php $__env->startSection('title', 'الرئيسية'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">مرحباً بك في لوحة التحكم</h1>
    <div class="text-muted">
        <?php echo e(now()->format('Y/m/d - H:i')); ?>

    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <?php if(auth()->user()->role === 'admin'): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">إجمالي المستخدمين</div>
                            <div class="h2 mb-0 text-white"><?php echo e($data['totalUsers'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">المعلمين</div>
                            <div class="h2 mb-0"><?php echo e($data['totalTeachers'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chalkboard-teacher fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">الطلاب</div>
                            <div class="h2 mb-0"><?php echo e($data['totalStudents'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-graduate fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">الدروس</div>
                            <div class="h2 mb-0"><?php echo e($data['totalLessons'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif(auth()->user()->role === 'teacher'): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">دروسي</div>
                            <div class="h2 mb-0 text-white"><?php echo e($data['myLessons'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">طلابي</div>
                            <div class="h2 mb-0"><?php echo e($data['myStudents'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-graduate fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">حضور اليوم</div>
                            <div class="h2 mb-0"><?php echo e($data['todayAttendances'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-day fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">إجمالي الحضور</div>
                            <div class="h2 mb-0"><?php echo e($data['totalAttendances'] ?? 0); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-check fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    الإجراءات السريعة
                </h5>
            </div>
            <div class="card-body">                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-users me-2"></i>
                            إدارة المستخدمين
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('admin.lessons.index')); ?>" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-book me-2"></i>
                            إدارة الدروس
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('admin.attendances.index')); ?>" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-clipboard-check me-2"></i>
                            إدارة الحضور
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-user-plus me-2"></i>
                            إضافة مستخدم
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('admin.lessons.create')); ?>" class="btn btn-secondary btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>
                            إضافة درس جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات النظام
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0"><?php echo e(auth()->user()->name); ?></h6>
                        <small class="text-muted">
                            <?php echo e(auth()->user()->role === 'admin' ? 'مدير النظام' : 'معلم'); ?>

                        </small>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <h6 class="text-primary">BasmahApp</h6>
                    <small class="text-muted">نظام إدارة الحضور الذكي</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>