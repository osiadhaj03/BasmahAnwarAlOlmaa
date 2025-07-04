@extends('layouts.admin')

@section('title', 'تقارير الحضور')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        تقارير الحضور
                    </h4>
                    <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        العودة للحضور
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- فلاتر التقرير -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="lesson_id" class="form-label">الدرس</label>
                                <select class="form-control" id="lesson_id" name="lesson_id">
                                    <option value="">جميع الدروس</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" 
                                                {{ request('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                            {{ $lesson->subject }} - {{ $lesson->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="student_id" class="form-label">الطالب</label>
                                <select class="form-control" id="student_id" name="student_id">
                                    <option value="">جميع الطلاب</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" 
                                                {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="start_date" class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date', date('Y-m-01')) }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="end_date" class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>
                                    تصفية
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- إحصائيات سريعة -->
                    @if(isset($statistics))
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h4>{{ $statistics['present'] }}</h4>
                                    <small>حاضر</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <h4>{{ $statistics['late'] }}</h4>
                                    <small>متأخر</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                                    <h4>{{ $statistics['absent'] }}</h4>
                                    <small>غائب</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-check fa-2x mb-2"></i>
                                    <h4>{{ $statistics['excused'] }}</h4>
                                    <small>بعذر</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- نسبة الحضور -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">نسبة الحضور العامة</h5>
                                    @php
                                        $total = $statistics['present'] + $statistics['late'] + $statistics['absent'] + $statistics['excused'];
                                        $attendanceRate = $total > 0 ? round((($statistics['present'] + $statistics['late']) / $total) * 100, 1) : 0;
                                    @endphp
                                    <div class="progress mb-2" style="height: 25px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $attendanceRate }}%" 
                                             aria-valuenow="{{ $attendanceRate }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $attendanceRate }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">إجمالي السجلات: {{ $total }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- جدول التفاصيل -->
                    @if(isset($attendances) && $attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الطالب</th>
                                    <th>المادة</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th>المعلم</th>
                                    @endif
                                    <th>الحالة</th>
                                    <th>الملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('Y/m/d') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($attendance->student->name, 0, 2) }}
                                            </div>
                                            {{ $attendance->student->name }}
                                        </div>
                                    </td>
                                    <td>{{ $attendance->lesson->subject }}</td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>{{ $attendance->lesson->teacher->name }}</td>
                                    @endif
                                    <td>
                                        @switch($attendance->status)
                                            @case('present')
                                                <span class="badge bg-success">حاضر</span>
                                                @break
                                            @case('absent')
                                                <span class="badge bg-danger">غائب</span>
                                                @break
                                            @case('late')
                                                <span class="badge bg-warning">متأخر</span>
                                                @break
                                            @case('excused')
                                                <span class="badge bg-info">بعذر</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $attendance->notes ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendances->appends(request()->query())->links() }}
                    </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد بيانات للعرض</h5>
                            <p class="text-muted">جرب تعديل الفلاتر أو إضافة المزيد من سجلات الحضور</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}
</style>
@endsection
