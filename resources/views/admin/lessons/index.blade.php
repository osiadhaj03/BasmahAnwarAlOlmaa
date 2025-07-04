@extends('layouts.admin')

@section('title', 'إدارة الدروس')

@push('styles')
<style>
    .filter-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .filter-section {
        border-right: 2px solid #dee2e6;
        padding-right: 20px;
        margin-right: 20px;
    }
    .filter-section:last-child {
        border-right: none;
        padding-right: 0;
        margin-right: 0;
    }
    .search-box {
        position: relative;
    }
    .search-box .fas {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .search-box input {
        padding-right: 40px;
    }
    .sort-controls {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 10px;
    }
    .lesson-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .lesson-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .day-badge {
        font-size: 0.85em;
        padding: 6px 12px;
        border-radius: 20px;
    }
    .time-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        border-radius: 15px;
        padding: 4px 8px;
        font-size: 0.8em;
    }
    .students-count {
        background: linear-gradient(45deg, #007bff, #6610f2);
        color: white;
        border-radius: 15px;
        padding: 4px 8px;
        font-size: 0.8em;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-book-open me-2 text-primary"></i>
        إدارة الدروس
    </h1>
    <a href="{{ route('admin.lessons.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        إضافة درس جديد
    </a>
</div>

<!-- نظام البحث والفلترة المتقدم -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.lessons.index') }}" id="filterForm">
            <div class="row g-3">
                <!-- البحث الرئيسي -->
                <div class="col-md-4">
                    <div class="filter-section">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-search me-2"></i>البحث
                        </h6>
                        <div class="search-box">
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="ابحث في المادة، الوصف، أو اسم المعلم..."
                                   style="padding-right: 40px;">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                <!-- فلاتر متقدمة -->
                <div class="col-md-8">
                    <div class="row g-3">
                        <!-- فلتر اليوم -->
                        <div class="col-md-3">
                            <h6 class="text-success mb-2">
                                <i class="fas fa-calendar-day me-1"></i>اليوم
                            </h6>
                            <select class="form-select" name="day_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل الأيام</option>
                                @foreach($days as $value => $label)
                                    <option value="{{ $value }}" 
                                            @if(request('day_filter') == $value) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- فلتر المعلم (للمدير فقط) -->
                        @if(auth()->user()->role === 'admin' && $teachers->count() > 0)
                        <div class="col-md-3">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-user-tie me-1"></i>المعلم
                            </h6>
                            <select class="form-select" name="teacher_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل المعلمين</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" 
                                            @if(request('teacher_filter') == $teacher->id) selected @endif>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- فلتر الوقت -->
                        <div class="col-md-3">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-clock me-1"></i>الوقت
                            </h6>
                            <select class="form-select" name="time_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل الأوقات</option>
                                <option value="morning" @if(request('time_filter') == 'morning') selected @endif>
                                    صباحي (قبل 12 ظهراً)
                                </option>
                                <option value="afternoon" @if(request('time_filter') == 'afternoon') selected @endif>
                                    بعد الظهر (12-6 مساءً)
                                </option>
                                <option value="evening" @if(request('time_filter') == 'evening') selected @endif>
                                    مسائي (بعد 6 مساءً)
                                </option>
                            </select>
                        </div>                        <!-- فلتر عدد الطلاب -->
                        <div class="col-md-2">
                            <h6 class="text-danger mb-2">
                                <i class="fas fa-users me-1"></i>الطلاب
                            </h6>
                            <select class="form-select" name="students_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل الأعداد</option>
                                <option value="none" @if(request('students_filter') == 'none') selected @endif>
                                    بدون طلاب
                                </option>
                                <option value="few" @if(request('students_filter') == 'few') selected @endif>
                                    قليل (1-10)
                                </option>
                                <option value="medium" @if(request('students_filter') == 'medium') selected @endif>
                                    متوسط (11-25)
                                </option>
                                <option value="many" @if(request('students_filter') == 'many') selected @endif>
                                    كثير (+25)
                                </option>
                            </select>
                        </div>

                        <!-- فلتر حالة الدرس -->
                        <div class="col-md-2">
                            <h6 class="text-purple mb-2">
                                <i class="fas fa-flag me-1"></i>الحالة
                            </h6>
                            <select class="form-select" name="status_filter" onchange="document.getElementById('filterForm').submit()">
                                <option value="">كل الحالات</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" 
                                            @if(request('status_filter') == $value) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>بحث
                    </button>
                    <a href="{{ route('admin.lessons.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i>مسح الفلاتر
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="sort-controls d-flex align-items-center justify-content-end">
                        <label class="me-2 text-muted small">ترتيب حسب:</label>
                        <select class="form-select form-select-sm me-2" name="sort_by" style="width: auto;" onchange="document.getElementById('filterForm').submit()">
                            <option value="created_at" @if(request('sort_by') == 'created_at') selected @endif>تاريخ الإنشاء</option>
                            <option value="subject" @if(request('sort_by') == 'subject') selected @endif>المادة</option>
                            <option value="teacher" @if(request('sort_by') == 'teacher') selected @endif>المعلم</option>
                            <option value="day" @if(request('sort_by') == 'day') selected @endif>اليوم</option>
                            <option value="time" @if(request('sort_by') == 'time') selected @endif>الوقت</option>
                            <option value="students" @if(request('sort_by') == 'students') selected @endif>عدد الطلاب</option>
                        </select>
                        <select class="form-select form-select-sm" name="sort_direction" style="width: auto;" onchange="document.getElementById('filterForm').submit()">
                            <option value="asc" @if(request('sort_direction') == 'asc') selected @endif>تصاعدي</option>
                            <option value="desc" @if(request('sort_direction') == 'desc') selected @endif>تنازلي</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- إحصائيات سريعة -->
@if(request()->hasAny(['search', 'day_filter', 'teacher_filter', 'time_filter', 'students_filter', 'status_filter']))
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    تم العثور على <strong>{{ $lessons->total() }}</strong> درس مطابق للبحث والفلاتر المحددة
    @if(request('search'))
        <span class="badge bg-primary ms-2">البحث: "{{ request('search') }}"</span>
    @endif
    @if(request('day_filter'))
        <span class="badge bg-success ms-2">اليوم: {{ $days[request('day_filter')] }}</span>
    @endif
    @if(request('status_filter'))
        <span class="badge bg-purple ms-2">الحالة: {{ $statuses[request('status_filter')] }}</span>
    @endif
</div>
@endif

<!-- قائمة الدروس -->
<div class="card">
    <div class="card-body">
        @if($lessons->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">
                                <i class="fas fa-book me-1 text-primary"></i>المادة
                            </th>
                            <th class="border-0">
                                <i class="fas fa-user-tie me-1 text-info"></i>المعلم
                            </th>
                            <th class="border-0">
                                <i class="fas fa-calendar-day me-1 text-success"></i>اليوم
                            </th>
                            <th class="border-0">
                                <i class="fas fa-clock me-1 text-warning"></i>الوقت
                            </th>                            <th class="border-0">
                                <i class="fas fa-users me-1 text-danger"></i>الطلاب
                            </th>
                            <th class="border-0">
                                <i class="fas fa-flag me-1 text-purple"></i>الحالة
                            </th>
                            <th class="border-0">
                                <i class="fas fa-cogs me-1 text-secondary"></i>الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lessons as $lesson)
                            <tr class="lesson-row">
                                <td>
                                    <div>
                                        <strong class="text-primary">{{ $lesson->subject }}</strong>
                                        @if($lesson->description)
                                            <br><small class="text-muted">{{ Str::limit($lesson->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $lesson->teacher->name }}</strong>
                                            <br><small class="text-muted">{{ $lesson->teacher->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="day-badge 
                                        @switch($lesson->day_of_week)
                                            @case('sunday') bg-primary @break
                                            @case('monday') bg-success @break
                                            @case('tuesday') bg-info @break
                                            @case('wednesday') bg-warning @break
                                            @case('thursday') bg-danger @break
                                            @case('friday') bg-dark @break
                                            @case('saturday') bg-secondary @break
                                        @endswitch text-white">
                                        @switch($lesson->day_of_week)
                                            @case('sunday') الأحد @break
                                            @case('monday') الإثنين @break
                                            @case('tuesday') الثلاثاء @break
                                            @case('wednesday') الأربعاء @break
                                            @case('thursday') الخميس @break
                                            @case('friday') الجمعة @break
                                            @case('saturday') السبت @break
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div class="time-badge">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
                                    </div>
                                    @php
                                        $duration = \Carbon\Carbon::parse($lesson->start_time)->diffInMinutes(\Carbon\Carbon::parse($lesson->end_time));
                                    @endphp
                                    <br><small class="text-muted">{{ $duration }} دقيقة</small>
                                </td>
                                <td>
                                    <div class="students-count">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $lesson->students_count }} طالب
                                    </div>
                                    @if($lesson->students_count > 0)
                                        <br><small class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>نشط
                                        </small>
                                    @else
                                        <br><small class="text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>بدون طلاب
                                        </small>                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'primary',
                                            'active' => 'success',
                                            'completed' => 'info',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusIcons = [
                                            'scheduled' => 'calendar-alt',
                                            'active' => 'play-circle',
                                            'completed' => 'check-circle',
                                            'cancelled' => 'times-circle'
                                        ];
                                        $statusColor = $statusColors[$lesson->status] ?? 'secondary';
                                        $statusIcon = $statusIcons[$lesson->status] ?? 'question-circle';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }} d-flex align-items-center">
                                        <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                        {{ $statuses[$lesson->status] ?? $lesson->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">                                        <!-- QR Code -->
                                        <a href="{{ route('admin.lessons.qr.display', $lesson) }}" 
                                           class="btn btn-outline-success btn-sm" 
                                           title="QR Code للحضور"
                                           data-bs-toggle="tooltip"
                                           target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                        
                                        <!-- عرض -->
                                        <a href="{{ route('admin.lessons.show', $lesson) }}" 
                                           class="btn btn-outline-info btn-sm" 
                                           title="عرض التفاصيل"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- تعديل -->
                                        @if(auth()->user()->role === 'admin' || $lesson->teacher_id === auth()->id())
                                        <a href="{{ route('admin.lessons.edit', $lesson) }}" 
                                           class="btn btn-outline-warning btn-sm" 
                                           title="تعديل"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        
                                        <!-- حذف -->
                                        @if(auth()->user()->role === 'admin' || $lesson->teacher_id === auth()->id())
                                        <form method="POST" 
                                              action="{{ route('admin.lessons.destroy', $lesson) }}" 
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الدرس؟\\n\\nسيتم حذف جميع سجلات الحضور المرتبطة به.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm" 
                                                    title="حذف"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- صفحات النتائج -->
            @if($lessons->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    عرض {{ $lessons->firstItem() }} إلى {{ $lessons->lastItem() }} 
                    من أصل {{ $lessons->total() }} درس
                </div>
                <div>
                    {{ $lessons->links() }}
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-3x text-muted"></i>
                </div>                <h5 class="text-muted mb-3">
                    @if(request()->hasAny(['search', 'day_filter', 'teacher_filter', 'time_filter', 'students_filter', 'status_filter']))
                        لا توجد دروس مطابقة للبحث
                    @else
                        لا توجد دروس
                    @endif
                </h5>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'day_filter', 'teacher_filter', 'time_filter', 'students_filter', 'status_filter']))
                        جرب تغيير معايير البحث أو إزالة بعض الفلاتر
                    @else
                        ابدأ بإضافة درس جديد لبناء جدولك الدراسي
                    @endif
                </p>
                <div>
                    @if(request()->hasAny(['search', 'day_filter', 'teacher_filter', 'time_filter', 'students_filter', 'status_filter']))
                        <a href="{{ route('admin.lessons.index') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-redo me-2"></i>
                            مسح الفلاتر
                        </a>
                    @endif
                    <a href="{{ route('admin.lessons.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة درس جديد
                    </a>
                </div>
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
    document.querySelectorAll('.lesson-row').forEach(function(row) {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'all 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.transform = 'scale(1)';
        });
    });

    // حفظ حالة الفلاتر في localStorage
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    // تحديث URL بدون إعادة تحميل الصفحة
    function updateURLWithoutReload() {
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newURL);
    }
</script>
@endpush
