@extends('layouts.admin')

@section('title', 'عرض QR Code - ' . $lesson->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>
                        QR Code للحضور - {{ $lesson->name }}
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="lesson-info mb-4">
                                <h6 class="text-muted">معلومات الدرس</h6>
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between">
                                        <strong>اسم الدرس:</strong>
                                        <span>{{ $lesson->name }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <strong>المادة:</strong>
                                        <span>{{ $lesson->subject }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <strong>المعلم:</strong>
                                        <span>{{ $lesson->teacher->name }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <strong>اليوم:</strong>
                                        <span>
                                            @switch($lesson->day_of_week)
                                                @case('sunday') الأحد @break
                                                @case('monday') الاثنين @break
                                                @case('tuesday') الثلاثاء @break
                                                @case('wednesday') الأربعاء @break
                                                @case('thursday') الخميس @break
                                                @case('friday') الجمعة @break
                                                @case('saturday') السبت @break
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <strong>الوقت:</strong>
                                        <span>{{ $lesson->start_time->format('H:i') }} - {{ $lesson->end_time->format('H:i') }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <strong>عدد الطلاب:</strong>
                                        <span>{{ $lesson->students()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="attendance-info">
                                <h6 class="text-muted">معلومات الحضور</h6>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>حاضر:</strong> مسح QR خلال أول 15 دقيقة من الدرس
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>متأخر:</strong> مسح QR بعد أول 15 دقيقة من الدرس
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    QR Code صالح طوال مدة الدرس ويمكن مسحه من عدة طلاب
                                </div>
                            </div>
                        </div>
                          <div class="col-md-6">
                            <div class="qr-container">
                                <h6 class="text-muted mb-3">QR Code للحضور</h6>
                                
                                <div class="qr-status mb-3">
                                    <div id="token-status" class="alert alert-info">
                                        <i class="fas fa-clock me-2"></i>
                                        <span id="status-text">جاري تحميل معلومات QR...</span>
                                    </div>
                                    <div id="timer-display" class="text-center mb-2">
                                        <div class="timer-container p-3 bg-light rounded">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fas fa-stopwatch text-primary me-2" style="font-size: 1.5rem;"></i>
                                                <div class="timer-info">
                                                    <div class="timer-label text-muted small">الوقت المتبقي للدرس</div>
                                                    <span class="badge bg-primary fs-4" id="countdown-timer" style="font-family: 'Courier New', monospace;">--:--</span>
                                                </div>
                                            </div>
                                            <div id="lesson-progress" class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <div class="text-center mt-1">
                                                <small class="text-muted" id="lesson-time-info">وقت الدرس: {{ $lesson->start_time->format('H:i') }} - {{ $lesson->end_time->format('H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="qr-code-display bg-light p-4 rounded" id="qr-container">
                                    <div class="text-center">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">جاري التحميل...</span>
                                        </div>
                                        <p class="mt-2">جاري توليد QR Code...</p>
                                    </div>
                                </div>
                                
                                <p class="text-muted mt-3">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>QR Code صالح طوال مدة الدرس</strong><br>
                                    <small>يمكن لجميع الطلاب مسح نفس QR للتسجيل - حاضر (أول 15 دقيقة) أو متأخر (بعد ذلك)</small>
                                </p>
                                
                                <div class="mt-4">
                                    <button class="btn btn-success btn-lg" onclick="generateNewQR()" id="refresh-btn">
                                        <i class="fas fa-sync-alt me-2"></i>
                                        توليد QR جديد
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="attendance-status">
                                <h6 class="text-muted">حالة الحضور اليوم</h6>
                                <div id="attendance-stats" class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h4 id="present-count">0</h4>
                                                <small>حاضر</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h4 id="late-count">0</h4>
                                                <small>متأخر</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body text-center">
                                                <h4 id="absent-count">0</h4>
                                                <small>غائب</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h4 id="total-students">{{ $lesson->students()->count() }}</h4>
                                                <small>إجمالي الطلاب</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة للدروس
                            </a>
                        @else
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة للوحة المعلم
                            </a>
                        @endif
                        
                        <div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.lessons.show', $lesson) }}" class="btn btn-info">
                                    <i class="fas fa-eye me-2"></i>
                                    عرض الدرس
                                </a>
                                <a href="{{ route('admin.attendances.index') }}?lesson_id={{ $lesson->id }}" class="btn btn-primary">
                                    <i class="fas fa-list me-2"></i>
                                    مراجعة الحضور
                                </a>
                            @else
                                <a href="{{ route('teacher.lessons.show', $lesson) }}" class="btn btn-info">
                                    <i class="fas fa-eye me-2"></i>
                                    عرض الدرس
                                </a>
                                <a href="{{ route('teacher.lessons.index') }}" class="btn btn-primary">
                                    <i class="fas fa-list me-2"></i>
                                    دروسي
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timer-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.timer-container:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-1px);
}

#countdown-timer {
    font-size: 1.8rem !important;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    min-width: 120px;
    display: inline-block;
}

.timer-label {
    font-weight: 500;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.progress {
    background-color: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 1s ease-in-out, background-color 0.3s ease;
}

.alert {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pulse-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.timer-warning {
    animation: blink 1s infinite;
}

@keyframes blink {
    50% { opacity: 0.5; }
}

/* تحسين الألوان للمواضيع المظلمة */
@media (prefers-color-scheme: dark) {
    .timer-container {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        border-color: #4a5568;
        color: #e2e8f0;
    }
}
</style>

<script>
let currentTimer;
let tokenData = null;

// Initialize QR display
document.addEventListener('DOMContentLoaded', function() {
    checkQRStatus();
    // تحديث حالة QR كل 10 ثوانٍ
    setInterval(checkQRStatus, 10000);
});

function checkQRStatus() {
    fetch('{{ route("admin.lessons.qr.info", $lesson) }}')
        .then(response => response.json())
        .then(data => {
            tokenData = data;
            updateQRDisplay(data);
            if (data.has_valid_token && data.can_generate_qr) {
                loadQRImage();
                // استخدام الوقت المتبقي للدرس
                const countdownMins = data.lesson_remaining_minutes || data.token_remaining_minutes || 0;
                startCountdown(countdownMins);
            } else {
                showExpiredQR();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorQR();
        });
}

function updateQRDisplay(data) {
    const statusEl = document.getElementById('status-text');
    const timerEl = document.getElementById('countdown-timer');
    const alertEl = document.getElementById('token-status');
    const refreshBtn = document.getElementById('refresh-btn');
    
    if (data.can_generate_qr) {
        if (data.has_valid_token) {
            statusEl.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>QR Code نشط وجاهز للاستخدام';
            alertEl.className = 'alert alert-success';
            // استخدام الوقت المتبقي للدرس بدلاً من token
            const remainingMins = data.lesson_remaining_minutes || data.token_remaining_minutes || 0;
            
            // عرض معلومات إضافية
            const currentTime = new Date().toLocaleTimeString('ar-SA', {hour: '2-digit', minute: '2-digit'});
            let statusMessage = `QR Code نشط - الوقت الحالي: ${currentTime}`;
            
            if (remainingMins <= 15) {
                statusMessage += ' - <span class="text-warning"><strong>قارب الدرس على الانتهاء!</strong></span>';
            } else if (remainingMins <= 30) {
                statusMessage += ' - <span class="text-info">متبقي نصف ساعة على انتهاء الدرس</span>';
            }
            
            statusEl.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>' + statusMessage;
            
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>توليد QR جديد';
        } else {
            statusEl.innerHTML = '<i class="fas fa-exclamation-triangle text-warning me-1"></i>QR Code منتهي الصلاحية - يحتاج توليد جديد';
            alertEl.className = 'alert alert-warning';
            timerEl.textContent = '00:00';
            timerEl.className = 'badge bg-secondary fs-4';
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>توليد QR جديد';
        }
    } else {
        // QR غير متاح في الوقت الحالي
        const message = data.qr_availability_message || 'QR Code غير متاح في الوقت الحالي';
        statusEl.innerHTML = '<i class="fas fa-info-circle text-info me-1"></i>' + message;
        alertEl.className = 'alert alert-info';
        timerEl.textContent = '--:--';
        timerEl.className = 'badge bg-secondary fs-4';
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<i class="fas fa-clock me-2"></i>غير متاح حالياً';
        
        if (data.minutes_until_available && data.minutes_until_available > 0) {
            const hours = Math.floor(data.minutes_until_available / 60);
            const mins = data.minutes_until_available % 60;
            let timeText = '';
            if (hours > 0) {
                timeText = `${hours}:${mins.toString().padStart(2, '0')}:00`;
            } else {
                timeText = `${mins.toString().padStart(2, '0')}:00`;
            }
            timerEl.textContent = timeText + ' متبقي';
            timerEl.className = 'badge bg-info fs-4';
        }
    }
}

function loadQRImage() {
    const container = document.getElementById('qr-container');
    if (tokenData && tokenData.can_generate_qr) {
        // في بيئة التطوير، استخدم quick-qr route
        const qrUrl = `{{ url('/quick-qr/' . $lesson->id) }}?t=${Date.now()}`;
        container.innerHTML = `
            <img src="${qrUrl}" 
                 alt="QR Code" 
                 class="img-fluid" 
                 style="max-width: 300px;"
                 onerror="showErrorQR()">
        `;
    } else if (!tokenData.can_generate_qr) {
        showNotAvailableQR(tokenData.qr_availability_message);
    }
}

function showExpiredQR() {
    const container = document.getElementById('qr-container');
    const currentTime = new Date().toLocaleTimeString('ar-SA', {hour: '2-digit', minute: '2-digit'});
    
    container.innerHTML = `
        <div class="text-center p-4">
            <i class="fas fa-clock text-warning" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-warning">انتهى وقت الدرس</h5>
            <p class="text-muted">انتهت صلاحية QR Code في الوقت: ${currentTime}</p>
            <small class="text-info">QR Code غير صالح بعد انتهاء وقت الدرس المحدد</small>
        </div>
    `;
    
    // تحديث المؤقت ليظهر انتهاء الوقت
    const timerEl = document.getElementById('countdown-timer');
    if (timerEl) {
        timerEl.textContent = '00:00';
        timerEl.className = 'badge bg-danger fs-4';
    }
    
    // تحديث شريط التقدم
    const progressBar = document.querySelector('#lesson-progress .progress-bar');
    if (progressBar) {
        progressBar.style.width = '100%';
        progressBar.className = 'progress-bar bg-danger';
    }
    
    // تحديث معلومات الوقت
    const infoElement = document.getElementById('lesson-time-info');
    if (infoElement) {
        infoElement.innerHTML = `انتهى الدرس في: ${currentTime} - <span class="text-danger">QR Code غير نشط</span>`;
    }
    
    clearTimeout(currentTimer);
}

function showNotAvailableQR(message) {
    const container = document.getElementById('qr-container');
    container.innerHTML = `
        <div class="text-center p-4">
            <i class="fas fa-calendar-times text-info" style="font-size: 4rem;"></i>
            <h5 class="mt-3">QR Code غير متاح</h5>
            <p class="text-muted">${message || 'QR Code متاح فقط في أوقات الدرس المحددة'}</p>
        </div>
    `;
    clearTimeout(currentTimer);
}

function showErrorQR() {
    const container = document.getElementById('qr-container');
    container.innerHTML = `
        <div class="text-center p-4">
            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
            <h5 class="mt-3">خطأ في تحميل QR Code</h5>
            <p class="text-muted">يرجى المحاولة مرة أخرى</p>
        </div>
    `;
}

function generateNewQR() {
    const refreshBtn = document.getElementById('refresh-btn');
    refreshBtn.disabled = true;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التوليد...';
    
    fetch('{{ route("admin.lessons.qr.refresh", $lesson) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إعادة تحميل معلومات QR
            setTimeout(() => {
                checkQRStatus();
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>توليد QR جديد';
            }, 1000);
        } else {
            alert('حدث خطأ في توليد QR جديد');
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>توليد QR جديد';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
        refreshBtn.disabled = false;
        refreshBtn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>توليد QR جديد';
    });
}

function startCountdown(minutes) {
    clearTimeout(currentTimer);
    let totalSeconds = Math.max(0, minutes * 60);
    const originalMinutes = minutes;
    
    function updateTimer() {
        const hours = Math.floor(totalSeconds / 3600);
        const mins = Math.floor((totalSeconds % 3600) / 60);
        const secs = totalSeconds % 60;
        
        // تحديث عرض المؤقت
        let timerText = '';
        if (hours > 0) {
            timerText = `${hours}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        } else {
            timerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        
        const timerElement = document.getElementById('countdown-timer');
        if (timerElement) {
            timerElement.textContent = timerText;
            
            // إزالة جميع الفئات المتحركة أولاً
            timerElement.classList.remove('pulse-animation', 'timer-warning');
            
            // تغيير اللون والتأثيرات حسب الوقت المتبقي
            if (totalSeconds <= 60) { // آخر دقيقة
                timerElement.className = 'badge bg-danger fs-4 timer-warning';
                // إضافة تأثير الوميض
                if (!timerElement.classList.contains('timer-warning')) {
                    timerElement.classList.add('timer-warning');
                }
            } else if (totalSeconds <= 300) { // آخر 5 دقائق
                timerElement.className = 'badge bg-danger fs-4 pulse-animation';
            } else if (totalSeconds <= 900) { // آخر 15 دقيقة
                timerElement.className = 'badge bg-warning fs-4';
            } else {
                timerElement.className = 'badge bg-primary fs-4';
            }
        }
        
        // تحديث شريط التقدم
        const progressBar = document.querySelector('#lesson-progress .progress-bar');
        if (progressBar && originalMinutes > 0) {
            const progressPercent = Math.max(0, ((originalMinutes * 60 - totalSeconds) / (originalMinutes * 60)) * 100);
            progressBar.style.width = progressPercent + '%';
            
            // تغيير لون شريط التقدم
            if (progressPercent >= 80) {
                progressBar.className = 'progress-bar bg-warning';
            } else if (progressPercent >= 50) {
                progressBar.className = 'progress-bar bg-info';
            } else {
                progressBar.className = 'progress-bar bg-success';
            }
        }
        
        // تحديث معلومات إضافية
        const infoElement = document.getElementById('lesson-time-info');
        if (infoElement && totalSeconds > 0) {
            const currentTime = new Date().toLocaleTimeString('ar-SA', {hour: '2-digit', minute: '2-digit'});
            if (totalSeconds <= 900) { // آخر 15 دقيقة
                infoElement.innerHTML = `الوقت الحالي: ${currentTime} - <span class="text-warning">يُنصح بإنهاء الدرس قريباً</span>`;
            } else {
                infoElement.innerHTML = `الوقت الحالي: ${currentTime} - وقت الدرس: {{ $lesson->start_time->format('H:i') }} - {{ $lesson->end_time->format('H:i') }}`;
            }
        }
        
        if (totalSeconds <= 0) {
            showExpiredQR();
            return;
        }
        
        totalSeconds--;
        currentTimer = setTimeout(updateTimer, 1000);
    }
    
    updateTimer();
}

function formatTime(minutes) {
    const mins = Math.floor(minutes);
    const secs = Math.round((minutes - mins) * 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

// Update attendance stats every 30 seconds
setInterval(function() {
    updateAttendanceStats();
}, 30000);

function updateAttendanceStats() {
    // يمكن إضافة تحديث إحصائيات الحضور هنا لاحقاً
}
</script>
@endsection
