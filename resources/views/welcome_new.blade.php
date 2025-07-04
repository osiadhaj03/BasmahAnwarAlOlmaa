<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BasmahApp - نظام إدارة الحضور الذكي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-10">
                <div class="welcome-card p-5">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <div class="feature-icon bg-primary text-white mx-auto mb-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h1 class="display-4 fw-bold text-primary mb-3">BasmahApp</h1>
                        <p class="lead text-muted">نظام إدارة الحضور الذكي</p>
                    </div>

                    <!-- Features -->
                    <div class="row text-center mb-5">
                        <div class="col-md-4 mb-4">
                            <div class="feature-icon bg-success text-white">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h5>تسجيل الحضور</h5>
                            <p class="text-muted">تسجيل سريع وسهل للحضور والغياب</p>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="feature-icon bg-info text-white">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h5>التقارير والإحصائيات</h5>
                            <p class="text-muted">تقارير مفصلة عن الحضور والأداء</p>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="feature-icon bg-warning text-white">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h5>التنبيهات الذكية</h5>
                            <p class="text-muted">تنبيهات فورية للغياب والتأخير</p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="row justify-content-center">
                        @auth
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                                <div class="col-md-5 mb-3">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-custom w-100">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        لوحة الإدارة
                                    </a>
                                </div>
                            @endif
                            
                            @if(auth()->user()->role === 'student')
                                <div class="col-md-5 mb-3">
                                    <a href="{{ route('student.dashboard') }}" class="btn btn-success btn-custom w-100">
                                        <i class="fas fa-user-graduate me-2"></i>
                                        لوحة الطالب
                                    </a>
                                </div>
                            @endif
                            
                            <div class="col-md-5 mb-3">
                                <form method="POST" action="{{ route('admin.logout') }}" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-custom w-100">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="col-md-5 mb-3">
                                <a href="{{ route('admin.login') }}" class="btn btn-primary btn-custom w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    تسجيل الدخول للإدارة
                                </a>
                            </div>
                            <div class="col-md-5 mb-3">
                                <a href="{{ route('admin.login') }}" class="btn btn-success btn-custom w-100">
                                    <i class="fas fa-user-graduate me-2"></i>
                                    تسجيل دخول الطلاب
                                </a>
                            </div>
                        @endauth
                    </div>

                    @auth
                    <!-- User Info -->
                    <div class="text-center mt-4 pt-4 border-top">
                        <p class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            مرحباً <strong>{{ auth()->user()->name }}</strong>
                            <span class="badge bg-secondary ms-2">
                                {{ auth()->user()->role === 'admin' ? 'مدير' : (auth()->user()->role === 'teacher' ? 'معلم' : 'طالب') }}
                            </span>
                        </p>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
