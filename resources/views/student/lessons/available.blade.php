@extends('layouts.student')

@section('title', 'الدروس المتاحة للتسجيل')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-book-open me-2 text-primary"></i>
                إدارة تسجيل الدروس
            </h2>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>
                العودة للوحة التحكم
            </a>
        </div>

        <!-- Available Lessons Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    الدروس المتاحة للتسجيل ({{ $availableLessons->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($availableLessons->count() > 0)
                    <div class="row">
                        @foreach($availableLessons as $lesson)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card lesson-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="card-title mb-1">{{ $lesson->name ?? $lesson->subject }}</h6>
                                        @if($lesson->start_time)
                                        <span class="badge bg-info">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-chalkboard-teacher me-1"></i>
                                            {{ $lesson->teacher->name }}
                                        </small>
                                        @if($lesson->day_of_week)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $lesson->day_of_week }}
                                        </small>
                                        @endif
                                        @if($lesson->location)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $lesson->location }}
                                        </small>
                                        @endif
                                    </div>
                                    
                                    @if($lesson->description)
                                    <p class="card-text small text-muted mb-3">{{ Str::limit($lesson->description, 80) }}</p>
                                    @endif
                                    
                                    <div class="d-grid">
                                        <form method="POST" action="{{ route('student.lessons.register', $lesson->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-user-plus me-2"></i>
                                                تسجيل في الدرس
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-muted">ممتاز! أنت مسجل في جميع الدروس المتاحة</h5>
                        <p class="text-muted">لا توجد دروس إضافية متاحة للتسجيل حالياً</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- My Lessons Section -->
        <div class="card">
            <div class="card-header" style="background: var(--anwar-gradient-gold); color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>
                    دروسي المسجل بها ({{ $myLessons->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($myLessons->count() > 0)
                    <div class="row">
                        @foreach($myLessons as $lesson)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card lesson-card h-100 border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="card-title mb-1">{{ $lesson->name ?? $lesson->subject }}</h6>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            مسجل
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-chalkboard-teacher me-1"></i>
                                            {{ $lesson->teacher->name }}
                                        </small>
                                        @if($lesson->day_of_week)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $lesson->day_of_week }}
                                        </small>
                                        @endif
                                        @if($lesson->start_time)
                                        <small class="text-muted d-block">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                                        </small>
                                        @endif
                                    </div>
                                    
                                    @if($lesson->description)
                                    <p class="card-text small text-muted mb-3">{{ Str::limit($lesson->description, 80) }}</p>
                                    @endif
                                    
                                    <div class="d-grid">
                                        <form method="POST" action="{{ route('student.lessons.unregister', $lesson->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('هل أنت متأكد من إلغاء التسجيل في هذا الدرس؟')">
                                                <i class="fas fa-user-minus me-2"></i>
                                                إلغاء التسجيل
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لم تسجل في أي دروس بعد</h5>
                        <p class="text-muted">ابدأ بتسجيل نفسك في الدروس المتاحة أعلاه</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .lesson-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .lesson-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-left-color: var(--anwar-teal);
    }
    
    .border-success {
        border-left-color: #28a745 !important;
    }
    
    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }
</style>
@endpush
@endsection
