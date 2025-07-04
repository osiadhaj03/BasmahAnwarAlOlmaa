@extends('layouts.admin')

@section('title', 'تعديل الحضور')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">تعديل سجل الحضور</h1>
                <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.attendances.update', $attendance) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lesson_id" class="form-label">الدرس <span class="text-danger">*</span></label>
                                    <select class="form-control @error('lesson_id') is-invalid @enderror" 
                                            id="lesson_id" name="lesson_id" required>
                                        <option value="">اختر الدرس</option>
                                        @foreach($lessons as $lesson)
                                            <option value="{{ $lesson->id }}" 
                                                {{ old('lesson_id', $attendance->lesson_id) == $lesson->id ? 'selected' : '' }}>
                                                {{ $lesson->name }} - {{ $lesson->teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lesson_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">الطالب <span class="text-danger">*</span></label>
                                    <select class="form-control @error('student_id') is-invalid @enderror" 
                                            id="student_id" name="student_id" required>
                                        <option value="">اختر الطالب</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" 
                                                {{ old('student_id', $attendance->student_id) == $student->id ? 'selected' : '' }}>
                                                {{ $student->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                           id="date" name="date" value="{{ old('date', $attendance->date) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">حالة الحضور <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">اختر الحالة</option>
                                        <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>
                                            حاضر
                                        </option>
                                        <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>
                                            غائب
                                        </option>
                                        <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>
                                            متأخر
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="أضف أي ملاحظات إضافية...">{{ old('notes', $attendance->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>تحديث سجل الحضور
                            </button>
                            <a href="{{ route('admin.attendances.show', $attendance) }}" class="btn btn-info">
                                <i class="fas fa-eye me-2"></i>عرض السجل
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lessonSelect = document.getElementById('lesson_id');
    const studentSelect = document.getElementById('student_id');
    
    lessonSelect.addEventListener('change', function() {
        const lessonId = this.value;
        
        if (!lessonId) {
            studentSelect.innerHTML = '<option value="">اختر الطالب</option>';
            return;
        }
        
        fetch(`/admin/lessons/${lessonId}/students`)
            .then(response => response.json())
            .then(students => {
                studentSelect.innerHTML = '<option value="">اختر الطالب</option>';
                students.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = student.name;
                    if (student.id == {{ $attendance->student_id }}) {
                        option.selected = true;
                    }
                    studentSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في تحميل قائمة الطلاب');
            });
    });
});
</script>
@endsection
