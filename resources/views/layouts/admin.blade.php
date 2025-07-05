<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - BasmahApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <!-- Anwar Al-Oloma Colors -->
    <link href="{{ asset('css/anwar-colors.css') }}" rel="stylesheet">
    
    <style>
        body {
            background-color: var(--anwar-background);
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--anwar-gray-dark);
            line-height: 1.7;
        }
        
        .sidebar {
            background: var(--anwar-gradient-teal);
            min-height: 100vh;
            color: white;
            position: relative;
            box-shadow: 4px 0 25px var(--anwar-shadow-teal);
        }
        
        /* خلفية مبسطة في الشريط الجانبي */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, 
                rgba(255, 255, 255, 0.04) 0%, 
                rgba(255, 255, 255, 0.02) 50%,
                rgba(255, 255, 255, 0.01) 100%);
            opacity: 0.9;
            pointer-events: none;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 18px 25px;
            border-radius: 20px;
            margin: 8px 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            z-index: 1;
            font-weight: 500;
            border: 1px solid transparent;
        }
        
        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active {
            background: rgba(218, 165, 32, 0.25);
            color: white;
            transform: translateX(-8px);
            box-shadow: 0 8px 25px rgba(218, 165, 32, 0.3);
            border-color: rgba(218, 165, 32, 0.4);
            backdrop-filter: blur(10px);
        }
        
        .sidebar .nav-link i {
            margin-left: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            color: var(--anwar-gold);
            transform: scale(1.1);
        }
        
        .sidebar .logo-section {
            position: relative;
            z-index: 2;
            background: rgba(218, 165, 32, 0.15);
            margin: 1.5rem;
            border-radius: 25px;
            padding: 2rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .logo-section h3 {
            font-family: var(--font-arabic);
            font-weight: 700;
            color: var(--anwar-gold);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin: 0;
            font-size: 1.8rem;
        }
        
        .main-content {
            padding: 30px;
            background: var(--anwar-background);
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 25px;
            box-shadow: 0 10px 40px var(--anwar-shadow-soft);
            background: var(--anwar-white);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(218, 165, 32, 0.08);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px var(--anwar-shadow-gold);
        }
        
        .card-header {
            background: var(--anwar-gradient-gold);
            color: white;
            border-bottom: none;
            border-radius: 25px 25px 0 0 !important;
            padding: 2rem;
            font-weight: 700;
            font-family: var(--font-arabic);
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.1) 0%, 
                rgba(255, 255, 255, 0.05) 100%);
            opacity: 0.6;
            pointer-events: none;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .btn-primary {
            background: var(--anwar-gradient-gold);
            border: none;
            border-radius: 20px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 20px var(--anwar-shadow-gold);
            font-family: var(--font-main);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px var(--anwar-shadow-gold);
            background: linear-gradient(135deg, #E6B800 0%, #DAA520 50%, #B8860B 100%);
        }
        
        .btn-secondary {
            background: var(--anwar-gradient-teal);
            border: none;
            border-radius: 20px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 6px 20px var(--anwar-shadow-teal);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px var(--anwar-shadow-teal);
        }
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--anwar-shadow);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e9ecef 100%);
            color: #495057;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #f1f3f4 100%);
        }
        
        .stats-card-teal {
            background: var(--anwar-gradient-teal);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 30px var(--anwar-shadow-dark);
            transition: all 0.3s ease;
        }
        
        .stats-card-teal:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px var(--anwar-shadow-dark);
        }
        
        /* بطاقات بيضاء للإجراءات السريعة ومعلومات النظام */
        .card-white-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .card-white-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        
        .card-header-white {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            border-radius: 15px 15px 0 0 !important;
            padding: 1.25rem;
        }
        
        .table {
            background: var(--anwar-white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px var(--anwar-shadow-soft);
            border: 1px solid rgba(218, 165, 32, 0.08);
        }
        
        .table thead th {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #495057;
            border: none;
            padding: 20px;
            font-weight: 700;
            font-family: var(--font-arabic);
            text-align: center;
            position: relative;
            border-bottom: 2px solid #dee2e6;
        }
        
        .table thead th::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.08) 0%, 
                rgba(255, 255, 255, 0.03) 100%);
            opacity: 0.8;
            pointer-events: none;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: var(--anwar-overlay-warm);
            transform: scale(1.01);
        }
        
        .form-control {
            border: 2px solid var(--anwar-gray-light);
            border-radius: 15px;
            padding: 12px 20px;
            transition: all 0.4s ease;
            font-family: var(--font-main);
        }
        
        .form-control:focus {
            border-color: var(--anwar-gold);
            box-shadow: 0 0 0 0.3rem var(--anwar-shadow-gold);
            transform: translateY(-1px);
        }
        
        .form-label {
            color: var(--anwar-teal-dark);
            font-weight: 600;
            font-family: var(--font-main);
            margin-bottom: 8px;
        }
        
        .alert {
            border: none;
            border-radius: 20px;
            padding: 1.5rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            border-right: 5px solid var(--anwar-gold);
            color: var(--anwar-teal-dark);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fdf2f2 0%, #fed7d7 100%);
            border-right: 5px solid #e53e3e;
            color: #742a2a;
        }
        
        /* تحسينات للعناوين */
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-arabic);
            color: var(--anwar-teal-dark);
            font-weight: 700;
        }
        
        .page-title {
            color: var(--anwar-teal-deep);
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 15px;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 4px;
            background: var(--anwar-gradient-gold);
            border-radius: 2px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="logo-section">
                    <i class="fas fa-graduation-cap fa-3x mb-3" style="color: var(--anwar-gold);"></i>
                    <h4 style="font-family: 'Amiri', serif; color: white;">أنوار العلماء</h4>
                    <small style="color: rgba(255,255,255,0.8);">{{ auth()->user()->name }}</small>
                    <br>
                    <small class="badge mt-2" style="background: var(--anwar-gold); color: white;">
                        {{ auth()->user()->role === 'admin' ? 'مدير' : 'معلم' }}
                    </small>
                </div>

                <nav class="nav flex-column">
                    @if(auth()->user()->role === 'admin')
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            الرئيسية
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                           href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users me-2"></i>
                            المستخدمين
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.lessons.*') ? 'active' : '' }}" 
                           href="{{ route('admin.lessons.index') }}">
                            <i class="fas fa-book me-2"></i>
                            الدروس
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.attendances.*') ? 'active' : '' }}" 
                           href="{{ route('admin.attendances.index') }}">
                            <i class="fas fa-clipboard-check me-2"></i>
                            مراجعة الحضور
                        </a>
                    @elseif(auth()->user()->role === 'teacher')
                        <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" 
                           href="{{ route('teacher.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            لوحة التحكم
                        </a>
                        <a class="nav-link {{ request()->routeIs('teacher.lessons.*') ? 'active' : '' }}" 
                           href="{{ route('teacher.lessons.index') }}">
                            <i class="fas fa-book me-2"></i>
                            إدارة دروسي
                        </a>
                        <a class="nav-link {{ request()->routeIs('teacher.attendances.*') ? 'active' : '' }}" 
                           href="{{ route('teacher.attendances.index') }}">
                            <i class="fas fa-clipboard-check me-2"></i>
                            مراجعة الحضور والغياب
                        </a>
                    @endif
                </nav>

                <div class="mt-auto p-3">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
