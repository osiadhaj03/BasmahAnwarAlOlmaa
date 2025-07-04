<?php $__env->startSection('title', 'المعلمين'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        المعلمين
                    </h4>
                    <div>
                        <a href="<?php echo e(route('admin.users.create')); ?>?role=teacher" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>
                            إضافة معلم جديد
                        </a>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-users me-2"></i>
                            جميع المستخدمين
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- فلتر البحث -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="<?php echo e(route('admin.users.teachers')); ?>" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="البحث في اسم المعلم أو البريد الإلكتروني..." 
                                       value="<?php echo e(request('search')); ?>">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <span class="badge bg-success fs-6">إجمالي المعلمين: <?php echo e($teachers->total()); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- جدول المعلمين -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>الرقم</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>عدد الدروس</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($teacher->id); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-success me-2">
                                                <?php echo e(substr($teacher->name, 0, 2)); ?>

                                            </div>
                                            <?php echo e($teacher->name); ?>

                                        </div>
                                    </td>
                                    <td><?php echo e($teacher->email); ?></td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e($teacher->teaching_lessons_count); ?> درس</span>
                                    </td>
                                    <td><?php echo e($teacher->created_at->format('Y/m/d')); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.users.show', $teacher)); ?>" 
                                               class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.users.edit', $teacher)); ?>" 
                                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if($teacher->id !== auth()->id()): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    title="حذف" onclick="confirmDelete(<?php echo e($teacher->id); ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-chalkboard-teacher fa-2x mb-3"></i>
                                        <p>لا يوجد معلمين</p>
                                        <a href="<?php echo e(route('admin.users.create')); ?>?role=teacher" class="btn btn-primary">
                                            إضافة معلم جديد
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($teachers->appends(request()->query())->links()); ?>

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
                <h5 class="modal-title">تأكيد حذف المعلم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد من حذف هذا المعلم؟ سيتم حذف جميع البيانات المرتبطة به.
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
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    text-transform: uppercase;
    color: white;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function confirmDelete(teacherId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `<?php echo e(route('admin.users.index')); ?>/${teacherId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/admin/users/teachers.blade.php ENDPATH**/ ?>