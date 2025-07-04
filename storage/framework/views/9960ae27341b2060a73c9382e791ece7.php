<?php $__env->startSection('title', 'إدارة الحضور'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- رسائل النجاح والخطأ -->    <?php if(session('success')): ?>
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

    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0">
                                <i class="fas fa-clipboard-check me-2 text-primary"></i>
                                إدارة الحضور
                            </h4>
                            <small class="text-muted">
                                <?php if(auth()->user()->role === 'admin'): ?>
                                    عرض وتقارير الحضور لجميع المعلمين
                                <?php else: ?>
                                    إدارة حضور دروسك
                                <?php endif; ?>
                            </small>
                        </div>                        <div class="col-auto">
                            <div class="alert alert-info mb-0 me-2" style="font-size: 0.85rem;">
                                <i class="fas fa-info-circle me-1"></i>
                                تسجيل الحضور يتم عبر الطلاب باستخدام QR Code فقط
                            </div>
                            <a href="<?php echo e(route('admin.attendances.reports')); ?>" class="btn btn-info">
                                <i class="fas fa-chart-line me-2"></i>
                                التقارير المتقدمة
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <?php if(isset($stats)): ?>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                            <h3 class="mb-0"><?php echo e($stats['total']); ?></h3>
                            <small>إجمالي السجلات</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-day fa-2x mb-2"></i>
                            <h3 class="mb-0"><?php echo e($stats['today']); ?></h3>
                            <small>سجلات اليوم</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-check fa-2x mb-2"></i>
                            <h3 class="mb-0"><?php echo e($stats['present_today']); ?></h3>
                            <small>حاضر اليوم</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-times fa-2x mb-2"></i>
                            <h3 class="mb-0"><?php echo e($stats['absent_today']); ?></h3>
                            <small>غائب اليوم</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- فلاتر البحث -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        فلاتر البحث والتصفية
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.attendances.index')); ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">البحث</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="search" 
                                       name="search" 
                                       value="<?php echo e(request('search')); ?>"
                                       placeholder="اسم الطالب أو المادة...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="lesson_id" class="form-label">الدرس</label>
                                <select class="form-control" id="lesson_id" name="lesson_id">
                                    <option value="">جميع الدروس</option>
                                    <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($lesson->id); ?>" 
                                                <?php echo e(request('lesson_id') == $lesson->id ? 'selected' : ''); ?>>
                                            <?php echo e($lesson->subject); ?> - <?php echo e($lesson->name); ?>

                                            <?php if(auth()->user()->role === 'admin'): ?>
                                                (<?php echo e($lesson->teacher->name); ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">جميع الحالات</option>
                                    <option value="present" <?php echo e(request('status') === 'present' ? 'selected' : ''); ?>>حاضر</option>
                                    <option value="absent" <?php echo e(request('status') === 'absent' ? 'selected' : ''); ?>>غائب</option>
                                    <option value="late" <?php echo e(request('status') === 'late' ? 'selected' : ''); ?>>متأخر</option>
                                    <option value="excused" <?php echo e(request('status') === 'excused' ? 'selected' : ''); ?>>بعذر</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date_from" class="form-label">من تاريخ</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date_from" 
                                       name="date_from" 
                                       value="<?php echo e(request('date_from')); ?>">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date_to" class="form-label">إلى تاريخ</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date_to" 
                                       name="date_to" 
                                       value="<?php echo e(request('date_to')); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-2"></i>
                                    بحث وتصفية
                                </button>
                                <a href="<?php echo e(route('admin.attendances.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-2"></i>
                                    إعادة تعيين
                                </a>
                                <?php if(request()->hasAny(['search', 'lesson_id', 'status', 'date_from', 'date_to'])): ?>
                                <span class="badge bg-info ms-2">
                                    <i class="fas fa-filter me-1"></i>
                                    فلاتر نشطة
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- جدول الحضور -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>
                                سجلات الحضور
                                <?php if($attendances->total() > 0): ?>
                                    <span class="badge bg-secondary"><?php echo e($attendances->total()); ?> سجل</span>
                                <?php endif; ?>
                            </h5>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted">
                                عرض <?php echo e($attendances->firstItem() ?? 0); ?> إلى <?php echo e($attendances->lastItem() ?? 0); ?> 
                                من أصل <?php echo e($attendances->total()); ?> سجل
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if($attendances->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>الطالب</th>
                                        <th>المادة/الدرس</th>
                                        <?php if(auth()->user()->role === 'admin'): ?>
                                            <th>المعلم</th>
                                        <?php endif; ?>
                                        <th style="width: 120px;">التاريخ</th>
                                        <th style="width: 100px;">الحالة</th>
                                        <th>الملاحظات</th>
                                        <?php if(auth()->user()->role === 'teacher'): ?>
                                            <th style="width: 120px;">الإجراءات</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-muted">
                                                <?php echo e(($attendances->currentPage() - 1) * $attendances->perPage() + $index + 1); ?>

                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-<?php echo e($attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning')); ?> text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <?php echo e(substr($attendance->student->name, 0, 2)); ?>

                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?php echo e($attendance->student->name); ?></div>
                                                        <small class="text-muted"><?php echo e($attendance->student->email); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold text-primary"><?php echo e($attendance->lesson->subject); ?></div>
                                                    <small class="text-muted"><?php echo e($attendance->lesson->name); ?></small>
                                                </div>
                                            </td>
                                            <?php if(auth()->user()->role === 'admin'): ?>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-chalkboard-teacher me-2 text-info"></i>
                                                        <?php echo e($attendance->lesson->teacher->name); ?>

                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <div class="text-center">
                                                    <div class="fw-bold"><?php echo e($attendance->date->format('Y/m/d')); ?></div>
                                                    <small class="text-muted"><?php echo e($attendance->date->format('l')); ?></small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php switch($attendance->status):
                                                    case ('present'): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>
                                                            حاضر
                                                        </span>
                                                        <?php break; ?>
                                                    <?php case ('absent'): ?>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times me-1"></i>
                                                            غائب
                                                        </span>
                                                        <?php break; ?>
                                                    <?php case ('late'): ?>
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            متأخر
                                                        </span>
                                                        <?php break; ?>
                                                    <?php case ('excused'): ?>
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-user-check me-1"></i>
                                                            بعذر
                                                        </span>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                <?php if($attendance->notes): ?>
                                                    <span class="text-muted" title="<?php echo e($attendance->notes); ?>">
                                                        <?php echo e(Str::limit($attendance->notes, 30)); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if(auth()->user()->role === 'teacher'): ?>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?php echo e(route('admin.attendances.show', $attendance)); ?>" 
                                                           class="btn btn-outline-info" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?php echo e(route('admin.attendances.edit', $attendance)); ?>" 
                                                           class="btn btn-outline-warning" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" 
                                                              action="<?php echo e(route('admin.attendances.destroy', $attendance)); ?>" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted">
                                        عرض <?php echo e($attendances->firstItem()); ?> إلى <?php echo e($attendances->lastItem()); ?> 
                                        من أصل <?php echo e($attendances->total()); ?> سجل
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <?php echo e($attendances->appends(request()->query())->links()); ?>

                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد سجلات حضور</h5>
                            <?php if(request()->hasAny(['search', 'lesson_id', 'status', 'date_from', 'date_to'])): ?>
                                <p class="text-muted">لا توجد نتائج مطابقة للفلاتر المحددة</p>
                                <a href="<?php echo e(route('admin.attendances.index')); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-undo me-2"></i>
                                    إزالة الفلاتر
                                </a>                            <?php else: ?>
                                <p class="text-muted">تسجيل الحضور متاح للطلاب فقط عبر QR Code</p>
                                <div class="alert alert-info">
                                    <i class="fas fa-qrcode me-2"></i>
                                    يمكن للطلاب تسجيل الحضور باستخدام رمز QR الخاص بكل درس
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
.avatar-sm {
    width: 35px;
    height: 35px;
    font-size: 12px;
    font-weight: bold;
}

.table th {
    font-weight: 600;
    border-top: none;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin: 0 1px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75em;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/admin/attendances/index.blade.php ENDPATH**/ ?>