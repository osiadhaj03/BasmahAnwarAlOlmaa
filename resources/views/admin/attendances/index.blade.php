@extends('layouts.admin')

@section('title', 'إدارة الحضور')

@push('styles')
<style>
    body {
        background-color: #F8F8F8;
        font-family: 'Tajawal', 'Cairo', sans-serif;
    }
    
    .islamic-pattern {
        background-color: #F8F8F8;
    }
    
    .status-present {
        background-color: #008080;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    .status-absent {
        background-color: #B22222;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    .status-late {
        background-color: #DAA520;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    .status-excused {
        background-color: #4682B4;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    .anwar-alert-success {
        background-color: #d1fae5;
        border-left: 4px solid #10b981;
        color: #065f46;
        border-radius: 8px;
    }
    
    .anwar-alert-danger {
        background-color: #fee2e2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
        border-radius: 8px;
    }
    
    .anwar-alert-info {
        background-color: #E0F2F1;
        border-left: 4px solid #008080;
        color: #008080;
        border-radius: 8px;
    }
    
    .btn-anwar-primary {
        background-color: #DAA520;
        border-color: #DAA520;
        color: white;
    }
    
    .btn-anwar-primary:hover {
        background-color: #c6951c;
        border-color: #c6951c;
        color: white;
    }
    
    .card-anwar {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .table th {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e9ecef 100%) !important;
        border: none;
        font-weight: 600;
        font-size: 0.85rem;
        color: #495057 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        border-bottom: 2px solid #dee2e6 !important;
    }
    
    /* إزالة أي لون ذهبي من header الجدول */
    .table thead th {
        background-color: #ffffff !important;
        background: #ffffff !important;
        color: #495057 !important;
    }
    
    /* إزالة أي لون ذهبي من Bootstrap */
    table.table thead th {
        background: #ffffff !important;
        background-color: #ffffff !important;
    }
    
    .table tbody tr {
        border-bottom: 1px solid #e9ecef;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .stats-card {
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stats-card i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    
    .btn-action {
        padding: 0.375rem 0.75rem;
        border: none;
        border-radius: 6px;
        margin: 0 2px;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .filter-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .active-filters-badge {
        background-color: #e3f2fd;
        color: #1976d2;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    /* إزالة الألوان الذهبية بقوة */
    .text-warning, 
    .fa-clipboard-check.text-warning,
    .fa-table.text-warning,
    i.text-warning {
        color: #6c757d !important;
    }
    
    /* التأكد من أن جميع الأيقونات تستخدم اللون الرمادي */
    h1 i, h2 i, h3 i {
        color: #6c757d !important;
    }
    
    /* إزالة اللون الذهبي من جدول الحضور بقوة */
    .table thead tr th {
        background: #ffffff !important;
        background-color: #ffffff !important;
        color: #495057 !important;
    }
    
    /* إزالة أي ألوان Bootstrap الافتراضية */
    .table-warning,
    .table-warning > th,
    .table-warning > td {
        background-color: #ffffff !important;
    }
    
    /* تطبيق تدرج جميل من درجات الأبيض على header الجدول */
    thead {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e9ecef 100%) !important;
    }
    
    thead th {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e9ecef 100%) !important;
        background-color: transparent !important;
        color: #495057 !important;
        border-bottom: 2px solid #dee2e6 !important;
        position: relative;
    }
    
    /* إضافة تأثير بصري خفيف */
    thead th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, #ced4da 50%, transparent 100%);
    }
</style>
@endpush

