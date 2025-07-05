@extends('layouts.admin')

@section('title', 'إدارة الحضور')

@section('content')
<div class="container-fluid">
    <!-- رسائل النجاح والخطأ -->    @if(session('success'))
        <div class="alert anwar-alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert anwar-alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card anwar-card islamic-pattern-subtle mb-4">
                <div class="card-header anwar-card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0 anwar-text-gold">
                                <i class="fas fa-clipboard-check me-2"></i>
                                إدارة الحضور
                            </h4>
                            <small class="anwar-text-muted">
                                @if(auth()->user()->role === 'admin')
                                    عرض وتقارير الحضور لجميع المعلمين
                                @else
                                    إدارة حضور دروسك
                                @endif
                            </small>
                        </div>                        <div class="col-auto">
                            <div class="alert anwar-alert-info mb-0 me-2" style="font-size: 0.85rem;">
                                <i class="fas fa-info-circle me-1"></i>
                                تسجيل الحضور يتم عبر الطلاب باستخدام QR Code فقط
                            </div>
                            <a href="{{ route('admin.attendances.reports') }}" class="btn anwar-btn-info">
                                <i class="fas fa-chart-line me-2"></i>
                                التقارير المتقدمة
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            @if(isset($stats))
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            <small>إجمالي السجلات</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-day fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['today'] }}</h3>
                            <small>سجلات اليوم</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-check fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['present_today'] }}</h3>
                            <small>حاضر اليوم</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-user-times fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $stats['absent_today'] }}</h3>
                            <small>غائب اليوم</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- فلاتر البحث -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        فلاتر البحث والتصفية
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.attendances.index') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">البحث</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="اسم الطالب أو المادة...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="lesson_id" class="form-label">الدرس</label>
                                <select class="form-control" id="lesson_id" name="lesson_id">
                                    <option value="">جميع الدروس</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" 
                                                {{ request('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                            {{ $lesson->subject }} - {{ $lesson->name }}
                                            @if(auth()->user()->role === 'admin')
                                                ({{ $lesson->teacher->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">جميع الحالات</option>
                                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>حاضر</option>
                                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>غائب</option>
                                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>متأخر</option>
                                    <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>بعذر</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date_from" class="form-label">من تاريخ</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date_from" 
                                       name="date_from" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="date_to" class="form-label">إلى تاريخ</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date_to" 
                                       name="date_to" 
                                       value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-2"></i>
                                    بحث وتصفية
                                </button>
                                <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-2"></i>
                                    إعادة تعيين
                                </a>
                                @if(request()->hasAny(['search', 'lesson_id', 'status', 'date_from', 'date_to']))
                                <span class="badge bg-info ms-2">
                                    <i class="fas fa-filter me-1"></i>
                                    فلاتر نشطة
                                </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- جدول الحضور -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>
                                سجلات الحضور
                                @if($attendances->total() > 0)
                                    <span class="badge bg-secondary">{{ $attendances->total() }} سجل</span>
                                @endif
                            </h5>
                        </div>
                        <div class="col-auto">
                            <small class="text-muted">
                                عرض {{ $attendances->firstItem() ?? 0 }} إلى {{ $attendances->lastItem() ?? 0 }} 
                                من أصل {{ $attendances->total() }} سجل
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>الطالب</th>
                                        <th>المادة/الدرس</th>
                                        @if(auth()->user()->role === 'admin')
                                            <th>المعلم</th>
                                        @endif
                                        <th style="width: 120px;">التاريخ</th>
                                        <th style="width: 100px;">الحالة</th>
                                        <th>الملاحظات</th>
                                        @if(auth()->user()->role === 'teacher')
                                            <th style="width: 120px;">الإجراءات</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $index => $attendance)
                                        <tr>
                                            <td class="text-muted">
                                                {{ ($attendances->currentPage() - 1) * $attendances->perPage() + $index + 1 }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : 'warning') }} text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        {{ substr($attendance->student->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $attendance->student->name }}</div>
                                                        <small class="text-muted">{{ $attendance->student->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold text-primary">{{ $attendance->lesson->subject }}</div>
                                                    <small class="text-muted">{{ $attendance->lesson->name }}</small>
                                                </div>
                                            </td>
                                            @if(auth()->user()->role === 'admin')
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-chalkboard-teacher me-2 text-info"></i>
                                                        {{ $attendance->lesson->teacher->name }}
                                                    </div>
                                                </td>
                                            @endif
                                            <td>
                                                <div class="text-center">
                                                    <div class="fw-bold">{{ $attendance->date->format('Y/m/d') }}</div>
                                                    <small class="text-muted">{{ $attendance->date->format('l') }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @switch($attendance->status)
                                                    @case('present')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>
                                                            حاضر
                                                        </span>
                                                        @break
                                                    @case('absent')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times me-1"></i>
                                                            غائب
                                                        </span>
                                                        @break
                                                    @case('late')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            متأخر
                                                        </span>
                                                        @break
                                                    @case('excused')
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-user-check me-1"></i>
                                                            بعذر
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($attendance->notes)
                                                    <span class="text-muted" title="{{ $attendance->notes }}">
                                                        {{ Str::limit($attendance->notes, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            @if(auth()->user()->role === 'teacher')
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.attendances.show', $attendance) }}" 
                                                           class="btn btn-outline-info" title="عرض">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.attendances.edit', $attendance) }}" 
                                                           class="btn btn-outline-warning" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" 
                                                              action="{{ route('admin.attendances.destroy', $attendance) }}" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted">
                                        عرض {{ $attendances->firstItem() }} إلى {{ $attendances->lastItem() }} 
                                        من أصل {{ $attendances->total() }} سجل
                                    </small>
                                </div>
                                <div class="col-auto">
                                    {{ $attendances->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد سجلات حضور</h5>
                            @if(request()->hasAny(['search', 'lesson_id', 'status', 'date_from', 'date_to']))
                                <p class="text-muted">لا توجد نتائج مطابقة للفلاتر المحددة</p>
                                <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-undo me-2"></i>
                                    إزالة الفلاتر
                                </a>                            @else
                                <p class="text-muted">تسجيل الحضور متاح للطلاب فقط عبر QR Code</p>
                                <div class="alert alert-info">
                                    <i class="fas fa-qrcode me-2"></i>
                                    يمكن للطلاب تسجيل الحضور باستخدام رمز QR الخاص بكل درس
                                </div>
                            @endif
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
    width: 35px;
    height: 35px;
    font-size: 12px;
    font-weight: bold;
}

.table th {
    font-weight: 600;
    border-top: none;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin: 0 1px;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75em;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>
@endsection
