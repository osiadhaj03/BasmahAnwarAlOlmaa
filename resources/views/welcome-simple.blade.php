<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BasmahApp - نظام إدارة الحضور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Toggle Button */
        .toggle-view-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            color: #667eea;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .toggle-view-btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .hero-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            max-width: 800px;
            margin: 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(45deg, #667eea, #764ba2);
        }
        
        .btn-custom {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            margin: 0.5rem;
        }
        
        .btn-primary-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-success-custom {
            background: linear-gradient(45deg, #56ab2f, #a8e6cf);
            color: white;
        }
        
        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(86, 171, 47, 0.4);
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            transform: scale(1.1) rotate(360deg);
        }
        
        .text-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .auth-notice {
            background: linear-gradient(45deg, #ff6b6b, #ffa726);
            color: white;
            padding: 1rem;
            border-radius: 15px;
            margin: 1rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-notice::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .auth-notice:hover::before {
            left: 100%;
        }

        .feature-card {
            transition: all 0.3s ease;
            padding: 1rem;
            border-radius: 15px;
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-5px);
        }

        .footer-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Pulse Animation */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>
    <!-- Toggle View Button -->
    <a href="{{ route('welcome.premium') }}" class="toggle-view-btn" title="التبديل إلى الواجهة المتطورة">
        <i class="fas fa-toggle-on me-2"></i>
        واجهة متطورة
    </a>

    <div class="hero-section">
        <div class="hero-card text-center">
            <div class="mb-4">
                <div class="feature-icon mx-auto mb-3 floating pulse" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1 class="display-4 fw-bold text-gradient mb-3">BasmahApp</h1>
                <p class="lead text-muted mb-4">نظام إدارة الحضور الذكي للمؤسسات التعليمية</p>
                
                <!-- Quick Features Badge -->
                <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                    <span class="badge bg-primary px-3 py-2"><i class="fas fa-qrcode me-1"></i> QR Code</span>
                    <span class="badge bg-success px-3 py-2"><i class="fas fa-shield-alt me-1"></i> آمن</span>
                    <span class="badge bg-info px-3 py-2"><i class="fas fa-mobile me-1"></i> متجاوب</span>
                    <span class="badge bg-warning px-3 py-2"><i class="fas fa-chart-line me-1"></i> تقارير</span>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h5>حضور بـ QR Code</h5>
                        <p class="text-muted small">تسجيل حضور سريع وآمن</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5>تقارير متقدمة</h5>
                        <p class="text-muted small">إحصائيات شاملة ومفصلة</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>أمان عالي</h5>
                        <p class="text-muted small">حماية البيانات والخصوصية</p>
                    </div>
                </div>
            </div>

            <!-- تحديد نوع المستخدم -->
            <div class="auth-notice">
                <i class="fas fa-info-circle me-2"></i>
                <strong>مهم:</strong> المعلمين والإداريين يتم إنشاء حساباتهم من قبل الإدارة فقط - الطلاب فقط يمكنهم التسجيل الذاتي
            </div>

            @guest
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mb-4">
                    <!-- تسجيل طالب جديد -->
                    <a href="{{ route('student.register.form') }}" class="btn btn-success-custom btn-custom">
                        <i class="fas fa-user-plus me-2"></i>
                        تسجيل طالب جديد
                    </a>
                    
                    <!-- تسجيل الدخول -->
                    <a href="{{ route('admin.login') }}" class="btn btn-primary-custom btn-custom">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        تسجيل الدخول
                    </a>
                </div>

                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-users me-2"></i>
                        للمعلمين والإداريين: يرجى استخدام البيانات المقدمة من الإدارة
                    </small>
                </div>
            @else
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mb-4">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom btn-custom">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            لوحة إدارة النظام
                        </a>
                    @elseif(auth()->user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-success-custom btn-custom">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            لوحة تحكم المعلم
                        </a>
                    @elseif(auth()->user()->role === 'student')
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-custom btn-custom">
                            <i class="fas fa-user-graduate me-2"></i>
                            لوحة تحكم الطالب
                        </a>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-custom btn-custom">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>

                <div class="alert alert-success border-0" style="background: rgba(86, 171, 47, 0.1); border-radius: 15px;">
                    <i class="fas fa-check-circle me-2"></i>
                    مرحباً <strong>{{ auth()->user()->name }}</strong> - {{ 
                        auth()->user()->role === 'admin' ? 'مدير النظام' : 
                        (auth()->user()->role === 'teacher' ? 'معلم' : 'طالب') 
                    }}
                </div>
            @endguest
            
            <!-- Quick Stats -->
            <div class="row g-3 my-4">
                <div class="col-6 col-md-3">
                    <div class="text-center p-2">
                        <div class="h4 text-primary mb-0">3</div>
                        <small class="text-muted">أنواع مستخدمين</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-2">
                        <div class="h4 text-success mb-0">15+</div>
                        <small class="text-muted">ميزة</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-2">
                        <div class="h4 text-warning mb-0">100%</div>
                        <small class="text-muted">آمان</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-2">
                        <div class="h4 text-info mb-0">24/7</div>
                        <small class="text-muted">متاح</small>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            
            <div class="footer-info">
                <div class="text-muted small">
                    <div class="row">
                        <div class="col-md-6 text-md-start text-center mb-3 mb-md-0">
                            <p class="mb-1">
                                <i class="fas fa-code me-2 text-primary"></i>
                                <strong>نظام BasmahApp</strong> - إدارة الحضور الذكي
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-calendar me-2 text-success"></i>
                                {{ now()->format('Y') }} &copy; جميع الحقوق محفوظة
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end text-center">
                            <p class="mb-1">
                                <i class="fas fa-user me-2 text-info"></i>
                                <strong>المطور:</strong> أسيد صلاح أبو الحاج
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-envelope me-2 text-warning"></i>
                                <a href="mailto:osaidhaj03@gmail.com" class="text-decoration-none" style="color: #667eea;">
                                    osaidhaj03@gmail.com
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Toggle Hint -->
            <div class="mt-3">
                <small class="text-muted">
                    <i class="fas fa-eye me-1"></i>
                    يمكنك التبديل بين الواجهة البسيطة والمتطورة من الزر في الأعلى
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Session Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create styled notification instead of simple alert
                const notification = document.createElement('div');
                notification.className = 'alert alert-success position-fixed';
                notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; border-radius: 15px; box-shadow: 0 10px 30px rgba(86, 171, 47, 0.3);';
                notification.innerHTML = '<i class="fas fa-check-circle me-2"></i>{{ session('success') }}';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            });
        </script>
    @endif
    
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create styled notification instead of simple alert
                const notification = document.createElement('div');
                notification.className = 'alert alert-danger position-fixed';
                notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; border-radius: 15px; box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);';
                notification.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            });
        </script>
    @endif

    <!-- View Switch Animation -->
    <script>
        document.querySelector('.toggle-view-btn').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add loading animation
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحميل...';
            this.style.pointerEvents = 'none';
            
            // Navigate after short delay for visual feedback
            setTimeout(() => {
                window.location.href = this.href;
            }, 500);
        });

        // Add hover effect to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(102, 126, 234, 0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.background = 'transparent';
            });
        });
    </script>
</body>
</html>
