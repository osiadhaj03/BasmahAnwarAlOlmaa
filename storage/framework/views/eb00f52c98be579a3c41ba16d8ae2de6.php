<?php $__env->startSection('title', 'إدارة المستخدمين'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        إدارة المستخدمين
                    </h4>
                    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة مستخدم جديد
                    </a>                </div>
                
                <div class="card-body">
                    <!-- رسائل النجاح والخطأ -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- فلاتر البحث -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="البحث في الاسم أو البريد الإلكتروني..." 
                                       value="<?php echo e(request('search')); ?>">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="<?php echo e(route('admin.users.index')); ?>">
                                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                                <select name="role" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع الأدوار</option>
                                    <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>مدير</option>
                                    <option value="teacher" <?php echo e(request('role') === 'teacher' ? 'selected' : ''); ?>>معلم</option>
                                    <option value="student" <?php echo e(request('role') === 'student' ? 'selected' : ''); ?>>طالب</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- الروابط السريعة -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.users.index')); ?>" 
                                   class="btn <?php echo e(!request('role') ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    جميع المستخدمين (<?php echo e(App\Models\User::count()); ?>)
                                </a>
                                <a href="<?php echo e(route('admin.users.teachers')); ?>" 
                                   class="btn btn-outline-success">
                                    المعلمين (<?php echo e(App\Models\User::where('role', 'teacher')->count()); ?>)
                                </a>
                                <a href="<?php echo e(route('admin.users.students')); ?>" 
                                   class="btn btn-outline-info">
                                    الطلاب (<?php echo e(App\Models\User::where('role', 'student')->count()); ?>)
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- جدول المستخدمين -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>الرقم</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الدور</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($user->id); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <?php echo e(substr($user->name, 0, 2)); ?>

                                            </div>
                                            <?php echo e($user->name); ?>

                                        </div>
                                    </td>
                                    <td><?php echo e($user->email); ?></td>
                                    <td>
                                        <?php if($user->role === 'admin'): ?>
                                            <span class="badge bg-danger">مدير</span>
                                        <?php elseif($user->role === 'teacher'): ?>
                                            <span class="badge bg-success">معلم</span>
                                        <?php elseif($user->role === 'student'): ?>
                                            <span class="badge bg-info">طالب</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($user->created_at->format('Y/m/d')); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.users.show', $user)); ?>" 
                                               class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" 
                                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>                                            <?php if($user->id !== auth()->id()): ?>
                                            <!-- نموذج حذف مضمن -->
                                            <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('هل أنت متأكد من حذف المستخدم <?php echo e($user->name); ?>؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <p>لا توجد مستخدمين</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($users->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>            <div class="modal-body">
                <p>هل أنت متأكد من حذف المستخدم <strong id="userNameToDelete"></strong>؟</p>
                <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
.avatar-circle {
    width: 35px;
    height: 35px;
    background-color: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    text-transform: uppercase;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function confirmDelete(userId, userName) {
    // تحديث اسم المستخدم في النموذج
    document.getElementById('userNameToDelete').textContent = userName;
    
    // تحديث action في النموذج
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = "<?php echo e(url('admin/users')); ?>/" + userId;
    
    // إضافة تسجيل للتصحيح
    console.log('Delete URL:', deleteForm.action);
    
    // إظهار النموذج
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// التحقق من إرسال النموذج
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    console.log('Form submitted with action:', this.action);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/admin/users/index.blade.php ENDPATH**/ ?>