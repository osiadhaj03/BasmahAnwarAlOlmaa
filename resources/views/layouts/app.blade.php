<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'BasmahApp')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <!-- Anwar Al-Oloma Colors -->
    <link href="{{ asset('css/anwar-colors.css') }}" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--anwar-background);
            min-height: 100vh;
            color: var(--anwar-gray-dark);
        }
        
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
            position: relative;
        }
        
        /* خلفية إسلامية محسّنة ومريحة للعين */
        .main-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='140' height='140' viewBox='0 0 140 140' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23DAA520' fill-opacity='0.015'%3E%3Cpolygon points='70,0 86.52,49.98 140,49.98 99.24,80.76 115.76,130.74 70,99.96 24.24,130.74 40.76,80.76 0,49.98 53.48,49.98'/%3E%3Cpolygon points='35,35 43.26,59.99 70,59.99 49.62,75.38 57.88,100.37 35,85.98 12.12,100.37 20.38,75.38 0,59.99 26.74,59.99'/%3E%3Cpolygon points='105,35 113.26,59.99 140,59.99 119.62,75.38 127.88,100.37 105,85.98 82.12,100.37 90.38,75.38 70,59.99 96.74,59.99'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.6;
            pointer-events: none;
        }
        
        .content-card {
            background: var(--anwar-white);
            backdrop-filter: blur(15px);
            border-radius: 30px;
            box-shadow: 0 25px 80px var(--anwar-shadow-gold);
            padding: 3.5rem;
            width: 100%;
            max-width: 550px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(218, 165, 32, 0.08);
        }
        
        .btn-custom {
            border-radius: 30px;
            padding: 15px 35px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            font-family: var(--font-main);
            letter-spacing: 0.5px;
        }
        
        .btn-primary-custom {
            background: var(--anwar-gradient-gold);
            color: white;
            border: none;
            box-shadow: 0 8px 25px var(--anwar-shadow-gold);
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px var(--anwar-shadow-gold);
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid var(--anwar-teal);
            color: var(--anwar-teal);
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: var(--anwar-teal);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--anwar-shadow-teal);
        }
        
        .text-gradient {
            background: var(--anwar-gradient-warm);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-family: var(--font-arabic);
        }
        
        .form-control:focus {
            border-color: var(--anwar-gold);
            box-shadow: 0 0 0 0.3rem var(--anwar-shadow-gold);
            transform: translateY(-1px);
        }
        
        .form-control {
            border: 2px solid var(--anwar-gray-light);
            border-radius: 20px;
            padding: 15px 25px;
            transition: all 0.4s ease;
            font-family: var(--font-main);
        }
        
        .form-label {
            color: var(--anwar-teal-dark);
            font-weight: 600;
            font-family: var(--font-main);
            margin-bottom: 8px;
        }
        
        .alert-custom {
            border: none;
            border-radius: 20px;
            padding: 1.5rem;
            border-right: 5px solid var(--anwar-gold);
            font-weight: 500;
            backdrop-filter: blur(10px);
        }
        
        .logo-text {
            font-family: 'Amiri', serif;
            color: var(--anwar-gold);
            font-size: 3rem;
            text-shadow: 0 4px 8px var(--anwar-shadow-gold);
            font-weight: 800;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="main-container">
        <div class="content-card">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Session Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            });
        </script>
    @endif
    
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            });
        </script>
    @endif
    
    @yield('scripts')
</body>
</html>
