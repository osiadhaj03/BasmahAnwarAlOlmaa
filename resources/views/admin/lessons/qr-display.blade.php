@extends('layouts.admin')

@section('title', 'عرض QR Code - ' . $lesson->name)

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

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
                                        <i class="fas fa-qrcode me-2"></i>
                                        <span id="status-text">جاري تحميل QR Code...</span>
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
                                    <button class="btn btn-success btn-lg" onclick="refreshQR()" id="refresh-btn">
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

/* تحسين الألوان للمواضيع المظلمة */
@media (prefers-color-scheme: dark) {
    .qr-container {
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
    // توليد QR مباشرة عند تحميل الصفحة
    generateQRCode();
});

function generateQRCode() {
    // إعداد رسالة التحميل
    document.getElementById('status-text').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري توليد QR Code...';
    document.getElementById('token-status').className = 'alert alert-info';
    
    // تحديد الـ route المناسب حسب نوع المستخدم
    @if(auth()->user()->isAdmin())
        const qrRoute = '{{ route("admin.lessons.qr.generate", $lesson) }}';
    @else
        const qrRoute = '{{ route("teacher.lessons.qr.generate", $lesson) }}';
    @endif
    
    // توليد QR Code مباشرة
    fetch(qrRoute, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('status-text').innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>QR Code جاهز للاستخدام';
            document.getElementById('token-status').className = 'alert alert-success';
            document.getElementById('qr-container').innerHTML = data.qr_code;
        } else {
            throw new Error(data.error || 'حدث خطأ في توليد QR Code');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('status-text').innerHTML = '<i class="fas fa-exclamation-triangle text-danger me-1"></i>' + error.message;
        document.getElementById('token-status').className = 'alert alert-danger';
    });
}

// إزالة الدوال المعقدة - سنستخدم generateQRCode فقط

    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('status-text').innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>QR Code جاهز للاستخدام';
            document.getElementById('token-status').className = 'alert alert-success';
            document.getElementById('qr-container').innerHTML = data.qr_code;
        } else {
            throw new Error(data.error || 'حدث خطأ في توليد QR Code');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('status-text').innerHTML = '<i class="fas fa-exclamation-triangle text-danger me-1"></i>' + error.message;
        document.getElementById('token-status').className = 'alert alert-danger';
    });
}

function refreshQR() {
    generateQRCode();
}

</script>
@endsection
