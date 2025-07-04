<?php $__env->startSection('title', 'ماسح QR Code'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>
                        ماسح QR Code للحضور
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Camera Preview -->
                    <div class="camera-container text-center mb-4">
                        <div id="camera-preview" class="border rounded" style="height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                            <div id="camera-placeholder">
                                <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                                <p class="text-muted">اضغط "تشغيل الكاميرا" لبدء المسح</p>
                            </div>
                            <video id="qr-video" style="width: 100%; height: 100%; display: none;" playsinline></video>
                            <canvas id="qr-canvas" style="display: none;"></canvas>
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="text-center mb-4">
                        <button id="start-camera" class="btn btn-success btn-lg me-2">
                            <i class="fas fa-camera me-2"></i>
                            تشغيل الكاميرا
                        </button>
                        <button id="stop-camera" class="btn btn-danger btn-lg" style="display: none;">
                            <i class="fas fa-stop me-2"></i>
                            إيقاف الكاميرا
                        </button>
                    </div>

                    <!-- Manual Input -->
                    <div class="manual-input">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-keyboard me-2"></i>
                            أو أدخل الكود يدوياً
                        </h6>
                        <div class="input-group">
                            <input type="text" id="manual-qr-input" class="form-control" placeholder="الصق الكود هنا...">
                            <button class="btn btn-primary" onclick="processManualInput()">
                                <i class="fas fa-paper-plane me-1"></i>
                                إرسال
                            </button>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    <div id="status-messages" class="mt-4"></div>

                    <!-- Instructions -->
                    <div class="instructions mt-4">
                        <h6 class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            تعليمات الاستخدام
                        </h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>تأكد من وجود إضاءة جيدة</li>
                            <li><i class="fas fa-check text-success me-2"></i>وجه الكاميرا مباشرة نحو QR Code</li>
                            <li><i class="fas fa-check text-success me-2"></i>تأكد من أنك داخل نافذة الحضور (أول 15 دقيقة)</li>
                            <li><i class="fas fa-check text-success me-2"></i>يجب أن تكون مسجلاً في الدرس</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include QR Scanner Library -->
<script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>

<script>
let video = document.getElementById('qr-video');
let canvas = document.getElementById('qr-canvas');
let context = canvas.getContext('2d');
let scanning = false;
let stream = null;

document.getElementById('start-camera').addEventListener('click', startCamera);
document.getElementById('stop-camera').addEventListener('click', stopCamera);

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'environment' // استخدام الكاميرا الخلفية إذا متوفرة
            } 
        });
        
        video.srcObject = stream;
        video.play();
        
        document.getElementById('camera-placeholder').style.display = 'none';
        video.style.display = 'block';
        document.getElementById('start-camera').style.display = 'none';
        document.getElementById('stop-camera').style.display = 'inline-block';
        
        scanning = true;
        scanQRCode();
        
        showMessage('تم تشغيل الكاميرا بنجاح', 'success');
        
    } catch (error) {
        console.error('Error accessing camera:', error);
        showMessage('خطأ في الوصول للكاميرا: ' + error.message, 'danger');
    }
}

function stopCamera() {
    scanning = false;
    
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    
    video.style.display = 'none';
    document.getElementById('camera-placeholder').style.display = 'flex';
    document.getElementById('start-camera').style.display = 'inline-block';
    document.getElementById('stop-camera').style.display = 'none';
    
    showMessage('تم إيقاف الكاميرا', 'info');
}

function scanQRCode() {
    if (!scanning) return;
    
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.height = video.videoHeight;
        canvas.width = video.videoWidth;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);
        
        if (code) {
            scanning = false;
            processQRCode(code.data);
            return;
        }
    }
    
    requestAnimationFrame(scanQRCode);
}

function processQRCode(qrData) {
    showMessage('تم اكتشاف QR Code، جاري التحقق...', 'info');
    
    // Extract token from URL if it's a URL
    let token = qrData;
    if (qrData.includes('token=')) {
        const urlParams = new URLSearchParams(qrData.split('?')[1]);
        token = urlParams.get('token');
    }
    
    // إنشاء URL مع المعاملات
    const url = '<?php echo e(route("attendance.scan")); ?>?token=' + encodeURIComponent(token);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            // عرض تفاصيل الحضور
            showAttendanceDetails(data);
        } else {
            showMessage(data.message, 'danger');
            // إعادة تشغيل المسح بعد 3 ثواني
            setTimeout(() => {
                if (video.style.display !== 'none') {
                    scanning = true;
                    scanQRCode();
                }
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('حدث خطأ في الاتصال بالخادم', 'danger');
        // إعادة تشغيل المسح
        setTimeout(() => {
            if (video.style.display !== 'none') {
                scanning = true;
                scanQRCode();
            }
        }, 3000);
    });
}

function processManualInput() {
    const input = document.getElementById('manual-qr-input');
    const qrData = input.value.trim();
    
    if (!qrData) {
        showMessage('يرجى إدخال الكود', 'warning');
        return;
    }
    
    processQRCode(qrData);
    input.value = '';
}

function showMessage(message, type) {
    const container = document.getElementById('status-messages');
    const alertClass = `alert-${type}`;
    const iconClass = type === 'success' ? 'fa-check-circle' : 
                     type === 'danger' ? 'fa-exclamation-circle' :
                     type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
    
    container.innerHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas ${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // إزالة الرسالة بعد 5 ثواني
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

function showAttendanceDetails(data) {
    const container = document.getElementById('status-messages');
    container.innerHTML += `
        <div class="card border-success mt-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    تم تسجيل الحضور بنجاح
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <strong>الدرس:</strong> ${data.lesson_name}
                    </div>
                    <div class="col-sm-6">
                        <strong>المادة:</strong> ${data.subject || 'غير محدد'}
                    </div>
                    <div class="col-sm-6">
                        <strong>الوقت:</strong> ${data.time}
                    </div>
                    <div class="col-sm-6">
                        <strong>ID الحضور:</strong> ${data.attendance_id}
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <a href="<?php echo e(route('student.dashboard')); ?>" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        العودة للوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    `;
}

// التحقق من دعم الكاميرا
if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
    showMessage('المتصفح لا يدعم الوصول للكاميرا', 'warning');
    document.getElementById('start-camera').disabled = true;
}

// تنظيف عند مغادرة الصفحة
window.addEventListener('beforeunload', function() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});

// معالج للإدخال اليدوي بالضغط على Enter
document.getElementById('manual-qr-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        processManualInput();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\osaidsalah002\Documents\BasmahApp-2\resources\views/student/qr-scanner.blade.php ENDPATH**/ ?>