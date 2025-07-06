<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - أنوار العلماء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <!-- Anwar Al-Oloma Colors -->
    <link href="{{ asset('css/anwar-colors.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: var(--anwar-background);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            color: var(--anwar-gray-dark);
        }
        
        /* خلفية مبسطة ومريحة */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(218, 165, 32, 0.015) 0%, 
                rgba(0, 128, 128, 0.01) 50%,
                rgba(218, 165, 32, 0.008) 100%);
            opacity: 0.7;
            pointer-events: none;
        }
        
        .login-card {
            background: var(--anwar-white);
            border-radius: 30px;
            box-shadow: 0 25px 80px var(--anwar-shadow-gold);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(218, 165, 32, 0.08);
            position: relative;
            z-index: 1;
        }
        
        .logo {
            color: var(--anwar-gold);
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 8px var(--anwar-shadow-gold);
            font-family: var(--font-arabic);
            font-weight: 800;
        }
        
        /* شعار مخصص لصفحة تسجيل الدخول */
        .login-logo {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .login-logo .logo-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--anwar-gold) 0%, #b8860b 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 8px 25px var(--anwar-shadow-gold);
        }
        
        .login-logo .logo-icon {
            font-size: 2.5rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .app-title {
            font-family: 'Amiri', serif;
            color: var(--anwar-teal-dark);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .btn-primary {
            background: var(--anwar-gradient-gold);
            border: none;
            border-radius: 25px;
            padding: 15px 35px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 25px var(--anwar-shadow-gold);
            font-family: var(--font-main);
            letter-spacing: 0.5px;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px var(--anwar-shadow-gold);
            color: white;
        }
        
        .btn-outline-success {
            border: 2px solid var(--anwar-teal);
            color: var(--anwar-teal);
            background: transparent;
            border-radius: 20px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .btn-outline-success:hover {
            background: var(--anwar-teal);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--anwar-shadow-teal);
        }
        
        .form-control {
            border-radius: 20px;
            border: 2px solid var(--anwar-gray-light);
            padding: 15px 20px;
            transition: all 0.4s ease;
            font-family: var(--font-main);
            background: var(--anwar-off-white);
        }
        
        .form-control:focus {
            border-color: var(--anwar-gold);
            box-shadow: 0 0 0 0.3rem var(--anwar-shadow-gold);
            transform: translateY(-1px);
            background: var(--anwar-white);
        }
        
        .input-group-text {
            background: var(--anwar-gray-light);
            border: 2px solid var(--anwar-gray-light);
            border-radius: 15px 0 0 15px;
            color: var(--anwar-teal);
        }
        
        .text-info {
            color: var(--anwar-teal) !important;
        }
        
        .border-top {
            border-top: 2px solid var(--anwar-gray-light) !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card p-5">                    <div class="text-center mb-4">
                        <div class="login-logo">
                            <div class="logo-circle">
                                <i class="fas fa-star logo-icon"></i>
                            </div>
                        </div>
                        <h2 class="app-title mb-3">أنوار العلماء</h2>
                        <p class="text-muted">تسجيل الدخول إلى نظام البصمة </p>
                        <small class="text-info">للطلاب والمعلمين والإداريين</small>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                تسجيل الدخول
                            </button>
                        </div>
                    </form>                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            سيتم توجيهك للوحة المناسبة بناءً على دورك
                        </small>
                        
                        <!-- رابط التسجيل للطلاب الجدد -->
                        <div class="mt-3">
                            <div class="border-top pt-3">
                                <p class="text-muted small mb-2">طالب جديد؟</p>
                                <a href="{{ route('student.register.form') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-user-plus me-2"></i>
                                    إنشاء حساب طالب جديد
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