@section('content')
<div class="container-fluid" style="background-color: #F8F8F8; min-height: 100vh; padding: 20px;">
    <!-- رسائل النجاح والخطأ -->
    @if(session('success'))
        <div class="alert anwar-alert-success alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert anwar-alert-danger alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Card -->
    <div class="card-anwar islamic-pattern mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 fw-bold text-dark mb-2">
                        <i class="fas fa-clipboard-check text-muted me-2" style="color: #6c757d !important;"></i>
                        إدارة الحضور
                    </h1>
                    <p class="text-muted mb-0">
                        @if(auth()->user()->role === 'admin')
                            عرض وتقارير الحضور لجميع المعلمين
                        @else
                            إدارة حضور دروسك
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="anwar-alert-info d-inline-block px-3 py-2 rounded mb-2 me-2" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        تسجيل الحضور يتم عبر الطلاب باستخدام QR Code فقط
                    </div>
                    <br>
                    <a href="{{ route('admin.attendances.reports') }}" class="btn btn-anwar-primary">
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
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); color: #495057;">
                <i class="fas fa-clipboard-list"></i>
                <h3>{{ $stats['total'] }}</h3>
                <small>إجمالي السجلات</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 100%); color: #6c757d;">
                <i class="fas fa-calendar-day"></i>
                <h3>{{ $stats['today'] }}</h3>
                <small>سجلات اليوم</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f7f8f9 0%, #dee2e6 100%); color: #343a40;">
                <i class="fas fa-user-check"></i>
                <h3>{{ $stats['present_today'] }}</h3>
                <small>حاضر اليوم</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #fefefe 0%, #f5f5f5 100%); color: #212529;">
                <i class="fas fa-user-times"></i>
                <h3>{{ $stats['absent_today'] }}</h3>
                <small>غائب اليوم</small>
            </div>
        </div>
    </div>
    @endif

    <!-- فلاتر البحث -->
    <div class="filter-section mb-4">
        <div class="card-body p-4">
            <div class="border-bottom pb-3 mb-4">
                <h2 class="h5 fw-semibold text-dark mb-0">
                    <i class="fas fa-filter text-muted me-2"></i>
                    فلاتر البحث والتصفية
                </h2>
            </div>
            <form method="GET" action="{{ route('admin.attendances.index') }}" id="filterForm">
                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label for="search" class="form-label fw-medium text-dark">البحث</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="اسم الطالب أو المادة...">
                    </div>
                    <div class="col-md-2">
                        <label for="lesson_id" class="form-label fw-medium text-dark">الدرس</label>
                        <select class="form-select" id="lesson_id" name="lesson_id">
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
                    <div class="col-md-2">
                        <label for="status" class="form-label fw-medium text-dark">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>حاضر</option>
                            <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>غائب</option>
                            <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>متأخر</option>
                            <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>بعذر</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label fw-medium text-dark">من تاريخ</label>
                        <input type="date" 
                               class="form-select" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label fw-medium text-dark">إلى تاريخ</label>
                        <input type="date" 
                               class="form-select" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="submit" class="btn btn-anwar-primary d-flex align-items-center">
                        <i class="fas fa-search me-2"></i>
                        بحث وتصفية
                    </button>
                    <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="fas fa-undo me-2"></i>
                        إعادة تعيين
                    </a>
                    @if(request()->hasAny(['search', 'lesson_id', 'status', 'date_from', 'date_to']))
                    <span class="active-filters-badge d-flex align-items-center">
                        <i class="fas fa-filter me-1"></i>
                        فلاتر نشطة
                    </span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الحضور -->
    <div class="card-anwar overflow-hidden">
        <div class="card-body p-4 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="h5 fw-semibold text-dark mb-0">
                        <i class="fas fa-table text-muted me-2" style="color: #6c757d !important;"></i>
                        سجلات الحضور
                        @if($attendances->total() > 0)
                            <span class="badge bg-secondary ms-2">{{ $attendances->total() }} سجل</span>
                        @endif
                    </h2>
                </div>
                <div class="col-md-4 text-end">
                    <p class="text-muted mb-0 small">
                        عرض {{ $attendances->firstItem() ?? 0 }} إلى {{ $attendances->lastItem() ?? 0 }} 
                        من أصل {{ $attendances->total() }} سجل
                    </p>
                </div>
            </div>
        </div>
        
        @if($attendances->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3" style="width: 50px;">#</th>
                            <th class="px-4 py-3">الطالب</th>
                            <th class="px-4 py-3">المادة/الدرس</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-4 py-3">المعلم</th>
                            @endif
                            <th class="px-4 py-3" style="width: 120px;">التاريخ</th>
                            <th class="px-4 py-3" style="width: 100px;">الحالة</th>
                            <th class="px-4 py-3">الملاحظات</th>
                            @if(auth()->user()->role === 'teacher')
                                <th class="px-4 py-3" style="width: 120px;">الإجراءات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $index => $attendance)
                            <tr>
                                <td class="px-4 py-3 text-muted small">
                                    {{ ($attendances->currentPage() - 1) * $attendances->perPage() + $index + 1 }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-medium text-dark">{{ $attendance->student->name }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-medium text-primary">{{ $attendance->lesson->subject }}</div>
                                    <div class="small text-muted">{{ $attendance->lesson->name }}</div>
                                </td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="px-4 py-3">
                                        <div class="fw-medium text-dark">{{ $attendance->lesson->teacher->name }}</div>
                                    </td>
                                @endif
                                <td class="px-4 py-3 text-center">
                                    <div class="fw-medium">{{ $attendance->date->format('Y/m/d') }}</div>
                                    <div class="small text-muted">{{ $attendance->date->format('l') }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @switch($attendance->status)
                                        @case('present')
                                            <span class="status-present">
                                                حاضر
                                            </span>
                                            @break
                                        @case('absent')
                                            <span class="status-absent">
                                                غائب
                                            </span>
                                            @break
                                        @case('late')
                                            <span class="status-late">
                                                متأخر
                                            </span>
                                            @break
                                        @case('excused')
                                            <span class="status-excused">
                                                بعذر
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">
                                    @if($attendance->notes)
                                        <span class="text-muted small" title="{{ $attendance->notes }}">
                                            {{ Str::limit($attendance->notes, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'teacher')
                                    <td class="px-4 py-3">
                                        <div class="d-flex gap-1">
                                            <button class="btn-action text-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-action text-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-action text-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-body border-top">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-muted small mb-0">
                            عرض {{ $attendances->firstItem() }} إلى {{ $attendances->lastItem() }} 
                            من أصل {{ $attendances->total() }} سجل
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        {{ $attendances->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="fas fa-clipboard-check text-muted mb-4" style="font-size: 4rem;"></i>
                <h3 class="h5 fw-medium text-muted">لا توجد سجلات حضور</h3>
                @if(request()->hasAny(['search', 'lesson_id', 'status', 'date_from', 'date_to']))
                    <p class="text-muted mt-2">لا توجد نتائج مطابقة للفلاتر المحددة</p>
                    <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-undo me-2"></i>
                        إزالة الفلاتر
                    </a>
                @else
                    <p class="text-muted mt-2">تسجيل الحضور متاح للطلاب فقط عبر QR Code</p>
                    <div class="anwar-alert-info mx-auto d-inline-block px-4 py-3 rounded mt-4">
                        <i class="fas fa-qrcode me-2"></i>
                        يمكن للطلاب تسجيل الحضور باستخدام رمز QR الخاص بكل درس
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تفعيل tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // البحث السريع مع Enter
    document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('filterForm').submit();
        }
    });

    // تأثيرات بصرية للصفوف
    document.querySelectorAll('tbody tr').forEach(function(row) {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'all 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = 'scale(1)';
        });
    });

    // رسائل النجاح/الخطأ التلقائية
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('alert-dismissible')) {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }
        });
    }, 5000);

    // تأكيد الحذف
    function confirmDelete() {
        return confirm('هل أنت متأكد من حذف هذا السجل؟');
    }
</script>
@endpush
