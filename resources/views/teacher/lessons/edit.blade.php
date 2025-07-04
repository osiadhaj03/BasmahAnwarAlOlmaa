@extends('layouts.admin')

@section('title', 'تعديل الدرس')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">تعديل الدرس</h1>
    <div>
        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <a href="{{ route('teacher.lessons.show', $lesson) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> عرض التفاصيل
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit"></i>
                    بيانات الدرس
                </h5>
            </div>            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle"></i> يرجى تصحيح الأخطاء التالية:</h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('teacher.lessons.update', $lesson) }}" method="POST">
                    @csrf
                    @method('PUT')
                      <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="subject" class="form-label">المادة <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject', $lesson->subject) }}" 
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><div class="row">                        <div class="col-md-4 mb-3">
                            <label for="day_of_week" class="form-label">يوم الأسبوع <span class="text-danger">*</span></label>
                            <select class="form-select @error('day_of_week') is-invalid @enderror" 
                                    id="day_of_week" 
                                    name="day_of_week" 
                                    required>
                                <option value="">اختر اليوم</option>
                                @foreach($daysOfWeek as $value => $label)
                                    <option value="{{ $value }}" {{ old('day_of_week', $lesson->day_of_week) === $value ? 'selected' : '' }}>
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
                                   value="{{ old('start_time', $lesson->start_time ? \Carbon\Carbon::parse($lesson->start_time)->format('H:i') : '') }}" 
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
                                   value="{{ old('end_time', $lesson->end_time ? \Carbon\Carbon::parse($lesson->end_time)->format('H:i') : '') }}" 
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="وصف اختياري للدرس">{{ old('description', $lesson->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>                    <div class="mb-3">
                        <label for="status" class="form-label">حالة الدرس <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status"
                                required>
                            <option value="active" {{ old('status', $lesson->status) == 'active' ? 'selected' : '' }}>
                                نشط
                            </option>
                            <option value="inactive" {{ old('status', $lesson->status) == 'inactive' ? 'selected' : '' }}>
                                غير نشط
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route('teacher.lessons.show', $lesson) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        </div>
                        <div>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> حذف الدرس
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i>
                    معلومات الدرس
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>تاريخ الإنشاء:</strong><br>
                    <small class="text-muted">{{ $lesson->created_at->format('Y/m/d - H:i') }}</small>
                </div>
                <div class="mb-3">
                    <strong>آخر تحديث:</strong><br>
                    <small class="text-muted">{{ $lesson->updated_at->format('Y/m/d - H:i') }}</small>
                </div>
                <div class="mb-3">
                    <strong>عدد الطلاب الحالي:</strong><br>
                    <span class="badge bg-primary">{{ $lesson->students()->count() }} طالب</span>
                </div>
                @if($lesson->max_students)
                <div class="mb-3">
                    <strong>المساحة المتاحة:</strong><br>
                    <span class="badge bg-success">{{ $lesson->max_students - $lesson->students()->count() }} مقعد متاح</span>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users"></i>
                    إدارة الطلاب
                </h5>
            </div>
            <div class="card-body">
                <a href="{{ route('teacher.lessons.manage-students', $lesson) }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-user-plus"></i> إضافة/إزالة طلاب
                </a>
                <a href="{{ route('teacher.attendances.lesson', $lesson) }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-clipboard-check"></i> تسجيل الحضور
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal للحذف -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">هل أنت متأكد من حذف هذا الدرس؟</p>
                <small class="text-muted">سيتم حذف جميع بيانات الحضور المرتبطة بهذا الدرس أيضاً.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('teacher.lessons.destroy', $lesson) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">نعم، احذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endpush
