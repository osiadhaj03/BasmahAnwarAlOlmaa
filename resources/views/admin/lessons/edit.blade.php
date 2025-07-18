@extends('layouts.admin')

@section('title', 'تعديل الدرس - أنوار العلماء')

@section('content')
<div class="container-fluid islamic-pattern-enhanced">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 fade-in">
                <div>
                    <h1 class="text-anwar-gradient mb-1 heading-anwar-center">
                        <i class="fas fa-edit me-2"></i>
                        تعديل الدرس
                    </h1>
                    <p class="text-anwar-teal mb-0">تحديث بيانات الدرس في نظام أنوار العلماء</p>
                </div>
                <a href="{{ route('admin.lessons.index') }}" class="btn-anwar-teal hover-lift">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
            </div>

            <div class="card-anwar shadow-anwar fade-in">
                <div class="card-body p-4">
                    <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                          <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-anwar">
                                    <label for="subject" class="form-label-anwar">
                                        <i class="fas fa-book me-2 text-anwar-gold"></i>
                                        المادة <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control-anwar @error('subject') is-invalid @enderror hover-lift" 
                                           id="subject" name="subject" value="{{ old('subject', $lesson->subject ?: $lesson->name) }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if(auth()->user()->role === 'admin')
                            <div class="col-md-6">
                                <div class="form-group-anwar">
                                    <label for="teacher_id" class="form-label-anwar">
                                        <i class="fas fa-chalkboard-teacher me-2 text-anwar-teal"></i>
                                        المعلم <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control-anwar @error('teacher_id') is-invalid @enderror hover-lift" 
                                            id="teacher_id" name="teacher_id" required>
                                        <option value="">اختر المعلم</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                {{ old('teacher_id', $lesson->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>                            @else
                                <input type="hidden" name="teacher_id" value="{{ auth()->user()->id }}">
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group-anwar">
                                <label for="day_of_week" class="form-label-anwar">
                                    <i class="fas fa-calendar-day me-2 text-anwar-gold"></i>
                                    يوم الأسبوع <span class="text-danger">*</span>
                                </label>
                                <select class="form-control-anwar @error('day_of_week') is-invalid @enderror hover-lift" 
                                        id="day_of_week" 
                                        name="day_of_week" 
                                        required>
                                    <option value="">اختر اليوم</option>
                                    <option value="sunday" @if(old('day_of_week', $lesson->day_of_week) == 'sunday') selected @endif>الأحد</option>
                                    <option value="monday" @if(old('day_of_week', $lesson->day_of_week) == 'monday') selected @endif>الإثنين</option>
                                    <option value="tuesday" @if(old('day_of_week', $lesson->day_of_week) == 'tuesday') selected @endif>الثلاثاء</option>
                                    <option value="wednesday" @if(old('day_of_week', $lesson->day_of_week) == 'wednesday') selected @endif>الأربعاء</option>
                                    <option value="thursday" @if(old('day_of_week', $lesson->day_of_week) == 'thursday') selected @endif>الخميس</option>
                                    <option value="friday" @if(old('day_of_week', $lesson->day_of_week) == 'friday') selected @endif>الجمعة</option>
                                    <option value="saturday" @if(old('day_of_week', $lesson->day_of_week) == 'saturday') selected @endif>السبت</option>
                                </select>
                                @error('day_of_week')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group-anwar">
                                <label for="start_time" class="form-label-anwar">
                                    <i class="fas fa-clock me-2 text-anwar-teal"></i>
                                    وقت البداية <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control-anwar @error('start_time') is-invalid @enderror hover-lift" 
                                       id="start_time" 
                                       name="start_time" 
                                       value="{{ old('start_time', $lesson->start_time ? $lesson->start_time->format('H:i') : '') }}" 
                                       required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 form-group-anwar">
                                <label for="end_time" class="form-label-anwar">
                                    <i class="fas fa-clock me-2 text-anwar-gold"></i>
                                    وقت النهاية <span class="text-danger">*</span>
                                </label>
                                <input type="time" 
                                       class="form-control-anwar @error('end_time') is-invalid @enderror hover-lift" 
                                       id="end_time" 
                                       name="end_time" 
                                       value="{{ old('end_time', $lesson->end_time ? $lesson->end_time->format('H:i') : '') }}" 
                                       required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">حالة الدرس <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="">اختر حالة الدرس</option>
                                        <option value="scheduled" @if(old('status', $lesson->status) == 'scheduled') selected @endif>مجدول</option>
                                        <option value="active" @if(old('status', $lesson->status) == 'active') selected @endif>نشط</option>
                                        <option value="completed" @if(old('status', $lesson->status) == 'completed') selected @endif>مكتمل</option>
                                        <option value="cancelled" @if(old('status', $lesson->status) == 'cancelled') selected @endif>ملغي</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف الدرس</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $lesson->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="students" class="form-label">الطلاب المسجلين في الدرس</label>
                            <div class="border rounded p-3 bg-light">
                                <div class="row">
                                    @foreach($students as $student)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="student_{{ $student->id }}" 
                                                       name="students[]" 
                                                       value="{{ $student->id }}"
                                                       {{ in_array($student->id, old('students', $lesson->students->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="student_{{ $student->id }}">
                                                    {{ $student->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('students')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>تحديث الدرس
                            </button>
                            <a href="{{ route('admin.lessons.show', $lesson) }}" class="btn btn-info">
                                <i class="fas fa-eye me-2"></i>عرض الدرس
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
