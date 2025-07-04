@extends('layouts.admin')

@section('title', 'تسجيل الحضور الجماعي')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-users-check me-2"></i>
                        تسجيل الحضور الجماعي
                    </h4>
                    <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        العودة لسجلات الحضور
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- اختيار الدرس والتاريخ -->
                    <form id="lessonForm" class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="lesson_select" class="form-label">اختر الدرس <span class="text-danger">*</span></label>
                                <select class="form-control" id="lesson_select" required>
                                    <option value="">اختر الدرس</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}">
                                            {{ $lesson->subject }} - {{ $lesson->name }}
                                            @if(auth()->user()->role === 'admin')
                                                ({{ $lesson->teacher->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="attendance_date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="attendance_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="loadStudents()">
                                    <i class="fas fa-search me-2"></i>
                                    تحميل الطلاب
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- قائمة الطلاب -->
                    <div id="studentsContainer" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">قائمة الطلاب</h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success btn-sm" onclick="markAll('present')">
                                    <i class="fas fa-check-circle me-1"></i>
                                    تحديد الكل حاضر
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="markAll('absent')">
                                    <i class="fas fa-times-circle me-1"></i>
                                    تحديد الكل غائب
                                </button>
                            </div>
                        </div>

                        <form id="attendanceForm" method="POST" action="{{ route('admin.attendances.bulk-store') }}">
                            @csrf
                            <input type="hidden" id="bulk_lesson_id" name="lesson_id">
                            <input type="hidden" id="bulk_date" name="date">
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الطالب</th>
                                            <th>البريد الإلكتروني</th>
                                            <th class="text-center">حالة الحضور</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentsTableBody">
                                        <!-- الطلاب سيتم تحميلهم هنا -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="button" class="btn btn-secondary me-2" onclick="resetForm()">
                                        <i class="fas fa-undo me-2"></i>
                                        إعادة تعيين
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ جميع الحضور
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- رسالة عدم وجود طلاب -->
                    <div id="noStudentsMessage" class="text-center py-5" style="display: none;">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد طلاب مسجلين في هذا الدرس</h5>
                        <p class="text-muted">تأكد من تسجيل الطلاب في الدرس أولاً</p>
                        <a href="{{ route('admin.lessons.index') }}" class="btn btn-primary">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            إدارة الدروس
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentStudents = [];

function loadStudents() {
    const lessonId = document.getElementById('lesson_select').value;
    const date = document.getElementById('attendance_date').value;
    
    if (!lessonId || !date) {
        alert('يرجى اختيار الدرس والتاريخ');
        return;
    }

    // إظهار loading
    const container = document.getElementById('studentsContainer');
    const noStudentsMsg = document.getElementById('noStudentsMessage');
    container.style.display = 'none';
    noStudentsMsg.style.display = 'none';

    fetch(`/admin/lessons/${lessonId}/students`)
        .then(response => response.json())
        .then(data => {
            if (data.students && data.students.length > 0) {
                currentStudents = data.students;
                renderStudentsTable(data.students);
                
                // تعبئة البيانات المخفية
                document.getElementById('bulk_lesson_id').value = lessonId;
                document.getElementById('bulk_date').value = date;
                
                container.style.display = 'block';
            } else {
                noStudentsMsg.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطأ في تحميل بيانات الطلاب');
        });
}

function renderStudentsTable(students) {
    const tbody = document.getElementById('studentsTableBody');
    tbody.innerHTML = '';
    
    students.forEach((student, index) => {
        const row = `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                            ${student.name.charAt(0)}
                        </div>
                        <strong>${student.name}</strong>
                    </div>
                </td>
                <td>${student.email || 'غير محدد'}</td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="attendance[${student.id}]" 
                               id="present_${student.id}" value="present" autocomplete="off">
                        <label class="btn btn-outline-success" for="present_${student.id}">
                            <i class="fas fa-check"></i> حاضر
                        </label>

                        <input type="radio" class="btn-check" name="attendance[${student.id}]" 
                               id="late_${student.id}" value="late" autocomplete="off">
                        <label class="btn btn-outline-warning" for="late_${student.id}">
                            <i class="fas fa-clock"></i> متأخر
                        </label>

                        <input type="radio" class="btn-check" name="attendance[${student.id}]" 
                               id="absent_${student.id}" value="absent" autocomplete="off">
                        <label class="btn btn-outline-danger" for="absent_${student.id}">
                            <i class="fas fa-times"></i> غائب
                        </label>

                        <input type="radio" class="btn-check" name="attendance[${student.id}]" 
                               id="excused_${student.id}" value="excused" autocomplete="off">
                        <label class="btn btn-outline-info" for="excused_${student.id}">
                            <i class="fas fa-user-check"></i> بعذر
                        </label>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" 
                           name="notes[${student.id}]" 
                           placeholder="ملاحظات اختيارية">
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function markAll(status) {
    currentStudents.forEach(student => {
        const radio = document.getElementById(`${status}_${student.id}`);
        if (radio) {
            radio.checked = true;
        }
    });
}

function resetForm() {
    document.getElementById('studentsContainer').style.display = 'none';
    document.getElementById('noStudentsMessage').style.display = 'none';
    document.getElementById('lesson_select').value = '';
    document.getElementById('attendance_date').value = '{{ date("Y-m-d") }}';
    currentStudents = [];
}

// إرسال النموذج
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
    
    if (checkedRadios.length === 0) {
        e.preventDefault();
        alert('يرجى تحديد حالة الحضور لطالب واحد على الأقل');
        return false;
    }
    
    // تأكيد الحفظ
    if (!confirm(`هل أنت متأكد من حفظ حضور ${checkedRadios.length} طالب؟`)) {
        e.preventDefault();
        return false;
    }
});
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

.btn-check:checked + .btn {
    transform: scale(1.05);
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    margin: 0 1px;
}
</style>
@endsection
