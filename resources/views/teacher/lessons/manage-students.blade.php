@extends('layouts.admin')

@section('title', 'إدارة طلاب الدرس')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">    <div>
        <h1 class="h3 mb-0">إدارة طلاب الدرس</h1>
        <p class="text-muted mb-0">{{ $lesson->subject }} - {{ $lesson->day_of_week }}</p>
    </div>
    <div>
        <a href="{{ route('teacher.lessons.show', $lesson) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للدرس
        </a>
        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-info">
            <i class="fas fa-list"></i> جميع الدروس
        </a>
    </div>
</div>

<div class="row">
    <!-- إحصائيات سريعة -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $lesson->students()->count() }}</h3>
                        <small>إجمالي الطلاب</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $lesson->max_students ?? '∞' }}</h3>
                        <small>الحد الأقصى</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $lesson->max_students ? ($lesson->max_students - $lesson->students()->count()) : '∞' }}</h3>
                        <small>مقاعد متاحة</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $averageAttendance }}%</h3>
                        <small>معدل الحضور</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إضافة طلاب جدد -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus"></i>
                    إضافة طلاب جدد
                </h5>
            </div>
            <div class="card-body">
                @if($availableStudents->count() > 0)
                    <form action="{{ route('teacher.lessons.add-student', $lesson) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="student_id" class="form-label">اختر الطالب</label>
                            <select class="form-select @error('student_id') is-invalid @enderror" 
                                    id="student_id" 
                                    name="student_id" 
                                    required>
                                <option value="">اختر طالب...</option>
                                @foreach($availableStudents as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i> إضافة الطالب
                        </button>
                    </form>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <p>لا توجد طلاب متاحين للإضافة</p>
                        <small>جميع الطلاب مسجلين في هذا الدرس بالفعل</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- إجراءات سريعة -->
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt"></i>
                    إجراءات سريعة
                </h5>
            </div>
            <div class="card-body">
                <a href="{{ route('teacher.attendances.lesson', $lesson) }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-clipboard-check"></i> تسجيل الحضور
                </a>
                <a href="{{ route('teacher.lessons.edit', $lesson) }}" class="btn btn-outline-warning w-100 mb-2">
                    <i class="fas fa-edit"></i> تعديل الدرس
                </a>
                <button class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#removeAllModal">
                    <i class="fas fa-user-minus"></i> إزالة جميع الطلاب
                </button>
            </div>
        </div>
    </div>

    <!-- قائمة الطلاب الحاليين -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users"></i>
                    الطلاب المسجلين ({{ $lesson->students()->count() }})
                </h5>
                <div>
                    <button class="btn btn-light btn-sm" onclick="selectAllStudents()">
                        <i class="fas fa-check-square"></i> تحديد الكل
                    </button>
                    <button class="btn btn-light btn-sm" onclick="unselectAllStudents()">
                        <i class="fas fa-square"></i> إلغاء التحديد
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if($lesson->students()->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" onchange="toggleAllStudents()">
                                    </th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>معدل الحضور</th>
                                    <th width="120">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lesson->students as $student)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="student-checkbox" value="{{ $student->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <strong>{{ $student->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        <small class="text-muted">{{ $student->pivot->created_at->format('Y/m/d') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $studentAttendanceRate = $studentAttendanceRates[$student->id] ?? 0;
                                        @endphp
                                        <span class="badge {{ $studentAttendanceRate >= 80 ? 'bg-success' : ($studentAttendanceRate >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $studentAttendanceRate }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('teacher.attendances.student', [$lesson, $student]) }}" 
                                               class="btn btn-outline-info" 
                                               title="عرض سجل الحضور">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="removeStudent({{ $student->id }}, '{{ $student->name }}')"
                                                    title="إزالة من الدرس">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- إجراءات جماعية -->
                    <div class="p-3 bg-light border-top d-none" id="bulkActions">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">
                                <span id="selectedCount">0</span> طالب محدد
                            </span>
                            <div>
                                <button class="btn btn-outline-danger btn-sm" onclick="removeSelectedStudents()">
                                    <i class="fas fa-user-minus"></i> إزالة المحددين
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-user-graduate fa-4x mb-3"></i>
                        <h5>لا توجد طلاب مسجلين</h5>
                        <p>ابدأ بإضافة طلاب إلى هذا الدرس</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal لحذف طالب واحد -->
<div class="modal fade" id="removeStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">إزالة طالب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من إزالة الطالب <strong id="studentNameToRemove"></strong> من هذا الدرس؟</p>
                <small class="text-muted">سيتم حذف جميع سجلات الحضور المرتبطة بهذا الطالب في هذا الدرس.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="removeStudentForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">نعم، أزل الطالب</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal لحذف جميع الطلاب -->
<div class="modal fade" id="removeAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">إزالة جميع الطلاب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من إزالة جميع الطلاب من هذا الدرس؟</p>
                <small class="text-muted">سيتم حذف جميع سجلات الحضور المرتبطة بهذا الدرس.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('teacher.lessons.remove-all-students', $lesson) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">نعم، أزل جميع الطلاب</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
function toggleAllStudents() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.student-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function selectAllStudents() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    const selectAll = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    selectAll.checked = true;
    
    updateBulkActions();
}

function unselectAllStudents() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    const selectAll = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    selectAll.checked = false;
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    selectedCount.textContent = checkedBoxes.length;
    
    if (checkedBoxes.length > 0) {
        bulkActions.classList.remove('d-none');
    } else {
        bulkActions.classList.add('d-none');
    }
}

function removeStudent(studentId, studentName) {
    document.getElementById('studentNameToRemove').textContent = studentName;
    document.getElementById('removeStudentForm').action = 
        `{{ route('teacher.lessons.remove-student', [$lesson, '__STUDENT_ID__']) }}`.replace('__STUDENT_ID__', studentId);
    
    new bootstrap.Modal(document.getElementById('removeStudentModal')).show();
}

function removeSelectedStudents() {
    const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
    const studentIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (studentIds.length === 0) {
        alert('يرجى تحديد طلاب للإزالة');
        return;
    }
    
    if (confirm(`هل أنت متأكد من إزالة ${studentIds.length} طالب من الدرس؟`)) {
        // إرسال طلب إزالة جماعية
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("teacher.lessons.remove-students", $lesson) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        studentIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'student_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// إضافة مستمعات الأحداث للـ checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});
</script>
@endpush
