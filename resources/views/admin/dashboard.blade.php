@extends('layouts.admin')

@section('title', 'الرئيسية')

@push('styles')
<style>
    .card-white-gradient {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .card-white-gradient:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .card-header-white {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        color: #495057;
    }
    
    .btn-white-style {
        background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 100%);
        border: 1px solid rgba(0,0,0,0.1);
        color: #495057;
        transition: all 0.3s ease;
    }
    
    .btn-white-style:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #343a40;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .btn-white-style i {
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">مرحباً بك في لوحة التحكم</h1>
    <div class="text-muted">
        {{ now()->format('Y/m/d - H:i') }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    @if(auth()->user()->role === 'admin')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">إجمالي المستخدمين</div>
                            <div class="h2 mb-0 text-dark">{{ $data['totalUsers'] ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid rgba(0,0,0,0.05);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">المعلمين</div>
                            <div class="h2 mb-0 text-dark">{{ $data['totalTeachers'] ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chalkboard-teacher fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card" style="background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 100%); border: 1px solid rgba(0,0,0,0.05);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">الطلاب</div>
                            <div class="h2 mb-0 text-dark">{{ $data['totalStudents'] ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-graduate fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card" style="background: linear-gradient(135deg, #f7f8f9 0%, #dee2e6 100%); border: 1px solid rgba(0,0,0,0.05);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">الدروس</div>
                            <div class="h2 mb-0 text-dark">{{ $data['totalLessons'] ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->role === 'teacher')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border: 1px solid rgba(0,0,0,0.05);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">دروسي</div>
                            <div class="h2 mb-0 text-dark">{{ $data['myLessons'] ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid rgba(0,0,0,0.05);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">طلابي</div>
                            <div class="h2 mb-0 text-dark">{{ $data['myStudents'] ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-graduate fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card" style="background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 100%); border: 1px solid rgba(0,0,0,0.05);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">حضور اليوم</div>
                            <div class="h2 mb-0 text-dark">{{ $data['todayAttendances'] ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-day fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card" style="background: linear-gradient(135deg, #f7f8f9 0%, #dee2e6 100%); border: 1px solid rgba(0,0,0,0.05);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">إجمالي الحضور</div>
                            <div class="h2 mb-0 text-dark">{{ $data['totalAttendances'] ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-check fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-8">
        <div class="card" style="background: linear-gradient(135deg, #0d9488 0%, #1e40af 100%); border-radius: 15px; box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);">
            <div class="card-header" style="background: rgba(255, 255, 255, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.2); border-radius: 15px 15px 0 0; backdrop-filter: blur(10px);">
                <h5 class="card-title mb-0" style="color: white; font-weight: 600;">
                    <i class="fas fa-tachometer-alt me-2" style="color: rgba(255, 255, 255, 0.9);"></i>
                    الإجراءات السريعة
                </h5>
            </div>
            <div class="card-body" style="padding: 2rem;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-lg w-100" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 12px; padding: 15px; backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'">
                            <i class="fas fa-users me-2" style="color: rgba(255, 255, 255, 0.9);"></i>
                            إدارة المستخدمين
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.lessons.index') }}" class="btn btn-lg w-100" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 12px; padding: 15px; backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'">
                            <i class="fas fa-book me-2" style="color: rgba(255, 255, 255, 0.9);"></i>
                            إدارة الدروس
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.attendances.index') }}" class="btn btn-lg w-100" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 12px; padding: 15px; backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'">
                            <i class="fas fa-clipboard-check me-2" style="color: rgba(255, 255, 255, 0.9);"></i>
                            إدارة الحضور
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-lg w-100" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 12px; padding: 15px; backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'">
                            <i class="fas fa-user-plus me-2" style="color: rgba(255, 255, 255, 0.9);"></i>
                            إضافة مستخدم
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.lessons.create') }}" class="btn btn-lg w-100" style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 12px; padding: 15px; backdrop-filter: blur(10px); transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'">
                            <i class="fas fa-plus me-2" style="color: rgba(255, 255, 255, 0.9);"></i>
                            إضافة درس جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card" style="background: linear-gradient(135deg, #0d9488 0%, #1e40af 100%); border-radius: 15px; box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);">
            <div class="card-header" style="background: rgba(255, 255, 255, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.2); border-radius: 15px 15px 0 0; backdrop-filter: blur(10px);">
                <h5 class="card-title mb-0" style="color: white; font-weight: 600;">
                    <i class="fas fa-info-circle me-2" style="color: rgba(255, 255, 255, 0.9);"></i>
                    معلومات النظام
                </h5>
            </div>
            <div class="card-body" style="padding: 2rem;">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-circle fa-2x" style="color: rgba(255, 255, 255, 0.9);"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0" style="color: white; font-weight: 600;">{{ auth()->user()->name }}</h6>
                        <small style="color: rgba(255, 255, 255, 0.8);">
                            {{ auth()->user()->role === 'admin' ? 'مدير النظام' : 'معلم' }}
                        </small>
                    </div>
                </div>
                
                <hr style="border-color: rgba(255, 255, 255, 0.3); margin: 1.5rem 0;">
                
                <div class="text-center">
                    <h6 style="color: white; font-weight: 600;">BasmahApp</h6>
                    <small style="color: rgba(255, 255, 255, 0.8);">نظام إدارة الحضور الذكي</small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
