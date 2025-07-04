<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'لوحة الطالب'); ?> - BasmahApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin: 20px;
            min-height: calc(100vh - 40px);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .lesson-card {
            border-left: 5px solid #667eea;
        }
        .btn-check-in {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
        }
        .btn-check-in:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }
        .status-badge {
            font-size: 0.9em;
            padding: 8px 15px;
            border-radius: 20px;
        }
        .attendance-item {
            border-left: 4px solid #dee2e6;
            transition: all 0.3s ease;
        }
        .attendance-item:hover {
            border-left-color: #667eea;
            background-color: #f8f9fa;
        }
        .floating-stats {
            position: sticky;
            top: 20px;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="main-container">        <!-- Header -->
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-user-graduate fa-2x me-3"></i>
                        <div>
                            <h3 class="mb-0">مرحباً <?php echo e(auth()->user()->name); ?></h3>
                            <small class="opacity-75">طالب في BasmahApp</small>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="mb-2">
                        <small class="opacity-75"><?php echo e(now()->locale('ar')->isoFormat('dddd، D MMMM YYYY')); ?></small>
                    </div>
                    <div class="mb-3">
                        <small class="opacity-75"><?php echo e(now()->format('H:i')); ?></small>
                    </div>
                    <!-- زر تسجيل الخروج -->
                    <div>
                        <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-light btn-sm" 
                                    onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo e(session('info')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>

        <!-- Footer -->
        <div class="text-center py-3 border-top">
            <small class="text-muted">
                <i class="fas fa-graduation-cap me-1"></i>
                BasmahApp - نظام إدارة الحضور الذكي
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\osaidsalah002\Documents\BasmahAnwarAlOlmaa-1\resources\views/layouts/student.blade.php ENDPATH**/ ?>