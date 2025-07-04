<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BasmahApp - نظام إدارة الحضور الذكي الأكثر تطوراً</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap');
        
        * {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: #0a0a0a;
        }        /* Hero Background with Animation */
        .hero-section {
            background: linear-gradient(-45deg, #1e3c72, #2a5298, #0066cc, #004499);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(30, 60, 114, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(42, 82, 152, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(0, 102, 204, 0.4) 0%, transparent 50%);
            animation: pulse 4s ease-in-out infinite alternate;
        }

        @keyframes pulse {
            0% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .floating-elements::before {
            background: rgba(255, 255, 255, 0.1);
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .floating-elements::after {
            background: rgba(255, 255, 255, 0.05);
            bottom: -150px;
            left: -150px;
            animation-delay: -10s;
        }

        @keyframes float {
            0% { transform: rotate(0deg) translateX(50px) rotate(0deg); }
            100% { transform: rotate(360deg) translateX(50px) rotate(-360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            padding: 2rem;
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            padding: 3rem;
            max-width: 1200px;
            margin: 2rem auto;
            color: white;
        }        .main-logo {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #1e3c72, #2a5298, #0066cc);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 2rem;
            animation: logoSpin 10s linear infinite, logoPulse 2s ease-in-out infinite alternate;
            box-shadow: 0 20px 40px rgba(30, 60, 114, 0.4);
        }

        @keyframes logoSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes logoPulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }        .main-title {
            font-size: 4rem;
            font-weight: 900;
            background: linear-gradient(45deg, #fff, #87ceeb, #1e3c72);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 1rem;
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% { text-shadow: 0 0 20px rgba(255, 255, 255, 0.5); }
            100% { text-shadow: 0 0 40px rgba(255, 255, 255, 0.8), 0 0 60px rgba(30, 60, 114, 0.6); }
        }

        .main-subtitle {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            margin-bottom: 3rem;
            font-weight: 400;
        }

        /* Advanced Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 4rem 0;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .feature-card:hover::before {
            left: 100%;
        }        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 60px rgba(30, 60, 114, 0.4);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
            position: relative;
            z-index: 2;
            animation: iconFloat 3s ease-in-out infinite alternate;
        }

        @keyframes iconFloat {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-10px); }
        }

        .feature-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }

        .feature-description {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
            font-size: 1rem;
        }

        /* Advanced Buttons */
        .btn-custom {
            border-radius: 50px;
            padding: 15px 35px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.4s ease;
            border: none;
            margin: 0.5rem;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }        .btn-primary-custom {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            box-shadow: 0 10px 30px rgba(30, 60, 114, 0.5);
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #2a5298, #0066cc);
            transition: left 0.4s ease;
            z-index: -1;
        }

        .btn-primary-custom:hover::before {
            left: 0;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(30, 60, 114, 0.7);
            color: white;
        }

        .btn-success-custom {
            background: linear-gradient(45deg, #0066cc, #87ceeb);
            color: white;
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.5);
        }

        .btn-success-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 102, 204, 0.7);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            background: transparent;
        }

        .btn-outline-custom:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateY(-3px);
            border-color: white;
        }

        /* System Stats */
        .stats-section {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 20px;
            padding: 3rem;
            margin: 3rem 0;
            backdrop-filter: blur(15px);
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(45deg, #1e3c72, #87ceeb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }        /* Auth Notice */
        .auth-notice {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            padding: 1.5rem;
            border-radius: 20px;
            margin: 2rem 0;
            text-align: center;
            box-shadow: 0 15px 35px rgba(30, 60, 114, 0.4);
            animation: noticeGlow 2s ease-in-out infinite alternate;
        }

        @keyframes noticeGlow {
            0% { box-shadow: 0 15px 35px rgba(30, 60, 114, 0.4); }
            100% { box-shadow: 0 15px 35px rgba(30, 60, 114, 0.7); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-title { font-size: 2.5rem; }
            .main-subtitle { font-size: 1.2rem; }
            .features-grid { grid-template-columns: 1fr; }
            .hero-card { padding: 2rem; margin: 1rem; }
        }

        /* Special Effects */
        .particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0.7;
            animation: particleFloat 15s infinite linear;
        }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.7; }
            90% { opacity: 0.7; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }

        .technology-badges {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .tech-badge {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .tech-badge:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
    </style>
</head>
<body>    <!-- Floating Particles -->
    <div class="particle" style="top: 10%; left: 10%; width: 4px; height: 4px; background: #1e3c72; animation-delay: 0s;"></div>
    <div class="particle" style="top: 20%; left: 80%; width: 6px; height: 6px; background: #2a5298; animation-delay: 2s;"></div>
    <div class="particle" style="top: 60%; left: 20%; width: 8px; height: 8px; background: #0066cc; animation-delay: 4s;"></div>
    <div class="particle" style="top: 80%; left: 90%; width: 5px; height: 5px; background: #87ceeb; animation-delay: 6s;"></div>

    <div class="hero-section">
        <div class="floating-elements"></div>
        
        <div class="hero-content">
            <div class="hero-card" data-aos="fade-up" data-aos-duration="1000">
                
                <!-- Main Logo & Title -->
                <div class="text-center mb-5">
                    <div class="main-logo" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h1 class="main-title" data-aos="fade-up" data-aos-delay="400">BasmahApp</h1>
                    <p class="main-subtitle" data-aos="fade-up" data-aos-delay="600">
                        نظام إدارة الحضور الذكي الأكثر تطوراً في المنطقة العربية
                    </p>
                    
                    <!-- Technology Badges -->
                    <div class="technology-badges" data-aos="fade-up" data-aos-delay="800">
                        <span class="tech-badge"><i class="fab fa-laravel me-1"></i> Laravel 10</span>
                        <span class="tech-badge"><i class="fas fa-qrcode me-1"></i> QR Code Technology</span>
                        <span class="tech-badge"><i class="fas fa-mobile-alt me-1"></i> Mobile Responsive</span>
                        <span class="tech-badge"><i class="fas fa-shield-alt me-1"></i> Security First</span>
                        <span class="tech-badge"><i class="fas fa-chart-line me-1"></i> Advanced Analytics</span>
                    </div>
                </div>

                <!-- System Statistics -->
                <div class="stats-section" data-aos="fade-up" data-aos-delay="1000">
                    <div class="row">
                        <div class="col-md-3 stat-item">
                            <span class="stat-number">3</span>
                            <div class="stat-label">أنواع مستخدمين</div>
                        </div>
                        <div class="col-md-3 stat-item">
                            <span class="stat-number">15+</span>
                            <div class="stat-label">ميزة متقدمة</div>
                        </div>
                        <div class="col-md-3 stat-item">
                            <span class="stat-number">100%</span>
                            <div class="stat-label">أمان وحماية</div>
                        </div>
                        <div class="col-md-3 stat-item">
                            <span class="stat-number">24/7</span>
                            <div class="stat-label">متاح دائماً</div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Features -->
                <div class="features-grid" data-aos="fade-up" data-aos-delay="1200">
                    
                    <!-- QR Code System -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="1400">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h3 class="feature-title">نظام QR Code المتطور</h3>
                        <p class="feature-description">
                            تسجيل حضور فوري وآمن باستخدام تقنية QR Code مع نوافذ زمنية ذكية (15 دقيقة). 
                            يدعم التحقق من الموقع والهوية وحماية من التلاعب.
                        </p>
                    </div>

                    <!-- Teacher Dashboard -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="1600">
                        <div class="feature-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="feature-title">لوحة تحكم المعلم المتقدمة</h3>
                        <p class="feature-description">
                            واجهة شاملة للمعلم مع إحصائيات تفاعلية، تسجيل حضور فردي وجماعي، 
                            إدارة الطلاب، وتقارير مفصلة لكل درس مع معدلات الحضور.
                        </p>
                    </div>

                    <!-- Admin System -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="1800">
                        <div class="feature-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="feature-title">نظام إداري شامل</h3>
                        <p class="feature-description">
                            لوحة تحكم المدير مع صلاحيات كاملة لإدارة المستخدمين والدروس والتقارير. 
                            إحصائيات شاملة وأدوات مراقبة متقدمة للنظام بالكامل.
                        </p>
                    </div>

                    <!-- Student Experience -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="2000">
                        <div class="feature-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="feature-title">تجربة طالب مميزة</h3>
                        <p class="feature-description">
                            واجهة بسيطة وسهلة للطلاب مع ماسح QR مدمج، متابعة الحضور الشخصي، 
                            إشعارات الدروس، وإحصائيات تفصيلية لكل طالب.
                        </p>
                    </div>

                    <!-- Attendance Management -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="2200">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 class="feature-title">إدارة الحضور الذكية</h3>
                        <p class="feature-description">
                            تسجيل تلقائي مع تصنيف الحالات (حاضر، متأخر، غائب، معذور). 
                            تقارير تفصيلية وإحصائيات متقدمة مع فلاتر قوية وتصدير البيانات.
                        </p>
                    </div>

                    <!-- Lesson Management -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="2400">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="feature-title">إدارة الدروس المتطورة</h3>
                        <p class="feature-description">
                            إنشاء وتنظيم الدروس مع جدولة ذكية، إدارة الطلاب، 
                            QR Code لكل درس، وتتبع الحضور بالوقت الفعلي.
                        </p>
                    </div>

                    <!-- Advanced Reports -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="2600">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">تقارير وإحصائيات متقدمة</h3>
                        <p class="feature-description">
                            تقارير تفاعلية مع رسوم بيانية، تحليل الأداء، معدلات الحضور، 
                            إحصائيات المعلمين والطلاب، وتصدير بصيغ متعددة.
                        </p>
                    </div>

                    <!-- Security System -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="2800">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">أمان وحماية عالية</h3>
                        <p class="feature-description">
                            نظام صلاحيات متقدم، حماية من التلاعب، تشفير البيانات، 
                            مراجعة السجلات، وحماية شاملة للخصوصية والبيانات.
                        </p>
                    </div>

                    <!-- Mobile Responsive -->
                    <div class="feature-card" data-aos="flip-left" data-aos-delay="3000">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">متوافق مع جميع الأجهزة</h3>
                        <p class="feature-description">
                            تصميم متجاوب بالكامل، يعمل على الهواتف والتابلت والحاسوب. 
                            تجربة مستخدم مثالية على جميع أحجام الشاشات.
                        </p>
                    </div>

                </div>

                <!-- System Roles Notice -->
                <div class="auth-notice" data-aos="fade-up" data-aos-delay="3200">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>هام للغاية:</strong> المعلمين والإداريين يتم إنشاء حساباتهم من قبل الإدارة فقط - الطلاب فقط يمكنهم التسجيل الذاتي بنظام آمن ومحمي
                </div>

                <!-- Main Actions -->
                @guest
                    <div class="text-center" data-aos="fade-up" data-aos-delay="3400">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mb-4">
                                    <!-- تسجيل طالب جديد -->
                                    <a href="{{ route('student.register.form') }}" class="btn btn-success-custom btn-custom">
                                        <i class="fas fa-user-plus me-2"></i>
                                        تسجيل طالب جديد
                                        <div style="font-size: 0.8rem; opacity: 0.9;">إنشاء حساب آمن</div>
                                    </a>
                                    
                                    <!-- تسجيل الدخول -->
                                    <a href="{{ route('admin.login') }}" class="btn btn-primary-custom btn-custom">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        تسجيل الدخول
                                        <div style="font-size: 0.8rem; opacity: 0.9;">للمعلمين والإداريين</div>
                                    </a>
                                </div>

                                <div class="mt-4" style="background: rgba(255, 255, 255, 0.1); padding: 1rem; border-radius: 15px;">
                                    <small style="color: rgba(255, 255, 255, 0.9);">
                                        <i class="fas fa-users me-2"></i>
                                        <strong>للمعلمين والإداريين:</strong> يرجى استخدام البيانات المقدمة من إدارة النظام
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center" data-aos="fade-up" data-aos-delay="3400">
                        <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mb-4">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-custom btn-custom">
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    لوحة إدارة النظام
                                    <div style="font-size: 0.8rem; opacity: 0.9;">صلاحيات كاملة</div>
                                </a>
                            @elseif(auth()->user()->role === 'teacher')
                                <a href="{{ route('teacher.dashboard') }}" class="btn btn-success-custom btn-custom">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>
                                    لوحة تحكم المعلم
                                    <div style="font-size: 0.8rem; opacity: 0.9;">إدارة دروسك</div>
                                </a>
                            @elseif(auth()->user()->role === 'student')
                                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-custom btn-custom">
                                    <i class="fas fa-user-graduate me-2"></i>
                                    لوحة تحكم الطالب
                                    <div style="font-size: 0.8rem; opacity: 0.9;">متابعة حضورك</div>
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-custom btn-custom">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    تسجيل الخروج
                                    <div style="font-size: 0.8rem; opacity: 0.9;">خروج آمن</div>
                                </button>
                            </form>
                        </div>                        <div class="alert" style="background: rgba(30, 60, 114, 0.3); border: 1px solid rgba(42, 82, 152, 0.4); border-radius: 15px; color: white;">
                            <i class="fas fa-check-circle me-2"></i>
                            مرحباً <strong>{{ auth()->user()->name }}</strong> - 
                            <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: white;">
                                {{ 
                                    auth()->user()->role === 'admin' ? 'مدير النظام' : 
                                    (auth()->user()->role === 'teacher' ? 'معلم' : 'طالب') 
                                }}
                            </span>
                        </div>
                    </div>
                @endguest

            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer style="background: rgba(0, 0, 0, 0.8); color: white; padding: 2rem 0; text-align: center;">
        <div class="container">
            <div class="row">                <div class="col-md-6" data-aos="fade-right">
                    <h5 style="color: #2a5298; margin-bottom: 1rem;">
                        <i class="fas fa-code me-2"></i>نظام BasmahApp
                    </h5>
                    <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 0.5rem;">
                        نظام إدارة الحضور الأكثر تطوراً وأماناً في المنطقة العربية
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">
                        <i class="fas fa-calendar me-2"></i>
                        {{ now()->format('Y') }} &copy; جميع الحقوق محفوظة
                    </p>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <h5 style="color: #87ceeb; margin-bottom: 1rem;">
                        <i class="fas fa-user-tie me-2"></i>معلومات المطور
                    </h5>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 0.5rem;">
                        <i class="fas fa-user me-2"></i>
                        <strong>المطور:</strong> أسيد صلاح أبو الحاج
                    </p>
                    <p style="margin-bottom: 0;">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:osaidhaj03@gmail.com" 
                           style="color: #2a5298; text-decoration: none; transition: all 0.3s ease;"
                           onmouseover="this.style.color='#87ceeb'"
                           onmouseout="this.style.color='#2a5298'">
                            osaidhaj03@gmail.com
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Additional Footer Info -->
            <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 2rem 0 1rem;">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center gap-3" style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.7);">
                        <span><i class="fas fa-code me-1"></i>Laravel Framework</span>
                        <span><i class="fas fa-database me-1"></i>MySQL Database</span>
                        <span><i class="fas fa-shield-alt me-1"></i>Security First</span>
                        <span><i class="fas fa-mobile-alt me-1"></i>Mobile Ready</span>
                        <span><i class="fas fa-chart-line me-1"></i>Advanced Analytics</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <script>
        // Initialize AOS (Animate On Scroll)
        AOS.init({
            duration: 1000,
            easing: 'ease-out-cubic',
            once: true,
            offset: 120
        });

        // Dynamic particle generation
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.width = particle.style.height = (Math.random() * 5 + 3) + 'px';
            particle.style.background = ['#1e3c72', '#2a5298', '#0066cc', '#87ceeb'][Math.floor(Math.random() * 4)];
            particle.style.animationDelay = Math.random() * 15 + 's';
            document.body.appendChild(particle);

            // Remove particle after animation
            setTimeout(() => {
                if (particle.parentNode) {
                    particle.parentNode.removeChild(particle);
                }
            }, 15000);
        }

        // Create particles periodically
        setInterval(createParticle, 3000);

        // Add smooth scrolling to all anchor links
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

        // Success/Error Messages
        @if(session('success'))            // Create animated success notification
            const successDiv = document.createElement('div');
            successDiv.innerHTML = `
                <div class="alert alert-success position-fixed" style="top: 20px; right: 20px; z-index: 9999; border-radius: 15px; box-shadow: 0 10px 30px rgba(30, 60, 114, 0.4);">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            `;
            document.body.appendChild(successDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 5000);
        @endif
        
        @if(session('error'))
            // Create animated error notification
            const errorDiv = document.createElement('div');
            errorDiv.innerHTML = `
                <div class="alert alert-danger position-fixed" style="top: 20px; right: 20px; z-index: 9999; border-radius: 15px; box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            `;
            document.body.appendChild(errorDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        @endif

        // Add loading animation to buttons
        document.querySelectorAll('.btn-custom').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.tagName === 'A' && this.href) {
                    this.innerHTML += ' <i class="fas fa-spinner fa-spin ms-2"></i>';
                    this.style.pointerEvents = 'none';
                }
            });
        });

        // Performance monitoring
        window.addEventListener('load', function() {
            const loadTime = performance.now();
            console.log(`✅ BasmahApp loaded in ${Math.round(loadTime)}ms`);
        });
    </script>
</body>
</html>
