@extends('layouts.student')

@section('title', 'لوحة الطالب')

@section('content')
<div class="row">
    <!-- Statistics Column -->
    <div class="col-lg-4">
        <div class="floating-stats">
            <!-- Quick Stats -->
            <div class="card stats-card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie me-2"></i>
                        إحصائياتي
                    </h5>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="border-end border-light">
                                <h3 class="mb-0">{{ $totalLessons }}</h3>
                                <small class="opacity-75">دروسي</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0">{{ $attendanceRate }}%</h3>
                            <small class="opacity-75">معدل الحضور</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Breakdown -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-clipboard-list me-2"></i>
                        تفصيل الحضور
                    </h6>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                حاضر
                            </span>
                            <span class="badge bg-success">{{ $presentCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning">
                                <i class="fas fa-clock me-1"></i>
                                متأخر
                            </span>
                            <span class="badge bg-warning">{{ $lateCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger">
                                <i class="fas fa-times-circle me-1"></i>
                                غائب
                            </span>
                            <span class="badge bg-danger">{{ $absentCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance -->
            @if($recentAttendances->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-history me-2"></i>
                        آخر سجلات الحضور
                    </h6>
                    <div class="mt-3">
                        @foreach($recentAttendances as $attendance)
                        <div class="attendance-item p-2 rounded mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="fw-bold">{{ $attendance->lesson->name ?? $attendance->lesson->subject }}</small>
                                    <br>
                                    <small class="text-muted">{{ $attendance->date }}</small>
                                </div>
                                <div>
                                    @if($attendance->status === 'present')
                                        <span class="badge bg-success">حاضر</span>
                                    @elseif($attendance->status === 'late')
                                        <span class="badge bg-warning">متأخر</span>
                                    @else
                                        <span class="badge bg-danger">غائب</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>    <!-- Lessons Column -->
    <div class="col-lg-8">
        <!-- QR Code Scanner Section -->
        <div class="card mb-4 border-success">
            <div class="card-body text-center">
                <h5 class="card-title text-success">
                    <i class="fas fa-qrcode me-2"></i>
                    مسح QR Code للحضور
                </h5>
                <p class="text-muted mb-3">
                    امسح الكود الموجود في قاعة الدرس لتسجيل حضورك بسرعة
                </p>
                <a href="{{ route('student.qr.scanner') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-camera me-2"></i>
                    فتح ماسح QR Code
                </a>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        يجب مسح الكود خلال أول 15 دقيقة من بداية الدرس
                    </small>
                </div>
            </div>
        </div>

        <!-- Today's Lessons with Check-in -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-primary mb-0">
                        <i class="fas fa-calendar-day me-2"></i>
                        دروس اليوم
                    </h5>
                    <a href="https://basmah.anwaralolmaa.com/qr-scanner" class="btn btn-check-in text-white">
                        <i class="fas fa-qrcode me-2"></i>
                        مسح رمز QR للحضور
                    </a>
                </div>
                
                <!-- Debug Information -->
                <div class="alert alert-info mb-3">
                    <small>
                        <strong>معلومات التصحيح:</strong><br>
                        التاريخ: {{ $today->format('Y-m-d') }}<br>
                        اليوم: {{ $currentDayOfWeek }}<br>
                        عدد الدروس اليوم: {{ $todayLessons->count() }}<br>
                        إجمالي الدروس: {{ $lessons->count() }}
                    </small>
                </div>
                
                @if($todayLessons->count() > 0)
                <div class="row mt-3">
                    @foreach($todayLessons as $lesson)                    @php
                        $hasCheckedInToday = $lesson->attendances->where('date', \Carbon\Carbon::today())->count() > 0;
                        $currentTime = \Carbon\Carbon::now();
                        
                        // إصلاح تحويل الوقت
                        $startTime = null;
                        $endTime = null;
                        
                        if ($lesson->start_time) {
                            try {
                                // محاولة تحويل من datetime كامل أولاً
                                $startTime = \Carbon\Carbon::parse($lesson->start_time);
                            } catch (\Exception $e) {
                                try {
                                    // إذا فشل، محاولة تحويل من time فقط
                                    $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $lesson->start_time);
                                } catch (\Exception $e2) {
                                    $startTime = null;
                                }
                            }
                        }
                        
                        if ($lesson->end_time) {
                            try {
                                $endTime = \Carbon\Carbon::parse($lesson->end_time);
                            } catch (\Exception $e) {
                                try {
                                    $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $lesson->end_time);
                                } catch (\Exception $e2) {
                                    $endTime = null;
                                }
                            }
                        }
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="card lesson-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="card-title mb-1">{{ $lesson->name ?? $lesson->subject }}</h6>                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $lesson->teacher->name }}
                                        </small>
                                    </div>
                                    @if($startTime)
                                    <span class="badge bg-info">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $startTime->format('H:i') }}
                                    </span>
                                    @endif
                                </div>
                                
                                <!-- Lesson Debug Info -->
                                <div class="alert alert-light mb-3">
                                    <small>
                                        يوم الدرس: {{ $lesson->day_of_week }}<br>
                                        الوقت الحالي: {{ $currentTime->format('H:i') }}<br>
                                        @if($startTime && $endTime)
                                            بداية: {{ $startTime->format('H:i') }} - نهاية: {{ $endTime->format('H:i') }}
                                        @endif
                                    </small>
                                </div>
                                
                                @if($lesson->description)
                                <p class="card-text small text-muted mb-3">{{ Str::limit($lesson->description, 80) }}</p>
                                @endif
                                
                                <!-- تم إزالة أزرار تسجيل الحضور من دروس اليوم -->
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    لا توجد دروس مجدولة لهذا اليوم ({{ $currentDayOfWeek }})
                </div>
                @endif
            </div>
        </div>

        <!-- All My Lessons -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-book me-2"></i>
                    جميع دروسي ({{ $totalLessons }})
                </h5>
                @if($lessons->count() > 0)
                <div class="row mt-3">
                    @foreach($lessons as $lesson)
                    @php
                        $lessonAttendances = $lesson->attendances;
                        $lessonTotal = $lessonAttendances->count();
                        $lessonPresent = $lessonAttendances->where('status', 'present')->count();
                        $lessonRate = $lessonTotal > 0 ? round(($lessonPresent / $lessonTotal) * 100, 1) : 0;
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="card lesson-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-1">{{ $lesson->name ?? $lesson->subject }}</h6>
                                    <span class="status-badge bg-light text-dark">
                                        {{ $lessonRate }}%
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>
                                        {{ $lesson->teacher->name }}                                    </small>
                                    @if($lesson->start_time)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                                    </small>
                                    @endif
                                </div>

                                @if($lesson->description)
                                <p class="card-text small text-muted mb-3">{{ Str::limit($lesson->description, 60) }}</p>
                                @endif

                                <div class="row text-center small">
                                    <div class="col-4">
                                        <div class="text-primary fw-bold">{{ $lessonTotal }}</div>
                                        <div class="text-muted">إجمالي</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-success fw-bold">{{ $lessonPresent }}</div>
                                        <div class="text-muted">حضور</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-warning fw-bold">{{ $lessonAttendances->where('status', 'late')->count() }}</div>
                                        <div class="text-muted">تأخير</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد دروس مسجل بها</h5>
                    <p class="text-muted">يرجى التواصل مع المعلم أو الإدارة للتسجيل في الدروس</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// تحديث الوقت كل ثانية
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('ar-SA', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
    const timeElement = document.querySelector('.header small:last-child');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

setInterval(updateTime, 1000);

// تأثيرات الحركة للبطاقات
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
        card.style.animation = 'fadeInUp 0.6s ease forwards';
    });
});

// CSS للحركة
const style = document.createElement('style');
style.textContent = `
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
`;
document.head.appendChild(style);
</script>
@endpush
