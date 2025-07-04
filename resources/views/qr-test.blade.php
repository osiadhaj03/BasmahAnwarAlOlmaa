<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تجربة QR Code - BasmahApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .qr-container { background: #f8f9fa; border-radius: 15px; padding: 30px; margin: 20px 0; }
        .lesson-card { border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card lesson-card">
                    <div class="card-header bg-primary text-white text-center">
                        <h3><i class="fas fa-qrcode me-2"></i>تجربة QR Code - BasmahApp</h3>
                        <p class="mb-0">اختر درس لتوليد QR Code فوري</p>
                    </div>
                    <div class="card-body">
                        <div id="lessons-list" class="row">
                            <div class="col-12 text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">جاري التحميل...</span>
                                </div>
                                <p class="mt-2">جاري تحميل الدروس...</p>
                            </div>
                        </div>
                        
                        <div id="qr-section" class="qr-container text-center" style="display: none;">
                            <h5 class="text-primary mb-3">QR Code للدرس</h5>
                            <div id="qr-display"></div>
                            <div class="mt-3">
                                <button class="btn btn-success" onclick="refreshQR()">
                                    <i class="fas fa-sync-alt me-2"></i>توليد QR جديد
                                </button>
                                <button class="btn btn-info" onclick="testScan()">
                                    <i class="fas fa-mobile-alt me-2"></i>تجربة المسح
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentLessonId = null;
        
        // تحميل الدروس
        fetch('/api/lessons-simple')
            .then(response => response.json())
            .then(lessons => {
                const container = document.getElementById('lessons-list');
                if (lessons.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                لا توجد دروس متاحة. يرجى إضافة درس أولاً.
                            </div>
                            <a href="/admin/lessons/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>إضافة درس جديد
                            </a>
                        </div>
                    `;
                } else {
                    container.innerHTML = lessons.map(lesson => `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title">${lesson.name}</h6>
                                    <p class="card-text small text-muted">
                                        ${lesson.subject || 'غير محدد'}<br>
                                        ${lesson.day_of_week} - ${lesson.start_time}
                                    </p>
                                    <button class="btn btn-primary btn-sm" onclick="generateQR(${lesson.id}, '${lesson.name}')">
                                        <i class="fas fa-qrcode me-1"></i>توليد QR
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            })
            .catch(error => {
                document.getElementById('lessons-list').innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            خطأ في تحميل الدروس: ${error.message}
                        </div>
                    </div>
                `;
            });
        
        function generateQR(lessonId, lessonName) {
            currentLessonId = lessonId;
            const qrSection = document.getElementById('qr-section');
            const qrDisplay = document.getElementById('qr-display');
            
            qrDisplay.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">جاري توليد QR...</span>
                </div>
                <p class="mt-2">جاري توليد QR Code للدرس: ${lessonName}</p>
            `;
            
            qrSection.style.display = 'block';
            
            const qrImg = new Image();
            qrImg.onload = function() {
                qrDisplay.innerHTML = `
                    <h6 class="text-success mb-3">${lessonName}</h6>
                    <img src="/quick-qr/${lessonId}" alt="QR Code" class="img-fluid border rounded" style="max-width: 300px;">
                    <p class="text-muted mt-2 small">
                        <i class="fas fa-clock me-1"></i>تم التوليد: ${new Date().toLocaleTimeString('ar')}
                    </p>
                `;
            };
            qrImg.onerror = function() {
                qrDisplay.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        فشل في توليد QR Code
                    </div>
                `;
            };
            qrImg.src = `/quick-qr/${lessonId}?t=${Date.now()}`;
        }
        
        function refreshQR() {
            if (currentLessonId) {
                generateQR(currentLessonId, 'الدرس المحدد');
            }
        }
        
        function testScan() {
            window.open('/qr-scanner', '_blank');
        }
    </script>
</body>
</html>
