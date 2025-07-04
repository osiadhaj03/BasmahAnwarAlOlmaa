<?php $__env->startSection('title', 'لوحة الطالب'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Statistics Column -->
    <div class="col-lg-4">
        <div class="floating-stats">
            <!-- Quick Stats -->
            <div class="card stats-card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie me-2"></i>
                        إحصائياتي
                    </h5>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="border-end border-light">
                                <h3 class="mb-0"><?php echo e($totalLessons); ?></h3>
                                <small class="opacity-75">دروسي</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0"><?php echo e($attendanceRate); ?>%</h3>
                            <small class="opacity-75">معدل الحضور</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Breakdown -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-clipboard-list me-2"></i>
                        تفصيل الحضور
                    </h6>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                حاضر
                            </span>
                            <span class="badge bg-success"><?php echo e($presentCount); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning">
                                <i class="fas fa-clock me-1"></i>
                                متأخر
                            </span>
                            <span class="badge bg-warning"><?php echo e($lateCount); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger">
                                <i class="fas fa-times-circle me-1"></i>
                                غائب
                            </span>
                            <span class="badge bg-danger"><?php echo e($absentCount); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance -->
            <?php if($recentAttendances->count() > 0): ?>
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-history me-2"></i>
                        آخر سجلات الحضور
                    </h6>
                    <div class="mt-3">
                        <?php $__currentLoopData = $recentAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="attendance-item p-2 rounded mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="fw-bold"><?php echo e($attendance->lesson->name ?? $attendance->lesson->subject); ?></small>
                                    <br>
                                    <small class="text-muted"><?php echo e($attendance->date); ?></small>
                                </div>
                                <div>
                                    <?php if($attendance->status === 'present'): ?>
                                        <span class="badge bg-success">حاضر</span>
                                    <?php elseif($attendance->status === 'late'): ?>
                                        <span class="badge bg-warning">متأخر</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">غائب</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>    <!-- Lessons Column -->
    <div class="col-lg-8">
        <!-- QR Code Scanner Section -->
        <div class="card mb-4 border-success">
            <div class="card-body text-center">
                <h5 class="card-title text-success">
                    <i class="fas fa-qrcode me-2"></i>
                    مسح QR Code للحضور
                </h5>
                <p class="text-muted mb-3">
                    امسح الكود الموجود في قاعة الدرس لتسجيل حضورك بسرعة
                </p>
                <a href="<?php echo e(route('student.qr.scanner')); ?>" class="btn btn-success btn-lg">
                    <i class="fas fa-camera me-2"></i>
                    فتح ماسح QR Code
                </a>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        يجب مسح الكود خلال أول 15 دقيقة من بداية الدرس
                    </small>
                </div>
            </div>
        </div>

        <!-- Today's Lessons with Check-in -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title text-primary">
                    <i class="fas fa-calendar-day me-2"></i>
                    دروس اليوم - تسجيل الحضور
                </h5>
                
                <!-- Debug Information -->
                <div class="alert alert-info mb-3">
                    <small>
                        <strong>معلومات التصحيح:</strong><br>
                        التاريخ: <?php echo e($today->format('Y-m-d')); ?><br>
                        اليوم: <?php echo e($currentDayOfWeek); ?><br>
                        عدد الدروس اليوم: <?php echo e($todayLessons->count()); ?><br>
                        إجمالي الدروس: <?php echo e($lessons->count()); ?>

                    </small>
                </div>
                
                <?php if($todayLessons->count() > 0): ?>
                <div class="row mt-3">
                    <?php $__currentLoopData = $todayLessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                    <?php
                        $hasCheckedInToday = $lesson->attendances->where('date', \Carbon\Carbon::today())->count() > 0;
                        $currentTime = \Carbon\Carbon::now();
                        
                        // إصلاح تحويل الوقت
                        $startTime = null;
                        $endTime = null;
                        
                        if ($lesson->start_time) {
                            try {
                                // محاولة تحويل من datetime كامل أولاً
                                $startTime = \Carbon\Carbon::parse($lesson->start_time);
                            } catch (\Exception $e) {
                                try {
                                    // إذا فشل، محاولة تحويل من time فقط
                                    $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $lesson->start_time);
                                } catch (\Exception $e2) {
                                    $startTime = null;
                                }
                            }
                        }
                        
                        if ($lesson->end_time) {
                            try {
                                $endTime = \Carbon\Carbon::parse($lesson->end_time);
                            } catch (\Exception $e) {
                                try {
                                    $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $lesson->end_time);
                                } catch (\Exception $e2) {
                                    $endTime = null;
                                }
                            }
                        }
                    ?>
                    <div class="col-md-6 mb-3">
                        <div class="card lesson-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="card-title mb-1"><?php echo e($lesson->name ?? $lesson->subject); ?></h6>                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            <?php echo e($lesson->teacher->name); ?>

                                        </small>
                                    </div>
                                    <?php if($startTime): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo e($startTime->format('H:i')); ?>

                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Lesson Debug Info -->
                                <div class="alert alert-light mb-3">
                                    <small>
                                        يوم الدرس: <?php echo e($lesson->day_of_week); ?><br>
                                        الوقت الحالي: <?php echo e($currentTime->format('H:i')); ?><br>
                                        <?php if($startTime && $endTime): ?>
                                            بداية: <?php echo e($startTime->format('H:i')); ?> - نهاية: <?php echo e($endTime->format('H:i')); ?>

                                        <?php endif; ?>
                                    </small>
                                </div>
                                
                                <?php if($lesson->description): ?>
                                <p class="card-text small text-muted mb-3"><?php echo e(Str::limit($lesson->description, 80)); ?></p>
                                <?php endif; ?>
                                
                                <div class="d-grid">
                                    <?php if($hasCheckedInToday): ?>
                                        <button class="btn btn-outline-success disabled">
                                            <i class="fas fa-check me-2"></i>
                                            تم تسجيل الحضور
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('student.checkin', ['lesson' => $lesson->id, 'student' => auth()->id()])); ?>" 
                                           class="btn btn-check-in text-white">
                                            <i class="fas fa-sign-in-alt me-2"></i>
                                            تسجيل الحضور
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    لا توجد دروس مجدولة لهذا اليوم (<?php echo e($currentDayOfWeek); ?>)
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- All My Lessons -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-book me-2"></i>
                    جميع دروسي (<?php echo e($totalLessons); ?>)
                </h5>
                <?php if($lessons->count() > 0): ?>
                <div class="row mt-3">
                    <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $lessonAttendances = $lesson->attendances;
                        $lessonTotal = $lessonAttendances->count();
                        $lessonPresent = $lessonAttendances->where('status', 'present')->count();
                        $lessonRate = $lessonTotal > 0 ? round(($lessonPresent / $lessonTotal) * 100, 1) : 0;
                    ?>
                    <div class="col-md-6 mb-3">
                        <div class="card lesson-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-1"><?php echo e($lesson->name ?? $lesson->subject); ?></h6>
                                    <span class="status-badge bg-light text-dark">
                                        <?php echo e($lessonRate); ?>%
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>
                                        <?php echo e($lesson->teacher->name); ?>                                    </small>
                                    <?php if($lesson->start_time): ?>
                                    <small class="text-muted d-block">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($lesson->start_time)->format('H:i')); ?>

                                    </small>
                                    <?php endif; ?>
                                </div>

                                <?php if($lesson->description): ?>
                                <p class="card-text small text-muted mb-3"><?php echo e(Str::limit($lesson->description, 60)); ?></p>
                                <?php endif; ?>

                                <div class="row text-center small">
                                    <div class="col-4">
                                        <div class="text-primary fw-bold"><?php echo e($lessonTotal); ?></div>
                                        <div class="text-muted">إجمالي</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-success fw-bold"><?php echo e($lessonPresent); ?></div>
                                        <div class="text-muted">حضور</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-warning fw-bold"><?php echo e($lessonAttendances->where('status', 'late')->count()); ?></div>
                                        <div class="text-muted">تأخير</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد دروس مسجل بها</h5>
                    <p class="text-muted">يرجى التواصل مع المعلم أو الإدارة للتسجيل في الدروس</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// تحديث الوقت كل ثانية
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('ar-SA', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
    const timeElement = document.querySelector('.header small:last-child');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

setInterval(updateTime, 1000);

// تأثيرات الحركة للبطاقات
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
        card.style.animation = 'fadeInUp 0.6s ease forwards';
    });
});

// CSS للحركة
const style = document.createElement('style');
style.textContent = `
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
`;
document.head.appendChild(style);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahAnwarAlOlmaa-1\resources\views/student/dashboard.blade.php ENDPATH**/ ?>