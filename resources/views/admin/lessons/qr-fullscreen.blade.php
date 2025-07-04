<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $lesson->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .qr-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 90%;
        }
        .qr-code {
            margin: 30px 0;
        }
        .lesson-title {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .lesson-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .timer {
            font-size: 1.2rem;
            color: #dc3545;
            font-weight: bold;
        }
        .instructions {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .status-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .fullscreen-controls {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="status-indicator">
        <div class="badge bg-success fs-6" id="status-badge">
            <i class="fas fa-circle me-1"></i>
            مباشر
        </div>
    </div>

    <div class="qr-container">
        <h1 class="lesson-title">
            <i class="fas fa-qrcode me-3"></i>
            {{ $lesson->name }}
        </h1>
        
        <div class="lesson-info">
            <div class="row">
                <div class="col-6">
                    <strong>المادة:</strong> {{ $lesson->subject }}
                </div>
                <div class="col-6">
                    <strong>المعلم:</strong> {{ $lesson->teacher->name }}
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6">
                    <strong>الوقت:</strong> {{ $lesson->start_time->format('H:i') }} - {{ $lesson->end_time->format('H:i') }}
                </div>
                <div class="col-6">
                    <strong>التاريخ:</strong> {{ now()->format('Y-m-d') }}
                </div>
            </div>
        </div>

        <div class="qr-code">
            {!! $qrCode !!}
        </div>

        <div class="timer" id="attendance-timer">
            <i class="fas fa-clock me-2"></i>
            <span id="countdown">جاري التحديد...</span>
        </div>

        <div class="instructions">
            <h5>
                <i class="fas fa-mobile-alt me-2"></i>
                تعليمات للطلاب
            </h5>
            <ul class="list-unstyled mt-3">
                <li><i class="fas fa-check text-success me-2"></i>افتح تطبيق الكاميرا أو ماسح QR</li>
                <li><i class="fas fa-check text-success me-2"></i>وجه الكاميرا نحو الرمز أعلاه</li>
                <li><i class="fas fa-check text-success me-2"></i>يجب التسجيل خلال أول 15 دقيقة</li>
                <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>تأكد من اتصالك بالإنترنت</li>
            </ul>
        </div>

        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                BasmahApp - نظام الحضور الذكي
            </small>
        </div>
    </div>

    <div class="fullscreen-controls">
        <button class="btn btn-light btn-sm" onclick="toggleFullscreen()">
            <i class="fas fa-expand"></i>
        </button>
        <button class="btn btn-light btn-sm" onclick="refreshQR()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <button class="btn btn-danger btn-sm" onclick="window.close()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        // تحديث المؤقت كل ثانية
        function updateTimer() {
            const lessonStartTime = '{{ $lesson->start_time->format("H:i") }}';
            const now = new Date();
            const lessonStart = new Date();
            const [hours, minutes] = lessonStartTime.split(':');
            lessonStart.setHours(parseInt(hours), parseInt(minutes), 0, 0);
            
            const attendanceEnd = new Date(lessonStart.getTime() + 15 * 60000); // +15 دقيقة
            const timeLeft = attendanceEnd - now;
            
            const countdownElement = document.getElementById('countdown');
            const statusBadge = document.getElementById('status-badge');
            
            if (timeLeft > 0) {
                const minutesLeft = Math.floor(timeLeft / 60000);
                const secondsLeft = Math.floor((timeLeft % 60000) / 1000);
                
                if (minutesLeft > 0) {
                    countdownElement.textContent = `باقي ${minutesLeft} دقيقة و ${secondsLeft} ثانية`;
                } else {
                    countdownElement.textContent = `باقي ${secondsLeft} ثانية`;
                }
                
                statusBadge.innerHTML = '<i class="fas fa-circle me-1"></i>مفتوح';
                statusBadge.className = 'badge bg-success fs-6';
            } else {
                countdownElement.textContent = 'انتهت فترة تسجيل الحضور';
                statusBadge.innerHTML = '<i class="fas fa-times-circle me-1"></i>مغلق';
                statusBadge.className = 'badge bg-danger fs-6';
            }
        }

        // تشغيل المؤقت كل ثانية
        setInterval(updateTimer, 1000);
        updateTimer(); // تشغيل فوري

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function refreshQR() {
            window.location.reload();
        }

        // تجديد QR كل 5 دقائق
        setInterval(function() {
            refreshQR();
        }, 300000);

        // التحقق من حالة الاتصال
        window.addEventListener('online', function() {
            document.getElementById('status-badge').innerHTML = '<i class="fas fa-circle me-1"></i>متصل';
        });

        window.addEventListener('offline', function() {
            document.getElementById('status-badge').innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>غير متصل';
            document.getElementById('status-badge').className = 'badge bg-warning fs-6';
        });
    </script>
</body>
</html>
