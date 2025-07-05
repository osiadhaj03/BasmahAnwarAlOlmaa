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
        
        /* نقش إسلامي محسّن في الشريط الجانبي */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.035'%3E%3Cpolygon points='50,0 61.8,35.36 100,35.36 70.9,57.64 82.7,93 50,70.72 17.3,93 29.1,57.64 0,35.36 38.2,35.36'/%3E%3Cpolygon points='25,25 30.9,42.68 50,42.68 35.45,54.82 41.35,72.5 25,60.36 8.65,72.5 14.55,54.82 0,42.68 19.1,42.68'/%3E%3Cpolygon points='75,25 80.9,42.68 100,42.68 85.45,54.82 91.35,72.5 75,60.36 58.65,72.5 64.55,54.82 50,42.68 69.1,42.68'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpolygon points='40,0 48.33,28 80,28 56.67,46 65,74 40,56 15,74 23.33,46 0,28 31.67,28'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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
            background: var(--anwar-gradient-gold);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 30px var(--anwar-shadow);
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px var(--anwar-shadow);
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
        
        .table {
            background: var(--anwar-white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px var(--anwar-shadow-soft);
            border: 1px solid rgba(218, 165, 32, 0.08);
        }
        
        .table thead th {
            background: var(--anwar-gradient-gold);
            color: white;
            border: none;
            padding: 20px;
            font-weight: 700;
            font-family: var(--font-arabic);
            text-align: center;
            position: relative;
        }
        
        .table thead th::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpolygon points='30,0 36.18,21.18 60,21.18 42.27,34.64 48.45,55.82 30,42.36 11.55,55.82 17.73,34.64 0,21.18 23.82,21.18'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
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
