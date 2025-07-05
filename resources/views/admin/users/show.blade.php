@extends('layouts.admin')

@section('title', 'عرض بيانات المستخدم')

@section('content')
<div class="container-fluid">
    <div class="anwar-header islamic-pattern-enhanced">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="anwar-title">
                <i class="fas fa-user anwar-text-gold me-2"></i>
                بيانات المستخدم
            </h1>
            <div>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn anwar-btn-warning me-2">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn anwar-btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- بيانات المستخدم الأساسية -->
        <div class="col-lg-8">
            <div class="card anwar-card islamic-pattern-subtle shadow mb-4">
                <div class="card-header anwar-card-header">
                    <h5 class="card-title mb-0 anwar-text-gold">
                        <i class="fas fa-id-card me-2"></i>البيانات الأساسية
                    </h5>
                </div>
                <div class="card-body anwar-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless anwar-table">
                                <tr>
                                    <th class="anwar-text-gold" style="width: 150px;">الاسم:</th>
                                    <td class="anwar-text-dark">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th class="anwar-text-gold">البريد الإلكتروني:</th>
                                    <td class="anwar-text-dark">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th class="anwar-text-gold">رقم الهاتف:</th>
                                    <td class="anwar-text-dark">{{ $user->phone ?? 'غير مسجل' }}</td>
                                </tr>
                                @if($user->student_id)
                                <tr>
                                    <th class="anwar-text-gold">رقم الطالب:</th>
                                    <td class="anwar-text-dark">{{ $user->student_id }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless anwar-table">
                                <tr>
                                    <th class="anwar-text-gold" style="width: 150px;">الصلاحية:</th>
                                    <td>
                                        @switch($user->role)
                                            @case('admin')
                                                <span class="badge anwar-badge-red">
                                                    <i class="fas fa-crown me-1"></i>مدير
                                                </span>
                                                @break
                                            @case('teacher')
                                                <span class="badge anwar-badge-blue">
                                                    <i class="fas fa-chalkboard-teacher me-1"></i>معلم
                                                </span>
                                                @break
                                            @case('student')
                                                <span class="badge anwar-badge-green">
                                                    <i class="fas fa-user-graduate me-1"></i>طالب
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th class="anwar-text-gold">تاريخ التسجيل:</th>
                                    <td class="anwar-text-dark">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="anwar-text-gold">آخر تحديث:</th>
                                    <td class="anwar-text-dark">{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="anwar-text-gold">تفعيل البريد:</th>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge anwar-badge-green">
                                                <i class="fas fa-check me-1"></i>مفعّل
                                            </span>
                                            <small class="anwar-text-muted d-block">{{ $user->email_verified_at->format('Y-m-d') }}</small>
                                        @else
                                            <span class="badge anwar-badge-orange">
                                                <i class="fas fa-exclamation-triangle me-1"></i>غير مفعّل
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإحصائيات -->
        <div class="col-lg-4">
            <div class="card anwar-card islamic-pattern-subtle shadow mb-4">
                <div class="card-header anwar-card-header">
                    <h5 class="card-title mb-0 anwar-text-gold">
                        <i class="fas fa-chart-pie me-2"></i>الإحصائيات
                    </h5>
                </div>
                <div class="card-body anwar-card-body">
                    @if($user->role === 'teacher' && isset($stats))
                        <div class="stats-item mb-3">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded anwar-border-gold">
                                <div>
                                    <i class="fas fa-book anwar-text-gold fa-2x"></i>
                                </div>
                                <div class="text-end">
                                    <h4 class="mb-0 anwar-text-dark">{{ $stats['total_lessons'] }}</h4>
                                    <small class="anwar-text-muted">إجمالي الدروس</small>
                                </div>
                            </div>
                        </div>
                        <div class="stats-item">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded anwar-border-blue">
                                <div>
                                    <i class="fas fa-users anwar-text-blue fa-2x"></i>
                                </div>
                                <div class="text-end">
                                    <h4 class="mb-0 anwar-text-dark">{{ $stats['total_students'] }}</h4>
                                    <small class="anwar-text-muted">إجمالي الطلاب</small>
                                </div>
                            </div>
                        </div>
                    @elseif($user->role === 'student' && isset($stats))
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="stats-item p-2 text-center rounded anwar-border-green">
                                    <i class="fas fa-book anwar-text-green"></i>
                                    <h6 class="mb-0 anwar-text-dark">{{ $stats['total_lessons'] }}</h6>
                                    <small class="anwar-text-muted">الدروس</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stats-item p-2 text-center rounded anwar-border-blue">
                                    <i class="fas fa-clipboard-check anwar-text-blue"></i>
                                    <h6 class="mb-0 anwar-text-dark">{{ $stats['total_attendances'] }}</h6>
                                    <small class="anwar-text-muted">سجلات الحضور</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-item p-2 text-center rounded anwar-border-green">
                                    <i class="fas fa-check anwar-text-green"></i>
                                    <h6 class="mb-0 anwar-text-dark">{{ $stats['present_count'] }}</h6>
                                    <small class="anwar-text-muted">حاضر</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-item p-2 text-center rounded anwar-border-orange">
                                    <i class="fas fa-clock anwar-text-orange"></i>
                                    <h6 class="mb-0 anwar-text-dark">{{ $stats['late_count'] }}</h6>
                                    <small class="anwar-text-muted">متأخر</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stats-item p-2 text-center rounded anwar-border-red">
                                    <i class="fas fa-times anwar-text-red"></i>
                                    <h6 class="mb-0 anwar-text-dark">{{ $stats['absent_count'] }}</h6>
                                    <small class="anwar-text-muted">غائب</small>
                                </div>
                            </div>
                        </div>

                        @if($stats['total_attendances'] > 0)
                        <div class="mt-3 p-3 rounded" style="background: linear-gradient(135deg, rgba(218, 165, 32, 0.1), rgba(0, 128, 128, 0.1));">
                            <h6 class="anwar-text-gold mb-2">نسبة الحضور</h6>
                            @php
                                $attendanceRate = round(($stats['present_count'] + $stats['late_count']) / $stats['total_attendances'] * 100, 1);
                            @endphp
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar" 
                                     style="width: {{ $attendanceRate }}%; background: var(--anwar-gradient-gold);" 
                                     role="progressbar"></div>
                            </div>
                            <small class="anwar-text-dark">{{ $attendanceRate }}% معدل الحضور</small>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar anwar-text-muted fa-3x mb-3"></i>
                            <p class="anwar-text-muted">لا توجد إحصائيات متاحة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($user->role === 'teacher')
    <!-- دروس المعلم -->
    <div class="card anwar-card islamic-pattern-subtle shadow">
        <div class="card-header anwar-card-header">
            <h5 class="card-title mb-0 anwar-text-gold">
                <i class="fas fa-chalkboard-teacher me-2"></i>دروس المعلم
            </h5>
        </div>
        <div class="card-body anwar-card-body">
            @php
                $teacherLessons = $user->teachingLessons()->with('students')->get();
            @endphp
            
            @if($teacherLessons->count() > 0)
                <div class="row">
                    @foreach($teacherLessons as $lesson)
                    <div class="col-md-6 mb-3">
                        <div class="lesson-card p-3 rounded anwar-border-gold">
                            <h6 class="anwar-text-gold mb-2">{{ $lesson->subject }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="anwar-text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        @switch($lesson->day_of_week)
                                            @case('sunday') الأحد @break
                                            @case('monday') الإثنين @break
                                            @case('tuesday') الثلاثاء @break
                                            @case('wednesday') الأربعاء @break
                                            @case('thursday') الخميس @break
                                            @case('friday') الجمعة @break
                                            @case('saturday') السبت @break
                                        @endswitch
                                    </small>
                                    <br>
                                    <small class="anwar-text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge anwar-badge-blue">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $lesson->students->count() }} طالب
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-book-open anwar-text-muted fa-3x mb-3"></i>
                    <p class="anwar-text-muted">لا يوجد دروس مسجلة لهذا المعلم</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    @if($user->role === 'student')
    <!-- دروس الطالب -->
    <div class="card anwar-card islamic-pattern-subtle shadow">
        <div class="card-header anwar-card-header">
            <h5 class="card-title mb-0 anwar-text-gold">
                <i class="fas fa-user-graduate me-2"></i>دروس الطالب
            </h5>
        </div>
        <div class="card-body anwar-card-body">
            @php
                $studentLessons = $user->lessons()->with('teacher')->get();
            @endphp
            
            @if($studentLessons->count() > 0)
                <div class="row">
                    @foreach($studentLessons as $lesson)
                    <div class="col-md-6 mb-3">
                        <div class="lesson-card p-3 rounded anwar-border-green">
                            <h6 class="anwar-text-green mb-2">{{ $lesson->subject }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="anwar-text-muted">
                                        <i class="fas fa-user-tie me-1"></i>
                                        {{ $lesson->teacher->name }}
                                    </small>
                                    <br>
                                    <small class="anwar-text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        @switch($lesson->day_of_week)
                                            @case('sunday') الأحد @break
                                            @case('monday') الإثنين @break
                                            @case('tuesday') الثلاثاء @break
                                            @case('wednesday') الأربعاء @break
                                            @case('thursday') الخميس @break
                                            @case('friday') الجمعة @break
                                            @case('saturday') السبت @break
                                        @endswitch
                                        {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    @php
                                        $userAttendance = $user->attendances()->where('lesson_id', $lesson->id)->latest()->first();
                                    @endphp
                                    @if($userAttendance)
                                        @switch($userAttendance->status)
                                            @case('present')
                                                <span class="badge anwar-badge-green">
                                                    <i class="fas fa-check me-1"></i>حاضر
                                                </span>
                                                @break
                                            @case('late')
                                                <span class="badge anwar-badge-orange">
                                                    <i class="fas fa-clock me-1"></i>متأخر
                                                </span>
                                                @break
                                            @case('absent')
                                                <span class="badge anwar-badge-red">
                                                    <i class="fas fa-times me-1"></i>غائب
                                                </span>
                                                @break
                                        @endswitch
                                    @else
                                        <span class="badge anwar-badge-gray">
                                            <i class="fas fa-question me-1"></i>لا يوجد حضور
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-book-open anwar-text-muted fa-3x mb-3"></i>
                    <p class="anwar-text-muted">لا يوجد دروس مسجلة لهذا الطالب</p>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<style>
.stats-item {
    transition: all 0.3s ease;
}

.stats-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.lesson-card {
    transition: all 0.3s ease;
    background: var(--anwar-white);
}

.lesson-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(218, 165, 32, 0.2);
}
</style>
@endsection
