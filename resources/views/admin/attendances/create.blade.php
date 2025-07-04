@extends('layouts.admin')

@section('title', 'تسجيل حضور جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">تسجيل حضور جديد</h1>    <a href="{{ auth()->user()->role === 'teacher' ? route('teacher.attendances.index') : route('admin.attendances.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        العودة لسجلات الحضور
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ auth()->user()->role === 'teacher' ? route('teacher.attendances.store') : route('admin.attendances.store') }}">>
            @csrf
              <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="student_id" class="form-label">الطالب <span class="text-danger">*</span></label>
                    <select class="form-control @error('student_id') is-invalid @enderror" 
                            id="student_id" 
                            name="student_id" 
                            required>
                        <option value="">اختر الطالب</option>
                        @foreach(\App\Models\User::where('role', 'student')->orderBy('name')->get() as $student)
                            <option value="{{ $student->id }}" @if(old('student_id') == $student->id) selected @endif>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="lesson_id" class="form-label">الدرس <span class="text-danger">*</span></label>
                    <select class="form-control @error('lesson_id') is-invalid @enderror" 
                            id="lesson_id" 
                            name="lesson_id" 
                            required 
                            disabled>
                        <option value="">اختر الطالب أولاً</option>
                    </select>
                    @error('lesson_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                    <input type="date" 
                           class="form-control @error('date') is-invalid @enderror" 
                           id="date" 
                           name="date" 
                           value="{{ old('date', date('Y-m-d')) }}" 
                           required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">حالة الحضور <span class="text-danger">*</span></label>
                    <select class="form-control @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="">اختر الحالة</option>
                        <option value="present" @if(old('status') == 'present') selected @endif>حاضر</option>
                        <option value="absent" @if(old('status') == 'absent') selected @endif>غائب</option>
                        <option value="late" @if(old('status') == 'late') selected @endif>متأخر</option>
                        <option value="excused" @if(old('status') == 'excused') selected @endif>غياب بعذر</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">                <a href="{{ auth()->user()->role === 'teacher' ? route('teacher.attendances.index') : route('admin.attendances.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    حفظ الحضور
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('student_id').addEventListener('change', function() {
    const studentId = this.value;
    const lessonSelect = document.getElementById('lesson_id');
    
    if (studentId) {
        // تحديد المسار بناءً على دور المستخدم
        @if(auth()->user()->role === 'teacher')
            const url = `{{ route('teacher.students.lessons', ':student_id') }}`.replace(':student_id', studentId);
        @else
            const url = `{{ route('admin.students.lessons', ':student_id') }}`.replace(':student_id', studentId);
        @endif
        
        lessonSelect.innerHTML = '<option value="">جاري التحميل...</option>';
        lessonSelect.disabled = true;
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.lessons) {
                    lessonSelect.innerHTML = '<option value="">اختر الدرس</option>';
                    data.lessons.forEach(lesson => {
                        lessonSelect.innerHTML += `<option value="${lesson.id}">${lesson.subject}</option>`;
                    });
                    lessonSelect.disabled = false;
                } else {
                    throw new Error(data.error || 'خطأ في البيانات');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                lessonSelect.innerHTML = '<option value="">خطأ في تحميل الدروس</option>';
                lessonSelect.disabled = false;
                alert('خطأ في تحميل قائمة الدروس: ' + error.message);
            });
    } else {
        lessonSelect.innerHTML = '<option value="">اختر الطالب أولاً</option>';
        lessonSelect.disabled = true;
    }
});
</script>
@endpush
