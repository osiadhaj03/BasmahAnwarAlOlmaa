@extends('layouts.admin')

@section('title', 'إضافة درس جديد')

@section('content')
<div class="anwar-header islamic-pattern-enhanced">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="anwar-title">
            <i class="fas fa-plus-circle anwar-text-gold me-2"></i>
            إضافة درس جديد
        </h1>
        <a href="{{ route('admin.lessons.index') }}" class="btn anwar-btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            العودة للدروس
        </a>
    </div>
</div>

<div class="card anwar-card islamic-pattern-subtle">
    <div class="card-body anwar-card-body">
        <form method="POST" action="{{ route('admin.lessons.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group-anwar">
                        <label for="subject" class="form-label-anwar">المادة <span class="anwar-text-red">*</span></label>
                        <input type="text" 
                               class="form-control-anwar @error('subject') is-invalid @enderror" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}" 
                               required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group-anwar">
                        <label for="teacher_id" class="form-label-anwar">المعلم <span class="anwar-text-red">*</span></label>
                        <select class="form-control-anwar @error('teacher_id') is-invalid @enderror" 
                                id="teacher_id" 
                                name="teacher_id" 
                                required
                                @if(auth()->user()->role === 'teacher') disabled @endif>
                            <option value="">اختر المعلم</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" 
                                        @if(old('teacher_id') == $teacher->id || (auth()->user()->role === 'teacher' && auth()->id() == $teacher->id)) selected @endif>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @if(auth()->user()->role === 'teacher')
                            <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
                        @endif
                        @error('teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group-anwar">
                        <label for="day_of_week" class="form-label-anwar">يوم الأسبوع <span class="anwar-text-red">*</span></label>
                        <select class="form-control-anwar @error('day_of_week') is-invalid @enderror" 
                                id="day_of_week" 
                                name="day_of_week" 
                                required>
                            <option value="">اختر اليوم</option>
                            <option value="sunday" @if(old('day_of_week') == 'sunday') selected @endif>الأحد</option>
                            <option value="monday" @if(old('day_of_week') == 'monday') selected @endif>الإثنين</option>
                            <option value="tuesday" @if(old('day_of_week') == 'tuesday') selected @endif>الثلاثاء</option>
                            <option value="wednesday" @if(old('day_of_week') == 'wednesday') selected @endif>الأربعاء</option>
                            <option value="thursday" @if(old('day_of_week') == 'thursday') selected @endif>الخميس</option>
                            <option value="friday" @if(old('day_of_week') == 'friday') selected @endif>الجمعة</option>
                            <option value="saturday" @if(old('day_of_week') == 'saturday') selected @endif>السبت</option>
                        </select>
                        @error('day_of_week')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="form-group-anwar">
                        <label for="start_time" class="form-label-anwar">وقت البداية <span class="anwar-text-red">*</span></label>
                        <input type="time" 
                               class="form-control-anwar @error('start_time') is-invalid @enderror" 
                               id="start_time" 
                               name="start_time" 
                               value="{{ old('start_time') }}" 
                               required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="form-group-anwar">
                        <label for="end_time" class="form-label-anwar">وقت النهاية <span class="anwar-text-red">*</span></label>
                        <input type="time" 
                               class="form-control-anwar @error('end_time') is-invalid @enderror" 
                               id="end_time" 
                               name="end_time" 
                               value="{{ old('end_time') }}" 
                               required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group-anwar">
                        <label for="status" class="form-label-anwar">حالة الدرس <span class="anwar-text-red">*</span></label>
                        <select class="form-control-anwar @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="">اختر حالة الدرس</option>
                            <option value="scheduled" @if(old('status') == 'scheduled') selected @endif>مجدول</option>
                            <option value="active" @if(old('status') == 'active') selected @endif>نشط</option>
                            <option value="completed" @if(old('status') == 'completed') selected @endif>مكتمل</option>
                            <option value="cancelled" @if(old('status') == 'cancelled') selected @endif>ملغي</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-group-anwar">
                    <label for="description" class="form-label-anwar">وصف الدرس</label>
                    <textarea class="form-control-anwar @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4" 
                              placeholder="أدخل وصف تفصيلي للدرس...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <div class="form-group-anwar">
                    <label class="form-label-anwar">
                        <i class="fas fa-users anwar-text-gold me-2"></i>
                        الطلاب
                    </label>
                    <div class="students-selection anwar-border-gold p-3 rounded">
                        <div class="row">
                            @foreach($students as $student)
                                <div class="col-md-4 col-lg-3 mb-2">
                                    <div class="form-check anwar-checkbox">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="students[]" 
                                               value="{{ $student->id }}" 
                                               id="student_{{ $student->id }}"
                                               @if(is_array(old('students')) && in_array($student->id, old('students'))) checked @endif>
                                        <label class="form-check-label anwar-text-dark" for="student_{{ $student->id }}">
                                            {{ $student->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('students')
                        <div class="anwar-text-red small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.lessons.index') }}" class="btn anwar-btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    إلغاء
                </a>
                <button type="submit" class="btn anwar-btn-primary">
                    <i class="fas fa-save me-2"></i>
                    حفظ الدرس
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
