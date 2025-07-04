@extends('layouts.admin')

@section('title', 'لوحة تحكم المعلم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">مرحباً {{ $teacher->name }}</h1>
        <p class="text-muted mb-0">لوحة تحكم المعلم - {{ now()->format('Y/m/d - H:i') }}</p>
    </div>    <div class="btn-group">
        <a href="{{ route('teacher.lessons.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> إضافة درس جديد
        </a>
        <a href="{{ route('teacher.attendances.index') }}" class="btn btn-primary">
            <i class="fas fa-eye"></i> مراجعة الحضور
        </a>
    </div>
</div>

<!-- الإجراءات السريعة -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-bolt text-warning"></i>
                    الإجراءات السريعة
                </h5>                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <span>إدارة دروسي</span>
                            <small class="text-muted">{{ $stats['total_lessons'] }} درس</small>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('teacher.lessons.create') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <span>إضافة درس جديد</span>
                            <small class="text-muted">إنشاء درس</small>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                            <span>إدارة الحضور</span>
                            <small class="text-muted">عرض السجلات</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الإحصائيات الرئيسية -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-white-75 small">إجمالي الدروس</div>
                        <div class="h2 mb-0">{{ $stats['total_lessons'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-white-75 small">إجمالي الطلاب</div>
                        <div class="h2 mb-0">{{ $stats['total_students'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-graduate fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-white-75 small">دروس اليوم</div>
                        <div class="h2 mb-0">{{ $stats['today_lessons'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-white-75 small">حضور هذا الأسبوع</div>
                        <div class="h2 mb-0">{{ $stats['this_week_attendances'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات الحضور -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie text-primary"></i>
                    إحصائيات الحضور
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-3">
                        <div class="border-end">
                            <div class="h4 text-success mb-0">{{ $attendanceStats['present'] }}</div>
                            <small class="text-muted">حاضر</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="border-end">
                            <div class="h4 text-danger mb-0">{{ $attendanceStats['absent'] }}</div>
                            <small class="text-muted">غائب</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="border-end">
                            <div class="h4 text-warning mb-0">{{ $attendanceStats['late'] }}</div>
                            <small class="text-muted">متأخر</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="h4 text-info mb-0">{{ $attendanceStats['excused'] }}</div>
                        <small class="text-muted">معذور</small>
                    </div>
                </div>
                
                @if($attendanceStats['total'] > 0)
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>معدل الحضور</span>
                        <span class="fw-bold text-success">{{ $attendanceStats['attendance_rate'] }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $attendanceStats['attendance_rate'] }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock text-primary"></i>
                    دروس اليوم
                </h5>
            </div>
            <div class="card-body">
                @if($todayLessons->count() > 0)
                    @foreach($todayLessons as $lesson)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                        <div>
                            <h6 class="mb-1">{{ $lesson->subject }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i>
                                {{ $lesson->start_time }} - {{ $lesson->end_time }}
                            </small>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-users"></i>
                                {{ $lesson->students_count }} طالب
                            </small>
                        </div>                        <div>
                            <a href="{{ route('teacher.attendances.lesson', $lesson) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="مراجعة الحضور">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>لا توجد دروس مجدولة لليوم</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- أداء الطلاب المتميزين -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy text-warning"></i>
                    أداء الطلاب المتميزين
                </h5>
            </div>
            <div class="card-body">
                @if($studentPerformance->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th class="text-center">إجمالي السجلات</th>
                                <th class="text-center">الحضور</th>
                                <th class="text-center">الغياب</th>
                                <th class="text-center">معدل الحضور</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentPerformance as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($student->student_name, 0, 1)) }}
                                        </div>
                                        {{ $student->student_name }}
                                    </div>
                                </td>
                                <td class="text-center">{{ $student->total_records }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $student->present_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $student->absent_count }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="me-2 fw-bold 
                                            @if($student->attendance_percentage >= 90) text-success
                                            @elseif($student->attendance_percentage >= 70) text-warning
                                            @else text-danger
                                            @endif">
                                            {{ $student->attendance_percentage }}%
                                        </span>
                                        <div class="progress progress-sm" style="width: 50px;">
                                            <div class="progress-bar 
                                                @if($student->attendance_percentage >= 90) bg-success
                                                @elseif($student->attendance_percentage >= 70) bg-warning
                                                @else bg-danger
                                                @endif" 
                                                style="width: {{ $student->attendance_percentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-user-graduate fa-3x mb-3"></i>
                        <p>لا توجد سجلات حضور للطلاب بعد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar text-info"></i>
                    إحصائيات الدروس
                </h5>
            </div>
            <div class="card-body">
                @if($lessonStats->count() > 0)
                    @foreach($lessonStats as $stat)
                    <div class="mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">{{ $stat['lesson']->subject }}</h6>
                                <small class="text-muted">
                                    {{ $stat['lesson']->day_of_week }} - {{ $stat['lesson']->start_time }}
                                </small>
                            </div>
                            <span class="badge 
                                @if($stat['attendance_rate'] >= 90) bg-success
                                @elseif($stat['attendance_rate'] >= 70) bg-warning
                                @else bg-danger
                                @endif">
                                {{ $stat['attendance_rate'] }}%
                            </span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar 
                                @if($stat['attendance_rate'] >= 90) bg-success
                                @elseif($stat['attendance_rate'] >= 70) bg-warning
                                @else bg-danger
                                @endif" 
                                style="width: {{ $stat['attendance_rate'] }}%">
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $stat['present_count'] }} من {{ $stat['total_records'] }} سجل
                        </small>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <p>لا توجد إحصائيات للدروس بعد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- آخر سجلات الحضور -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history text-secondary"></i>
                    آخر سجلات الحضور
                </h5>
                <a href="{{ route('teacher.attendances.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>الدرس</th>
                                <th class="text-center">الحالة</th>
                                <th class="text-center">التاريخ</th>
                                <th class="text-center">الوقت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $attendance)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($attendance->student->name, 0, 1)) }}
                                        </div>
                                        {{ $attendance->student->name }}
                                    </div>
                                </td>
                                <td>{{ $attendance->lesson->subject }}</td>
                                <td class="text-center">
                                    @switch($attendance->status)
                                        @case('present')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> حاضر
                                            </span>
                                            @break
                                        @case('absent')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> غائب
                                            </span>
                                            @break
                                        @case('late')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> متأخر
                                            </span>
                                            @break
                                        @case('excused')
                                            <span class="badge bg-info">
                                                <i class="fas fa-user-check"></i> معذور
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-center">{{ $attendance->date->format('Y/m/d') }}</td>
                                <td class="text-center text-muted">{{ $attendance->created_at->format('H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p>لا توجد سجلات حضور حديثة</p>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            تسجيل الحضور يتم عبر الطلاب باستخدام QR Code
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
.stats-card {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.avatar-circle {
    width: 35px;
    height: 35px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.progress-sm {
    height: 6px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.border-end {
    border-left: 1px solid #dee2e6 !important;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

@media (max-width: 768px) {
    .border-end {
        border-left: none !important;
    }
}
</style>
@endsection

@section('scripts')
<script>
// تحديث الوقت كل دقيقة
setInterval(function() {
    const now = new Date();
    const timeString = now.getFullYear() + '/' + 
        String(now.getMonth() + 1).padStart(2, '0') + '/' + 
        String(now.getDate()).padStart(2, '0') + ' - ' +
        String(now.getHours()).padStart(2, '0') + ':' + 
        String(now.getMinutes()).padStart(2, '0');
    
    document.querySelector('.text-muted').textContent = 'لوحة تحكم المعلم - ' + timeString;
}, 60000);

// تحسين تجربة المستخدم
document.addEventListener('DOMContentLoaded', function() {
    // إضافة تأثيرات hover للكروت
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection
