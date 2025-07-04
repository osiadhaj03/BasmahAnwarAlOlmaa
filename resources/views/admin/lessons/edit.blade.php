@extends('layouts.admin')

@section('title', 'تعديل الدرس')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">تعديل الدرس</h1>
                <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST">
                        @csrf
                        @method('PUT')
                          <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subject" class="form-label">المادة <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject', $lesson->subject ?: $lesson->name) }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if(auth()->user()->role === 'admin')
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="teacher_id" class="form-label">المعلم <span class="text-danger">*</span></label>
                                    <select class="form-control @error('teacher_id') is-invalid @enderror" 
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
                            <div class="col-md-4 mb-3">
                                <label for="day_of_week" class="form-label">يوم الأسبوع <span class="text-danger">*</span></label>
                                <select class="form-control @error('day_of_week') is-invalid @enderror" 
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

                            <div class="col-md-4 mb-3">
                                <label for="start_time" class="form-label">وقت البداية <span class="text-danger">*</span></label>
                                <input type="time" 
                                       class="form-control @error('start_time') is-invalid @enderror" 
                                       id="start_time" 
                                       name="start_time" 
                                       value="{{ old('start_time', $lesson->start_time ? $lesson->start_time->format('H:i') : '') }}" 
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
