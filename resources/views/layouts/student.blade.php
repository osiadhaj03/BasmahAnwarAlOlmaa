<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة الطالب') - أنوار العلماء</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* متغيرات ألوان أنوار العلماء */
        :root {
            --anwar-gold: #daa520;
            --anwar-teal: #008080;
            --anwar-teal-dark: #006666;
            --anwar-gradient-gold: linear-gradient(135deg, #daa520 0%, #b8860b 100%);
            --anwar-gradient-teal: linear-gradient(135deg, #008080 0%, #006666 100%);
            --anwar-shadow-gold: rgba(218, 165, 32, 0.3);
            --anwar-shadow-teal: rgba(0, 128, 128, 0.3);
            --anwar-white: #ffffff;
            --anwar-background: #f8fafc;
            --anwar-gray-light: #e2e8f0;
        }
        
        body {
            background: linear-gradient(135deg, rgba(218, 165, 32, 0.1) 0%, rgba(0, 128, 128, 0.08) 100%);
            min-height: 100vh;
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin: 20px;
            min-height: calc(100vh - 40px);
            box-shadow: 0 20px 40px rgba(218, 165, 32, 0.1);
        }
        .header {
            background: var(--anwar-gradient-teal);
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
            box-shadow: 0 15px 35px var(--anwar-shadow-gold);
        }
        .stats-card {
            background: var(--anwar-gradient-gold);
            color: white;
        }
        .lesson-card {
            border-left: 5px solid var(--anwar-teal);
        }
        .btn-check-in {
            background: var(--anwar-gradient-gold);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: bold;
            box-shadow: 0 4px 15px var(--anwar-shadow-gold);
            transition: all 0.3s ease;
        }
        .btn-check-in:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--anwar-shadow-gold);
            background: linear-gradient(135deg, #b8860b 0%, #9a7609 100%);
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
            border-left-color: var(--anwar-teal);
            background-color: #f8f9fa;
        }
        .floating-stats {
            position: sticky;
            top: 20px;
        }
        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="main-container">        <!-- Header -->
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-user-graduate fa-2x me-3"></i>
                        <div>
                            <h3 class="mb-0">مرحباً {{ auth()->user()->name }}</h3>
                            <small class="opacity-75">طالب في أنوار العلماء</small>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="mb-2">
                        <small class="opacity-75">{{ now()->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</small>
                    </div>
                    <div class="mb-3">
                        <small class="opacity-75">{{ now()->format('H:i') }}</small>
                    </div>
                    <!-- زر تسجيل الخروج -->
                    <div>
                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                            @csrf
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

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <div class="text-center py-3 border-top">
            <small class="text-muted">
                <i class="fas fa-graduation-cap me-1"></i>
                أنوار العلماء - نظام إدارة الحضور الذكي
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
