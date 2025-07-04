<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BasmahApp - نظام إدارة الحضور الذكي</title>
    <meta name="description" content="نظام إدارة الحضور الذكي باستخدام QR Code للمؤسسات التعليمية">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --border-radius: 20px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero Section */
        .hero-section {
            background: var(--primary-gradient);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            padding: 120px 0;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Buttons */
        .btn-custom {
            border-radius: 50px;
            padding: 15px 40px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            margin: 0.5rem;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-success-custom {
            background: var(--secondary-gradient);
            color: white;
            box-shadow: 0 8px 30px rgba(86, 171, 47, 0.3);
        }

        .btn-success-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(86, 171, 47, 0.4);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid white;
            color: white;
            background: transparent;
        }

        .btn-outline-custom:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .feature-card {
            background: white;
            padding: 3rem 2rem;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-gradient);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        /* How it Works */
        .how-it-works {
            padding: 100px 0;
            background: white;
        }

        .step-card {
            text-align: center;
            padding: 2rem;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
            position: relative;
            z-index: 2;
        }

        .step-line {
            position: absolute;
            top: 30px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #667eea 50%, transparent 100%);
            z-index: 1;
        }

        /* Statistics */
        .stats-section {
            padding: 80px 0;
            background: var(--primary-gradient);
            color: white;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Screenshots */
        .screenshots-section {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .screenshot-card {
            background: white;
            padding: 1rem;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .screenshot-card:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .screenshot-img {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea20, #764ba220);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1rem;
        }

        /* Auth Notice */
        .auth-notice {
            background: var(--danger-gradient);
            color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin: 2rem 0;
            text-align: center;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
        }

        /* Footer */
        .footer-section {
            background: #2c3e50;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-links a {
            color: #bdc3c7;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
        }

        /* Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .feature-card {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="fas fa-graduation-cap me-2"></i>
                BasmahApp
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">الميزات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">كيف يعمل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#screenshots">لقطات الشاشة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">اتصل بنا</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('welcome.simple')); ?>" class="btn btn-outline-primary btn-sm ms-2" title="التبديل إلى الواجهة البسيطة">
                            <i class="fas fa-toggle-off me-1"></i>
                            واجهة بسيطة
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="floating-elements">
            <div class="floating-element" style="width: 60px; height: 60px; top: 20%; left: 10%; animation-delay: 0s;"></div>
            <div class="floating-element" style="width: 40px; height: 40px; top: 60%; left: 80%; animation-delay: 2s;"></div>
            <div class="floating-element" style="width: 80px; height: 80px; top: 80%; left: 20%; animation-delay: 4s;"></div>
        </div>
        
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto">
                    <h1 class="hero-title animate__animated animate__fadeInUp">BasmahApp</h1>
                    <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">
                        نظام إدارة الحضور الذكي باستخدام QR Code للمؤسسات التعليمية
                    </p>
                    <p class="lead mb-5 animate__animated animate__fadeInUp animate__delay-2s">
                        حلول متقدمة لتسجيل الحضور والغياب مع تقارير شاملة وإدارة سهلة
                    </p>

                    <!-- Auth Notice -->
                    <div class="auth-notice animate__animated animate__fadeInUp animate__delay-3s">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>مهم:</strong> المعلمين والإداريين يتم إنشاء حساباتهم من قبل الإدارة فقط - الطلاب فقط يمكنهم التسجيل الذاتي
                    </div>

                    <?php if(auth()->guard()->guest()): ?>
                        <div class="animate__animated animate__fadeInUp animate__delay-4s">
                            <a href="<?php echo e(route('student.register.form')); ?>" class="btn btn-success-custom btn-custom">
                                <i class="fas fa-user-plus me-2"></i>
                                تسجيل طالب جديد
                            </a>
                            <a href="<?php echo e(route('admin.login')); ?>" class="btn btn-outline-custom btn-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                تسجيل الدخول
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="animate__animated animate__fadeInUp animate__delay-4s">
                            <?php if(auth()->user()->role === 'admin'): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary-custom btn-custom">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    لوحة إدارة النظام
                                </a>
                            <?php elseif(auth()->user()->role === 'teacher'): ?>
                                <a href="<?php echo e(route('teacher.dashboard')); ?>" class="btn btn-success-custom btn-custom">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>
                                    لوحة تحكم المعلم
                                </a>
                            <?php elseif(auth()->user()->role === 'student'): ?>
                                <a href="<?php echo e(route('student.dashboard')); ?>" class="btn btn-outline-custom btn-custom">
                                    <i class="fas fa-user-graduate me-2"></i>
                                    لوحة تحكم الطالب
                                </a>
                            <?php endif; ?>
                            
                            <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-custom btn-custom">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>

                        <div class="alert alert-success border-0 mt-4" style="background: rgba(255, 255, 255, 0.2); border-radius: 15px;">
                            <i class="fas fa-check-circle me-2"></i>
                            مرحباً <strong><?php echo e(auth()->user()->name); ?></strong> - <?php echo e(auth()->user()->role === 'admin' ? 'مدير النظام' : 
                                (auth()->user()->role === 'teacher' ? 'معلم' : 'طالب')); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-4 fw-bold mb-4 fade-in-up">ميزات النظام</h2>
                    <p class="lead text-muted fade-in-up">تجربة متكاملة لإدارة الحضور بأحدث التقنيات</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h4 class="mb-3">تسجيل حضور بـ QR Code</h4>
                        <p class="text-muted">تسجيل سريع وآمن للحضور باستخدام رموز QR المتجددة تلقائياً</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> رموز QR متجددة</li>
                            <li><i class="fas fa-check text-success me-2"></i> تسجيل فوري</li>
                            <li><i class="fas fa-check text-success me-2"></i> حماية من التلاعب</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h4 class="mb-3">لوحة تحكم المعلم</h4>
                        <p class="text-muted">إدارة شاملة للدروس والطلاب مع عرض QR Code المباشر</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> إدارة الدروس</li>
                            <li><i class="fas fa-check text-success me-2"></i> عرض QR مباشر</li>
                            <li><i class="fas fa-check text-success me-2"></i> متابعة الحضور</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4 class="mb-3">تقارير وإحصائيات</h4>
                        <p class="text-muted">تقارير مفصلة ورسوم بيانية لمتابعة أداء الحضور</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> تقارير شاملة</li>
                            <li><i class="fas fa-check text-success me-2"></i> رسوم بيانية</li>
                            <li><i class="fas fa-check text-success me-2"></i> تصدير البيانات</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h4 class="mb-3">بوابة الطالب</h4>
                        <p class="text-muted">واجهة سهلة للطلاب لمتابعة حضورهم وتسجيل الدخول</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> تسجيل ذاتي</li>
                            <li><i class="fas fa-check text-success me-2"></i> متابعة الحضور</li>
                            <li><i class="fas fa-check text-success me-2"></i> إشعارات فورية</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="mb-3">أمان وحماية</h4>
                        <p class="text-muted">حماية عالية للبيانات مع تشفير متقدم وصلاحيات محددة</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> تشفير البيانات</li>
                            <li><i class="fas fa-check text-success me-2"></i> صلاحيات محددة</li>
                            <li><i class="fas fa-check text-success me-2"></i> حماية الخصوصية</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h4 class="mb-3">لوحة الإدارة</h4>
                        <p class="text-muted">تحكم كامل في النظام مع إدارة المستخدمين والصلاحيات</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i> إدارة المستخدمين</li>
                            <li><i class="fas fa-check text-success me-2"></i> تحكم بالصلاحيات</li>
                            <li><i class="fas fa-check text-success me-2"></i> مراقبة النظام</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-4 fw-bold mb-4 fade-in-up">كيف يعمل النظام</h2>
                    <p class="lead text-muted fade-in-up">خطوات بسيطة لبدء استخدام النظام</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="step-card fade-in-up">
                        <div class="step-number">1</div>
                        <div class="step-line d-none d-lg-block"></div>
                        <h5>التسجيل</h5>
                        <p class="text-muted">الطلاب يسجلون بأنفسهم، المعلمين ينشئهم المدير</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="step-card fade-in-up">
                        <div class="step-number">2</div>
                        <div class="step-line d-none d-lg-block"></div>
                        <h5>إنشاء الدروس</h5>
                        <p class="text-muted">المعلم ينشئ الدروس ويضيف الطلاب المسجلين</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="step-card fade-in-up">
                        <div class="step-number">3</div>
                        <div class="step-line d-none d-lg-block"></div>
                        <h5>توليد QR Code</h5>
                        <p class="text-muted">المعلم يولد رمز QR للدرس أثناء وقت الحضور</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="step-card fade-in-up">
                        <div class="step-number">4</div>
                        <h5>تسجيل الحضور</h5>
                        <p class="text-muted">الطلاب يمسحون الرمز لتسجيل الحضور تلقائياً</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-4 fw-bold mb-4 fade-in-up">إحصائيات النظام</h2>
                    <p class="lead fade-in-up" style="opacity: 0.9;">أرقام تتحدث عن كفاءة النظام</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card fade-in-up">
                        <span class="stat-number" data-count="99">0</span>
                        <div class="stat-label">نسبة الدقة %</div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card fade-in-up">
                        <span class="stat-number" data-count="15">0</span>
                        <div class="stat-label">ثانية متوسط التسجيل</div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card fade-in-up">
                        <span class="stat-number" data-count="100">0</span>
                        <div class="stat-label">نسبة الأمان %</div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card fade-in-up">
                        <span class="stat-number" data-count="24">0</span>
                        <div class="stat-label">ساعة متواصلة</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Screenshots -->
    <section id="screenshots" class="screenshots-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-4 fw-bold mb-4 fade-in-up">لقطات من النظام</h2>
                    <p class="lead text-muted fade-in-up">تصفح واجهات النظام المختلفة</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="screenshot-card fade-in-up">
                        <div class="screenshot-img">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <h5>لوحة تحكم المدير</h5>
                        <p class="text-muted">إدارة شاملة للنظام والمستخدمين</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="screenshot-card fade-in-up">
                        <div class="screenshot-img">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h5>عرض QR Code</h5>
                        <p class="text-muted">واجهة المعلم لعرض رمز QR للطلاب</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="screenshot-card fade-in-up">
                        <div class="screenshot-img">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5>التقارير والإحصائيات</h5>
                        <p class="text-muted">تقارير مفصلة ورسوم بيانية تفاعلية</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="screenshot-card fade-in-up">
                        <div class="screenshot-img">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h5>لوحة الطالب</h5>
                        <p class="text-muted">واجهة بسيطة لمتابعة الحضور</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="screenshot-card fade-in-up">
                        <div class="screenshot-img">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h5>لوحة المعلم</h5>
                        <p class="text-muted">إدارة الدروس والطلاب بسهولة</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="screenshot-card fade-in-up">
                        <div class="screenshot-img">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5>تطبيق الهاتف</h5>
                        <p class="text-muted">متوافق تماماً مع الأجهزة المحمولة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 fw-bold mb-4 fade-in-up">تواصل معنا</h2>
                    <p class="lead fade-in-up">لديك استفسار أو تحتاج مساعدة؟ نحن هنا لخدمتك</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5>البريد الإلكتروني</h5>
                    <a href="mailto:osaidhaj03@gmail.com" class="text-decoration-none">osaidhaj03@gmail.com</a>
                </div>
                
                <div class="col-lg-4 text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-code"></i>
                    </div>
                    <h5>المطور</h5>
                    <p class="mb-0">أسيد صلاح أبو الحاج</p>
                </div>
                
                <div class="col-lg-4 text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5>النظام</h5>
                    <p class="mb-0">BasmahApp - إدارة الحضور الذكي</p>
                </div>
            </div>
            
            <hr class="my-5" style="border-color: #34495e;">
            
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">
                        <?php echo e(now()->format('Y')); ?> &copy; جميع الحقوق محفوظة - 
                        <strong>BasmahApp</strong> - 
                        تطوير أسيد صلاح أبو الحاج
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in-up').forEach(el => {
            observer.observe(el);
        });

        // Animated counters
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-count'));
            const increment = target / 50;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 40);
        }

        // Start counters when stats section is visible
        const statsObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('[data-count]');
                    counters.forEach(counter => {
                        animateCounter(counter);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }

        // Session messages
        <?php if(session('success')): ?>
            setTimeout(() => {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }, 1000);
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            setTimeout(() => {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }, 1000);
        <?php endif; ?>
    </script>
</body>
</html>
<?php /**PATH C:\Users\osaidsalah002\Documents\BasmahAnwarAlOlmaa-1\resources\views/welcome-premium.blade.php ENDPATH**/ ?>