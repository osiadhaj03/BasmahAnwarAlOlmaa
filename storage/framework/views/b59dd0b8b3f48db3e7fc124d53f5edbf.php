<?php $__env->startSection('title', 'إدارة الدروس'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .filter-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .filter-section {
        border-right: 2px solid #dee2e6;
        padding-right: 20px;
        margin-right: 20px;
    }
    .filter-section:last-child {
        border-right: none;
        padding-right: 0;
        margin-right: 0;
    }
    .search-box {
        position: relative;
    }
    .search-box .fas {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .search-box input {
        padding-right: 40px;
    }
    .sort-controls {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 10px;
    }
    .lesson-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .lesson-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .day-badge {
        font-size: 0.85em;
        padding: 6px 12px;
        border-radius: 20px;
    }
    .time-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        border-radius: 15px;
        padding: 4px 8px;
        font-size: 0.8em;
    }
    .students-count {
        background: linear-gradient(45deg, #007bff, #6610f2);
        color: white;
        border-radius: 15px;
        padding: 4px 8px;
        font-size: 0.8em;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-book-open me-2 text-primary"></i>
        إدارة الدروس
    </h1>
    <a href="<?php echo e(route('admin.lessons.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        إضافة درس جديد
    </a>
</div>

<!-- نظام البحث والفلترة المتقدم -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.lessons.index')); ?>" id="filterForm">
            <div class="row g-3">
                <!-- البحث الرئيسي -->
                <div class="col-md-4">
                    <div class="filter-section">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-search me-2"></i>البحث
                        </h6>
                        <div class="search-box">
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="ابحث في المادة، الوصف، أو اسم المعلم..."
                                   style="padding-right: 40px;">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                <!-- فلاتر متقدمة -->
                <div class="col-md-8">
                    <div class="row g-3">
                        <!-- فلتر اليوم -->
                        <div class="col-md-3">
                            <h6 class="text-success mb-2">
                                <i class="fas fa-calendar-day me-1"></i>اليوم
                            </h6>
                            <select class="form-select" name="day_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل الأيام</option>
                                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" 
                                            <?php if(request('day_filter') == $value): ?> selected <?php endif; ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- فلتر المعلم (للمدير فقط) -->
                        <?php if(auth()->user()->role === 'admin' && $teachers->count() > 0): ?>
                        <div class="col-md-3">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-user-tie me-1"></i>المعلم
                            </h6>
                            <select class="form-select" name="teacher_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل المعلمين</option>
                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>" 
                                            <?php if(request('teacher_filter') == $teacher->id): ?> selected <?php endif; ?>>
                                        <?php echo e($teacher->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <!-- فلتر الوقت -->
                        <div class="col-md-3">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-clock me-1"></i>الوقت
                            </h6>
                            <select class="form-select" name="time_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل الأوقات</option>
                                <option value="morning" <?php if(request('time_filter') == 'morning'): ?> selected <?php endif; ?>>
                                    صباحي (قبل 12 ظهراً)
                                </option>
                                <option value="afternoon" <?php if(request('time_filter') == 'afternoon'): ?> selected <?php endif; ?>>
                                    بعد الظهر (12-6 مساءً)
                                </option>
                                <option value="evening" <?php if(request('time_filter') == 'evening'): ?> selected <?php endif; ?>>
                                    مسائي (بعد 6 مساءً)
                                </option>
                            </select>
                        </div>                        <!-- فلتر عدد الطلاب -->
                        <div class="col-md-2">
                            <h6 class="text-danger mb-2">
                                <i class="fas fa-users me-1"></i>الطلاب
                            </h6>
                            <select class="form-select" name="students_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل الأعداد</option>
                                <option value="none" <?php if(request('students_filter') == 'none'): ?> selected <?php endif; ?>>
                                    بدون طلاب
                                </option>
                                <option value="few" <?php if(request('students_filter') == 'few'): ?> selected <?php endif; ?>>
                                    قليل (1-10)
                                </option>
                                <option value="medium" <?php if(request('students_filter') == 'medium'): ?> selected <?php endif; ?>>
                                    متوسط (11-25)
                                </option>
                                <option value="many" <?php if(request('students_filter') == 'many'): ?> selected <?php endif; ?>>
                                    كثير (+25)
                                </option>
                            </select>
                        </div>

                        <!-- فلتر حالة الدرس -->
                        <div class="col-md-2">
                            <h6 class="text-purple mb-2">
                                <i class="fas fa-flag me-1"></i>الحالة
                            </h6>
                            <select class="form-select" name="status_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل الحالات</option>
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" 
                                            <?php if(request('status_filter') == $value): ?> selected <?php endif; ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>بحث
                    </button>
                    <a href="<?php echo e(route('admin.lessons.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i>مسح الفلاتر
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="sort-controls d-flex align-items-center justify-content-end">
                        <label class="me-2 text-muted small">ترتيب حسب:</label>
                        <select class="form-select form-select-sm me-2" name="sort_by" style="width: auto;" onchange="document.getElementById('filterForm').submit()">
                            <option value="created_at" <?php if(request('sort_by') == 'created_at'): ?> selected <?php endif; ?>>تاريخ الإنشاء</option>
                            <option value="subject" <?php if(request('sort_by') == 'subject'): ?> selected <?php endif; ?>>المادة</option>
                            <option value="teacher" <?php if(request('sort_by') == 'teacher'): ?> selected <?php endif; ?>>المعلم</option>
                            <option value="day" <?php if(request('sort_by') == 'day'): ?> selected <?php endif; ?>>اليوم</option>
                            <option value="time" <?php if(request('sort_by') == 'time'): ?> selected <?php endif; ?>>الوقت</option>
                            <option value="students" <?php if(request('sort_by') == 'students'): ?> selected <?php endif; ?>>عدد الطلاب</option>
                        </select>
                        <select class="form-select form-select-sm" name="sort_direction" style="width: auto;" onchange="document.getElementById('filterForm').submit()">
                            <option value="asc" <?php if(request('sort_direction') == 'asc'): ?> selected <?php endif; ?>>تصاعدي</option>
                            <option value="desc" <?php if(request('sort_direction') == 'desc'): ?> selected <?php endif; ?>>تنازلي</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- إحصائيات سريعة -->
<?php if(request()->hasAny(['search', 'day_filter', 'teacher_filter', 'time_filter', 'students_filter', 'status_filter'])): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    تم العثور على <strong><?php echo e($lessons->total()); ?></strong> درس مطابق للبحث والفلاتر المحددة
    <?php if(request('search')): ?>
        <span class="badge bg-primary ms-2">البحث: "<?php echo e(request('search')); ?>"</span>
    <?php endif; ?>
    <?php if(request('day_filter')): ?>
        <span class="badge bg-success ms-2">اليوم: <?php echo e($days[request('day_filter')]); ?></span>
    <?php endif; ?>
    <?php if(request('status_filter')): ?>
        <span class="badge bg-purple ms-2">الحالة: <?php echo e($statuses[request('status_filter')]); ?></span>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- قائمة الدروس -->
<div class="card">
    <div class="card-body">
        <?php if($lessons->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">
                                <i class="fas fa-book me-1 text-primary"></i>المادة
                            </th>
                            <th class="border-0">
                                <i class="fas fa-user-tie me-1 text-info"></i>المعلم
                            </th>
                            <th class="border-0">
                                <i class="fas fa-calendar-day me-1 text-success"></i>اليوم
                            </th>
                            <th class="border-0">
                                <i class="fas fa-clock me-1 text-warning"></i>الوقت
                            </th>                            <th class="border-0">
                                <i class="fas fa-users me-1 text-danger"></i>الطلاب
                            </th>
                            <th class="border-0">
                                <i class="fas fa-flag me-1 text-purple"></i>الحالة
                            </th>
                            <th class="border-0">
                                <i class="fas fa-cogs me-1 text-secondary"></i>الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="lesson-row">
                                <td>
                                    <div>
                                        <strong class="text-primary"><?php echo e($lesson->subject); ?></strong>
                                        <?php if($lesson->description): ?>
                                            <br><small class="text-muted"><?php echo e(Str::limit($lesson->description, 50)); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo e($lesson->teacher->name); ?></strong>
                                            <br><small class="text-muted"><?php echo e($lesson->teacher->email); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="day-badge 
                                        <?php switch($lesson->day_of_week):
                                            case ('sunday'): ?> bg-primary <?php break; ?>
                                            <?php case ('monday'): ?> bg-success <?php break; ?>
                                            <?php case ('tuesday'): ?> bg-info <?php break; ?>
                                            <?php case ('wednesday'): ?> bg-warning <?php break; ?>
                                            <?php case ('thursday'): ?> bg-danger <?php break; ?>
                                            <?php case ('friday'): ?> bg-dark <?php break; ?>
                                            <?php case ('saturday'): ?> bg-secondary <?php break; ?>
                                        <?php endswitch; ?> text-white">
                                        <?php switch($lesson->day_of_week):
                                            case ('sunday'): ?> الأحد <?php break; ?>
                                            <?php case ('monday'): ?> الإثنين <?php break; ?>
                                            <?php case ('tuesday'): ?> الثلاثاء <?php break; ?>
                                            <?php case ('wednesday'): ?> الأربعاء <?php break; ?>
                                            <?php case ('thursday'): ?> الخميس <?php break; ?>
                                            <?php case ('friday'): ?> الجمعة <?php break; ?>
                                            <?php case ('saturday'): ?> السبت <?php break; ?>
                                        <?php endswitch; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="time-badge">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($lesson->start_time)->format('H:i')); ?> - 
                                        <?php echo e(\Carbon\Carbon::parse($lesson->end_time)->format('H:i')); ?>

                                    </div>
                                    <?php
                                        $duration = \Carbon\Carbon::parse($lesson->start_time)->diffInMinutes(\Carbon\Carbon::parse($lesson->end_time));
                                    ?>
                                    <br><small class="text-muted"><?php echo e($duration); ?> دقيقة</small>
                                </td>
                                <td>
                                    <div class="students-count">
                                        <i class="fas fa-users me-1"></i>
                                        <?php echo e($lesson->students_count); ?> طالب
                                    </div>
                                    <?php if($lesson->students_count > 0): ?>
                                        <br><small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>نشط
                                        </small>
                                    <?php else: ?>
                                        <br><small class="text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>بدون طلاب
                                        </small>                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $statusColors = [
                                            'scheduled' => 'primary',
                                            'active' => 'success',
                                            'completed' => 'info',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusIcons = [
                                            'scheduled' => 'calendar-alt',
                                            'active' => 'play-circle',
                                            'completed' => 'check-circle',
                                            'cancelled' => 'times-circle'
                                        ];
                                        $statusColor = $statusColors[$lesson->status] ?? 'secondary';
                                        $statusIcon = $statusIcons[$lesson->status] ?? 'question-circle';
                                    ?>
                                    <span class="badge bg-<?php echo e($statusColor); ?> d-flex align-items-center">
                                        <i class="fas fa-<?php echo e($statusIcon); ?> me-1"></i>
                                        <?php echo e($statuses[$lesson->status] ?? $lesson->status); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">                                        <!-- QR Code -->
                                        <a href="<?php echo e(route('admin.lessons.qr.display', $lesson)); ?>" 
                                           class="btn btn-outline-success btn-sm" 
                                           title="QR Code للحضور"
                                           data-bs-toggle="tooltip"
                                           target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        
                                        <!-- عرض -->
                                        <a href="<?php echo e(route('admin.lessons.show', $lesson)); ?>" 
                                           class="btn btn-outline-info btn-sm" 
                                           title="عرض التفاصيل"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- تعديل -->
                                        <?php if(auth()->user()->role === 'admin' || $lesson->teacher_id === auth()->id()): ?>
                                        <a href="<?php echo e(route('admin.lessons.edit', $lesson)); ?>" 
                                           class="btn btn-outline-warning btn-sm" 
                                           title="تعديل"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <!-- حذف -->
                                        <?php if(auth()->user()->role === 'admin' || $lesson->teacher_id === auth()->id()): ?>
                                        <form method="POST" 
                                              action="<?php echo e(route('admin.lessons.destroy', $lesson)); ?>" 
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الدرس؟\\n\\nسيتم حذف جميع سجلات الحضور المرتبطة به.')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm" 
                                                    title="حذف"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- صفحات النتائج -->
            <?php if($lessons->hasPages()): ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    عرض <?php echo e($lessons->firstItem()); ?> إلى <?php echo e($lessons->lastItem()); ?> 
                    من أصل <?php echo e($lessons->total()); ?> درس
                </div>
                <div>
                    <?php echo e($lessons->links()); ?>

                </div>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-3x text-muted"></i>
                </div>                <h5 class="text-muted mb-3">
                    <?php if(request()->hasAny(['search', 'day_filter', 'teacher_filter', 'time_filter', 'students_filter', 'status_filter'])): ?>
                        لا توجد دروس مطابقة للبحث
                    <?php else: ?>
                        لا توجد دروس
                    <?php endif; ?>
                </h5>
                <p class="text-muted mb-4">
                    <?php if(request()->hasAny(['search', 'day_filter', 'teacher_filter', 'time_filter', 'students_filter', 'status_filter'])): ?>
                        جرب تغيير معايير البحث أو إزالة بعض الفلاتر
                    <?php else: ?>
                        ابدأ بإضافة درس جديد لبناء جدولك الدراسي
                    <?php endif; ?>
                </p>
                <div>
                    <?php if(request()->hasAny(['search', 'day_filter', 'teacher_filter', 'time_filter', 'students_filter', 'status_filter'])): ?>
                        <a href="<?php echo e(route('admin.lessons.index')); ?>" class="btn btn-outline-primary me-2">
                            <i class="fas fa-redo me-2"></i>
                            مسح الفلاتر
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('admin.lessons.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة درس جديد
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // تفعيل tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // البحث السريع مع Enter
    document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('filterForm').submit();
        }
    });

    // تأثيرات بصرية للصفوف
    document.querySelectorAll('.lesson-row').forEach(function(row) {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'all 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = 'scale(1)';
        });
    });

    // حفظ حالة الفلاتر في localStorage
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    // تحديث URL بدون إعادة تحميل الصفحة
    function updateURLWithoutReload() {
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newURL);
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/admin/lessons/index.blade.php ENDPATH**/ ?>