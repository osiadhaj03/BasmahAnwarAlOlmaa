<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'لوحة التحكم'); ?> - BasmahApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 15px 20px;
            border-radius: 10px;
            margin: 5px 10px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .main-content {
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .badge {
            font-size: 0.8em;
            padding: 8px 12px;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="text-center py-4">
                    <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                    <h4>BasmahApp</h4>
                    <small><?php echo e(auth()->user()->name); ?></small>
                    <br>
                    <small class="badge bg-light text-dark mt-2">
                        <?php echo e(auth()->user()->role === 'admin' ? 'مدير' : 'معلم'); ?>

                    </small>
                </div>

                <nav class="nav flex-column">
                    <?php if(auth()->user()->role === 'admin'): ?>
                        <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('admin.dashboard')); ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            الرئيسية
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('admin.users.index')); ?>">
                            <i class="fas fa-users me-2"></i>
                            المستخدمين
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('admin.lessons.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('admin.lessons.index')); ?>">
                            <i class="fas fa-book me-2"></i>
                            الدروس
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('admin.attendances.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('admin.attendances.index')); ?>">
                            <i class="fas fa-clipboard-check me-2"></i>
                            مراجعة الحضور
                        </a>
                    <?php elseif(auth()->user()->role === 'teacher'): ?>
                        <a class="nav-link <?php echo e(request()->routeIs('teacher.dashboard') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('teacher.dashboard')); ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            لوحة التحكم
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('teacher.lessons.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('teacher.lessons.index')); ?>">
                            <i class="fas fa-book me-2"></i>
                            إدارة دروسي
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('teacher.attendances.*') ? 'active' : ''); ?>" 
                           href="<?php echo e(route('teacher.attendances.index')); ?>">
                            <i class="fas fa-clipboard-check me-2"></i>
                            مراجعة الحضور والغياب
                        </a>
                    <?php endif; ?>
                </nav>

                <div class="mt-auto p-3">
                    <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
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

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/layouts/admin.blade.php ENDPATH**/ ?>