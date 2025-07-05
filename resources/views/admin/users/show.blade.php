@extends('layouts.admin')

@section('title', 'عرض بيانات المستخدم')

@section('content')
<div class="container-fluid" style="background-color: #F8F8F8; min-height: 100vh; padding: 20px;">
    <!-- Header Section -->
    <div class="mb-4 p-4 rounded-lg" style="background: linear-gradient(135deg, #008080 0%, #004d40 100%); color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2" style="color: #B8860B; font-size: 2rem; font-weight: 700;">
                    <i class="fas fa-user me-3"></i>
                    بيانات المستخدم
                </h1>
                <p class="mb-0" style="color: #F8F8F8; opacity: 0.9;">
                    عرض تفاصيل وإحصائيات المستخدم
                </p>
            </div>
            <div>
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="btn me-2" 
                   style="background-color: #B8860B; border: none; color: white; padding: 10px 20px; border-radius: 8px;">
                    <i class="fas fa-edit me-2"></i>تعديل
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="btn" 
                   style="background-color: #008080; border: none; color: white; padding: 10px 20px; border-radius: 8px;">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- بيانات المستخدم الأساسية -->
        <div class="col-lg-8">
            <div class="card shadow-sm" style="border: none; border-radius: 12px; background-color: white;">
                <div class="card-header" style="background-color: #008080; border-radius: 12px 12px 0 0; border: none;">
                    <h5 class="mb-0" style="color: #B8860B; font-weight: 600;">
                        <i class="fas fa-id-card me-2"></i>البيانات الأساسية
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label style="color: #008080; font-weight: 600; display: block; margin-bottom: 5px;">الاسم</label>
                                <p style="color: #333333; margin: 0; font-size: 1.1rem;">{{ $user->name }}</p>
                            </div>
                            <div class="info-item mb-3">
                                <label style="color: #008080; font-weight: 600; display: block; margin-bottom: 5px;">البريد الإلكتروني</label>
                                <p style="color: #333333; margin: 0;">{{ $user->email }}</p>
                            </div>
                            <div class="info-item mb-3">
                                <label style="color: #008080; font-weight: 600; display: block; margin-bottom: 5px;">رقم الهاتف</label>
                                <p style="color: #333333; margin: 0;">{{ $user->phone ?? 'غير مسجل' }}</p>
                            </div>
                            @if($user->student_id)
                            <div class="info-item mb-3">
                                <label style="color: #008080; font-weight: 600; display: block; margin-bottom: 5px;">رقم الطالب</label>
                                <p style="color: #333333; margin: 0;">{{ $user->student_id }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <label style="color: #008080; font-weight: 600; display: block; margin-bottom: 5px;">الصلاحية</label>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge" style="background-color: #B8860B; color: white; padding: 8px 15px; border-radius: 20px;">
                                            <i class="fas fa-crown me-1"></i>مدير
                                        </span>
                                        @break
                                    @case('teacher')
                                        <span class="badge" style="background-color: #008080; color: white; padding: 8px 15px; border-radius: 20px;">
                                            <i class="fas fa-chalkboard-teacher me-1"></i>معلم
                                        </span>
                                        @break
                                    @case('student')
                                        <span class="badge" style="background-color: #004d40; color: white; padding: 8px 15px; border-radius: 20px;">
                                            <i class="fas fa-user-graduate me-1"></i>طالب
                                        </span>
                                        @break
                                @endswitch
                            </div>
                            <div class="info-item mb-3">
                                <label style="color: #008080; font-weight: 600; display: block; margin-bottom: 5px;">تاريخ التسجيل</label>
                                <p style="color: #333333; margin: 0;">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div class="info-item mb-3">
                                <label style="color: #008080; font-weight: 600; display: block; margin-bottom: 5px;">آخر تحديث</label>
                                <p style="color: #333333; margin: 0;">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div class="info-item mb-3">
                                <label style="color: #008080; font-weight: 600; display: block; margin-bottom: 5px;">تفعيل البريد</label>
                                @if($user->email_verified_at)
                                    <span class="badge" style="background-color: #004d40; color: white; padding: 8px 15px; border-radius: 20px;">
                                        <i class="fas fa-check me-1"></i>مفعّل
                                    </span>
                                    <small style="color: #666; display: block; margin-top: 5px;">{{ $user->email_verified_at->format('Y-m-d') }}</small>
                                @else
                                    <span class="badge" style="background-color: #ff6b35; color: white; padding: 8px 15px; border-radius: 20px;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>غير مفعّل
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإحصائيات -->
        <div class="col-lg-4">
            <div class="card shadow-sm" style="border: none; border-radius: 12px; background-color: white;">
                <div class="card-header" style="background-color: #008080; border-radius: 12px 12px 0 0; border: none;">
                    <h5 class="mb-0" style="color: #B8860B; font-weight: 600;">
                        <i class="fas fa-chart-pie me-2"></i>الإحصائيات
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($user->role === 'teacher' && isset($stats))
                        <div class="stats-box mb-3 p-3 rounded" style="background: linear-gradient(135deg, #F8F8F8 0%, #e8f4f8 100%); border-left: 4px solid #B8860B;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-book" style="color: #B8860B; font-size: 2rem;"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="mb-0" style="color: #333333; font-weight: 700;">{{ $stats['total_lessons'] }}</h3>
                                    <small style="color: #666;">إجمالي الدروس</small>
                                </div>
                            </div>
                        </div>
                        <div class="stats-box p-3 rounded" style="background: linear-gradient(135deg, #F8F8F8 0%, #e8f4f8 100%); border-left: 4px solid #008080;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-users" style="color: #008080; font-size: 2rem;"></i>
                                </div>
                                <div class="text-end">
                                    <h3 class="mb-0" style="color: #333333; font-weight: 700;">{{ $stats['total_students'] }}</h3>
                                    <small style="color: #666;">إجمالي الطلاب</small>
                                </div>
                            </div>
                        </div>
                    @elseif($user->role === 'student' && isset($stats))
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="mini-stat p-2 text-center rounded" style="background-color: #F8F8F8; border: 2px solid #004d40;">
                                    <i class="fas fa-book" style="color: #004d40;"></i>
                                    <h6 class="mb-0 mt-1" style="color: #333333;">{{ $stats['total_lessons'] }}</h6>
                                    <small style="color: #666;">الدروس</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mini-stat p-2 text-center rounded" style="background-color: #F8F8F8; border: 2px solid #008080;">
                                    <i class="fas fa-clipboard-check" style="color: #008080;"></i>
                                    <h6 class="mb-0 mt-1" style="color: #333333;">{{ $stats['total_attendances'] }}</h6>
                                    <small style="color: #666;">سجلات الحضور</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mini-stat p-2 text-center rounded" style="background-color: #F8F8F8; border: 2px solid #004d40;">
                                    <i class="fas fa-check" style="color: #004d40;"></i>
                                    <h6 class="mb-0 mt-1" style="color: #333333;">{{ $stats['present_count'] }}</h6>
                                    <small style="color: #666;">حاضر</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mini-stat p-2 text-center rounded" style="background-color: #F8F8F8; border: 2px solid #B8860B;">
                                    <i class="fas fa-clock" style="color: #B8860B;"></i>
                                    <h6 class="mb-0 mt-1" style="color: #333333;">{{ $stats['late_count'] }}</h6>
                                    <small style="color: #666;">متأخر</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mini-stat p-2 text-center rounded" style="background-color: #F8F8F8; border: 2px solid #ff6b35;">
                                    <i class="fas fa-times" style="color: #ff6b35;"></i>
                                    <h6 class="mb-0 mt-1" style="color: #333333;">{{ $stats['absent_count'] }}</h6>
                                    <small style="color: #666;">غائب</small>
                                </div>
                            </div>
                        </div>

                        @if($stats['total_attendances'] > 0)
                        <div class="mt-3 p-3 rounded" style="background: linear-gradient(135deg, #F8F8F8 0%, #fff8e7 100%); border: 1px solid #B8860B;">
                            <h6 style="color: #B8860B; margin-bottom: 10px; font-weight: 600;">نسبة الحضور</h6>
                            @php
                                $attendanceRate = round(($stats['present_count'] + $stats['late_count']) / $stats['total_attendances'] * 100, 1);
                            @endphp
                            <div class="progress mb-2" style="height: 10px; background-color: #F8F8F8; border-radius: 5px;">
                                <div class="progress-bar" 
                                     style="width: {{ $attendanceRate }}%; background: linear-gradient(90deg, #B8860B 0%, #008080 100%); border-radius: 5px;" 
                                     role="progressbar"></div>
                            </div>
                            <small style="color: #333333; font-weight: 600;">{{ $attendanceRate }}% معدل الحضور</small>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar" style="color: #ccc; font-size: 3rem; margin-bottom: 15px;"></i>
                            <p style="color: #666;">لا توجد إحصائيات متاحة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($user->role === 'teacher')
    <!-- دروس المعلم -->
    <div class="card shadow-sm mt-4" style="border: none; border-radius: 12px; background-color: white;">
        <div class="card-header" style="background-color: #008080; border-radius: 12px 12px 0 0; border: none;">
            <h5 class="mb-0" style="color: #B8860B; font-weight: 600;">
                <i class="fas fa-chalkboard-teacher me-2"></i>دروس المعلم
            </h5>
        </div>
        <div class="card-body p-4">
            @php
                $teacherLessons = $user->teachingLessons()->with('students')->get();
            @endphp
            
            @if($teacherLessons->count() > 0)
                <div class="row g-3">
                    @foreach($teacherLessons as $lesson)
                    <div class="col-md-6">
                        <div class="lesson-item p-3 rounded" style="background-color: #F8F8F8; border-left: 4px solid #B8860B; transition: all 0.3s ease;">
                            <h6 style="color: #B8860B; margin-bottom: 10px; font-weight: 600;">{{ $lesson->subject }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small style="color: #666;">
                                        <i class="fas fa-calendar me-1" style="color: #008080;"></i>
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
                                    <small style="color: #666;">
                                        <i class="fas fa-clock me-1" style="color: #008080;"></i>
                                        {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge" style="background-color: #008080; color: white; padding: 5px 12px; border-radius: 15px;">
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
                    <i class="fas fa-book-open" style="color: #ccc; font-size: 3rem; margin-bottom: 15px;"></i>
                    <p style="color: #666;">لا يوجد دروس مسجلة لهذا المعلم</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    @if($user->role === 'student')
    <!-- دروس الطالب -->
    <div class="card shadow-sm mt-4" style="border: none; border-radius: 12px; background-color: white;">
        <div class="card-header" style="background-color: #008080; border-radius: 12px 12px 0 0; border: none;">
            <h5 class="mb-0" style="color: #B8860B; font-weight: 600;">
                <i class="fas fa-user-graduate me-2"></i>دروس الطالب
            </h5>
        </div>
        <div class="card-body p-4">
            @php
                $studentLessons = $user->lessons()->with('teacher')->get();
            @endphp
            
            @if($studentLessons->count() > 0)
                <div class="row g-3">
                    @foreach($studentLessons as $lesson)
                    <div class="col-md-6">
                        <div class="lesson-item p-3 rounded" style="background-color: #F8F8F8; border-left: 4px solid #004d40; transition: all 0.3s ease;">
                            <h6 style="color: #004d40; margin-bottom: 10px; font-weight: 600;">{{ $lesson->subject }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small style="color: #666;">
                                        <i class="fas fa-user-tie me-1" style="color: #008080;"></i>
                                        {{ $lesson->teacher->name }}
                                    </small>
                                    <br>
                                    <small style="color: #666;">
                                        <i class="fas fa-calendar me-1" style="color: #008080;"></i>
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
                                <div>
                                    @php
                                        $userAttendance = $user->attendances()->where('lesson_id', $lesson->id)->latest()->first();
                                    @endphp
                                    @if($userAttendance)
                                        @switch($userAttendance->status)
                                            @case('present')
                                                <span class="badge" style="background-color: #004d40; color: white; padding: 5px 12px; border-radius: 15px;">
                                                    <i class="fas fa-check me-1"></i>حاضر
                                                </span>
                                                @break
                                            @case('late')
                                                <span class="badge" style="background-color: #B8860B; color: white; padding: 5px 12px; border-radius: 15px;">
                                                    <i class="fas fa-clock me-1"></i>متأخر
                                                </span>
                                                @break
                                            @case('absent')
                                                <span class="badge" style="background-color: #ff6b35; color: white; padding: 5px 12px; border-radius: 15px;">
                                                    <i class="fas fa-times me-1"></i>غائب
                                                </span>
                                                @break
                                        @endswitch
                                    @else
                                        <span class="badge" style="background-color: #ccc; color: white; padding: 5px 12px; border-radius: 15px;">
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
                    <i class="fas fa-book-open" style="color: #ccc; font-size: 3rem; margin-bottom: 15px;"></i>
                    <p style="color: #666;">لا يوجد دروس مسجلة لهذا الطالب</p>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<style>
.stats-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.mini-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.lesson-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    background-color: #ffffff !important;
}

.info-item {
    transition: all 0.3s ease;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection
