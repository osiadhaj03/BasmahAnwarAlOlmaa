<?php $__env->startSection('title', 'إضافة درس جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">إضافة درس جديد</h1>
    <a href="<?php echo e(route('admin.lessons.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        العودة للدروس
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.lessons.store')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="subject" class="form-label">المادة <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="subject" 
                           name="subject" 
                           value="<?php echo e(old('subject')); ?>" 
                           required>
                    <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="teacher_id" class="form-label">المعلم <span class="text-danger">*</span></label>
                    <select class="form-control <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="teacher_id" 
                            name="teacher_id" 
                            required
                            <?php if(auth()->user()->role === 'teacher'): ?> disabled <?php endif; ?>>
                        <option value="">اختر المعلم</option>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($teacher->id); ?>" 
                                    <?php if(old('teacher_id') == $teacher->id || (auth()->user()->role === 'teacher' && auth()->id() == $teacher->id)): ?> selected <?php endif; ?>>
                                <?php echo e($teacher->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if(auth()->user()->role === 'teacher'): ?>
                        <input type="hidden" name="teacher_id" value="<?php echo e(auth()->id()); ?>">
                    <?php endif; ?>
                    <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="day_of_week" class="form-label">يوم الأسبوع <span class="text-danger">*</span></label>
                    <select class="form-control <?php $__errorArgs = ['day_of_week'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="day_of_week" 
                            name="day_of_week" 
                            required>
                        <option value="">اختر اليوم</option>
                        <option value="sunday" <?php if(old('day_of_week') == 'sunday'): ?> selected <?php endif; ?>>الأحد</option>
                        <option value="monday" <?php if(old('day_of_week') == 'monday'): ?> selected <?php endif; ?>>الإثنين</option>
                        <option value="tuesday" <?php if(old('day_of_week') == 'tuesday'): ?> selected <?php endif; ?>>الثلاثاء</option>
                        <option value="wednesday" <?php if(old('day_of_week') == 'wednesday'): ?> selected <?php endif; ?>>الأربعاء</option>
                        <option value="thursday" <?php if(old('day_of_week') == 'thursday'): ?> selected <?php endif; ?>>الخميس</option>
                        <option value="friday" <?php if(old('day_of_week') == 'friday'): ?> selected <?php endif; ?>>الجمعة</option>
                        <option value="saturday" <?php if(old('day_of_week') == 'saturday'): ?> selected <?php endif; ?>>السبت</option>
                    </select>
                    <?php $__errorArgs = ['day_of_week'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="start_time" class="form-label">وقت البداية <span class="text-danger">*</span></label>
                    <input type="time" 
                           class="form-control <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="start_time" 
                           name="start_time" 
                           value="<?php echo e(old('start_time')); ?>" 
                           required>
                    <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="end_time" class="form-label">وقت النهاية <span class="text-danger">*</span></label>
                    <input type="time" 
                           class="form-control <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="end_time" 
                           name="end_time" 
                           value="<?php echo e(old('end_time')); ?>" 
                           required>
                    <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">حالة الدرس <span class="text-danger">*</span></label>
                    <select class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="">اختر حالة الدرس</option>
                        <option value="scheduled" <?php if(old('status') == 'scheduled'): ?> selected <?php endif; ?>>مجدول</option>
                        <option value="active" <?php if(old('status') == 'active'): ?> selected <?php endif; ?>>نشط</option>
                        <option value="completed" <?php if(old('status') == 'completed'): ?> selected <?php endif; ?>>مكتمل</option>
                        <option value="cancelled" <?php if(old('status') == 'cancelled'): ?> selected <?php endif; ?>>ملغي</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">وصف الدرس</label>
                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="description" 
                          name="description" 
                          rows="4" 
                          placeholder="أدخل وصف تفصيلي للدرس..."><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label for="students" class="form-label">الطلاب</label>
                <div class="row">
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="students[]" 
                                       value="<?php echo e($student->id); ?>" 
                                       id="student_<?php echo e($student->id); ?>"
                                       <?php if(is_array(old('students')) && in_array($student->id, old('students'))): ?> checked <?php endif; ?>>
                                <label class="form-check-label" for="student_<?php echo e($student->id); ?>">
                                    <?php echo e($student->name); ?>

                                </label>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['students'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('admin.lessons.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    حفظ الدرس
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/admin/lessons/create.blade.php ENDPATH**/ ?>