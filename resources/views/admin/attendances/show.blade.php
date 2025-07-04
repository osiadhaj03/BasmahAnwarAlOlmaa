@extends('layouts.admin')

@section('title', 'عرض سجل الحضور')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">تفاصيل سجل الحضور</h1>
                <div>
                    <a href="{{ route('admin.attendances.edit', $attendance) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>تعديل
                    </a>
                    <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Attendance Details -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clipboard-check me-2"></i>تفاصيل سجل الحضور
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 200px;">الطالب:</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-graduate text-primary me-2"></i>
                                            <strong>{{ $attendance->student->name }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $attendance->student->email }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>الدرس:</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-book text-info me-2"></i>
                                            <strong>{{ $attendance->lesson->name }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $attendance->lesson->subject ?? 'غير محدد' }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>المعلم:</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                                            <span class="badge bg-primary">{{ $attendance->lesson->teacher->name }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>التاريخ:</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar text-warning me-2"></i>
                                            <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('Y/m/d') }}</strong>
                                            <small class="text-muted ms-2">
                                                ({{ \Carbon\Carbon::parse($attendance->date)->diffForHumans() }})
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>حالة الحضور:</th>
                                    <td>
                                        @if($attendance->status === 'present')
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check me-1"></i>حاضر
                                            </span>
                                        @elseif($attendance->status === 'absent')
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-times me-1"></i>غائب
                                            </span>
                                        @else
                                            <span class="badge bg-warning fs-6">
                                                <i class="fas fa-clock me-1"></i>متأخر
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ التسجيل:</th>
                                    <td>{{ $attendance->created_at->format('Y/m/d - H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $attendance->updated_at->format('Y/m/d - H:i') }}</td>
                                </tr>
                                @if($attendance->notes)
                                <tr>
                                    <th>ملاحظات:</th>
                                    <td>
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-sticky-note me-2"></i>
                                            {{ $attendance->notes }}
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Actions & Info -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-lightning-bolt me-2"></i>إجراءات سريعة
                            </h5>
                        </div>                        <div class="card-body">
                            <a href="{{ route('admin.attendances.edit', $attendance) }}" 
                               class="btn btn-warning btn-block mb-2">
                                <i class="fas fa-edit me-2"></i>تعديل السجل
                            </a>
                            <a href="{{ route('admin.lessons.show', $attendance->lesson) }}" 
                               class="btn btn-info btn-block mb-2">
                                <i class="fas fa-book me-2"></i>عرض الدرس
                            </a>
                            <div class="alert alert-info">
                                <i class="fas fa-qrcode me-2"></i>تسجيل الحضور متاح للطلاب فقط عبر QR Code
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>معلومات إضافية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if($attendance->status === 'present')
                                    <div class="text-success">
                                        <i class="fas fa-check-circle fa-3x mb-2"></i>
                                        <h6>حضور مؤكد</h6>
                                        <small class="text-muted">الطالب حضر الدرس في الموعد المحدد</small>
                                    </div>
                                @elseif($attendance->status === 'absent')
                                    <div class="text-danger">
                                        <i class="fas fa-times-circle fa-3x mb-2"></i>
                                        <h6>غياب</h6>
                                        <small class="text-muted">الطالب لم يحضر الدرس</small>
                                    </div>
                                @else
                                    <div class="text-warning">
                                        <i class="fas fa-exclamation-circle fa-3x mb-2"></i>
                                        <h6>تأخير</h6>
                                        <small class="text-muted">الطالب حضر متأخراً</small>
                                    </div>
                                @endif
                            </div>
                            
                            <hr>
                            
                            <div class="small text-muted">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>معرف السجل:</span>
                                    <strong>#{{ $attendance->id }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>يوم الأسبوع:</span>
                                    <strong>{{ \Carbon\Carbon::parse($attendance->date)->locale('ar')->dayName }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student's Attendance History -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>سجل حضور الطالب في هذا الدرس
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $studentAttendances = \App\Models\Attendance::where('student_id', $attendance->student_id)
                            ->where('lesson_id', $attendance->lesson_id)
                            ->orderBy('date', 'desc')
                            ->take(10)
                            ->get();
                    @endphp
                    
                    @if($studentAttendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>ملاحظات</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentAttendances as $record)
                                        <tr class="{{ $record->id === $attendance->id ? 'table-primary' : '' }}">
                                            <td>
                                                {{ \Carbon\Carbon::parse($record->date)->format('Y/m/d') }}
                                                @if($record->id === $attendance->id)
                                                    <span class="badge bg-primary ms-1">الحالي</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($record->status === 'present')
                                                    <span class="badge bg-success">حاضر</span>
                                                @elseif($record->status === 'absent')
                                                    <span class="badge bg-danger">غائب</span>
                                                @else
                                                    <span class="badge bg-warning">متأخر</span>
                                                @endif
                                            </td>
                                            <td>{{ $record->notes ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('admin.attendances.show', $record) }}" 
                                                   class="btn btn-sm btn-outline-primary">عرض</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @php
                            $totalAttendances = \App\Models\Attendance::where('student_id', $attendance->student_id)
                                ->where('lesson_id', $attendance->lesson_id)
                                ->count();
                            $presentCount = \App\Models\Attendance::where('student_id', $attendance->student_id)
                                ->where('lesson_id', $attendance->lesson_id)
                                ->where('status', 'present')
                                ->count();
                            $attendanceRate = $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 1) : 0;
                        @endphp
                        
                        <div class="mt-3 text-center">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="text-primary">{{ $totalAttendances }}</h5>
                                    <small class="text-muted">إجمالي السجلات</small>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="text-success">{{ $presentCount }}</h5>
                                    <small class="text-muted">مرات الحضور</small>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="text-info">{{ $attendanceRate }}%</h5>
                                    <small class="text-muted">معدل الحضور</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">هذا هو أول سجل حضور للطالب في هذا الدرس</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
