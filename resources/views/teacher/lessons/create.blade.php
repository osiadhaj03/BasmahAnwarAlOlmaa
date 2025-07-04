@extends('layouts.admin')

@section('title', 'إضافة درس جديد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">إضافة درس جديد</h1>
        <p class="text-muted mb-0">إنشاء درس جديد وإضافته لجدولك</p>
    </div>
    <div>
        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> العودة للدروس
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle"></i>
                    معلومات الدرس الجديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.lessons.store') }}" method="POST">
                    @csrf
                      <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="subject" class="form-label">المادة <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}"
                                   placeholder="اكتب اسم المادة (مثال: الرياضيات، العلوم...)"
                                   required
                                   list="subjects-list">
                            
                            <!-- قائمة المواد المقترحة -->
                            <datalist id="subjects-list">
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject }}">
                                @endforeach
                            </datalist>
                            
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">يمكنك كتابة أي مادة تريدها</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="day_of_week" class="form-label">يوم الأسبوع <span class="text-danger">*</span></label>
                            <select class="form-select @error('day_of_week') is-invalid @enderror" 
                                    id="day_of_week" 
                                    name="day_of_week" 
                                    required>
                                <option value="">اختر اليوم</option>
                                @foreach($daysOfWeek as $value => $label)
                                    <option value="{{ $value }}" {{ old('day_of_week') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="start_time" class="form-label">وقت البداية <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" 
                                   name="start_time" 
                                   value="{{ old('start_time') }}" 
                                   required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="end_time" class="form-label">وقت النهاية <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" 
                                   name="end_time" 
                                   value="{{ old('end_time') }}" 
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">وصف الدرس</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="وصف مختصر عن محتوى الدرس والأهداف...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @error('time_conflict')
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> حفظ الدرس
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- معاينة الجدول -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="fas fa-calendar"></i>
                    دروسي الحالية
                </h6>
            </div>
            <div class="card-body">
                @php
                    $currentLessons = \App\Models\Lesson::where('teacher_id', $teacher->id)
                        ->orderBy('day_of_week')
                        ->orderBy('start_time')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($currentLessons->count() > 0)
                    @foreach($currentLessons as $lesson)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div>
                            <small class="fw-bold">{{ $lesson->subject }}</small>
                            @if($lesson->day_of_week && $lesson->start_time)
                            <br>
                            <small class="text-muted">
                                {{ ucfirst($lesson->day_of_week) }} - 
                                {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                            </small>
                            @endif
                        </div>
                        <span class="badge bg-primary">{{ $lesson->students_count }} طالب</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">لا توجد دروس مسجلة بعد</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // التحقق من تعارض الأوقات
    const daySelect = document.getElementById('day_of_week');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    function checkTimeConflict() {
        if (daySelect.value && startTimeInput.value && endTimeInput.value) {
            // يمكن إضافة AJAX call للتحقق من التعارض
            console.log('Checking time conflict...');
        }
    }
    
    daySelect.addEventListener('change', checkTimeConflict);
    startTimeInput.addEventListener('change', checkTimeConflict);
    endTimeInput.addEventListener('change', checkTimeConflict);
    
    // التحقق من أن وقت النهاية بعد وقت البداية
    function validateTimes() {
        if (startTimeInput.value && endTimeInput.value) {
            if (endTimeInput.value <= startTimeInput.value) {
                endTimeInput.setCustomValidity('وقت النهاية يجب أن يكون بعد وقت البداية');
            } else {
                endTimeInput.setCustomValidity('');
            }
        }
    }
    
    startTimeInput.addEventListener('change', validateTimes);
    endTimeInput.addEventListener('change', validateTimes);
    
    // التركيز على حقل المادة
    document.getElementById('subject').focus();
});
</script>
@endsection
