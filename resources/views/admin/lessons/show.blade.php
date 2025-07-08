@extends('layouts.admin')

@section('title', 'عرض الدرس')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="anwar-header islamic-pattern-enhanced">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="anwar-title">
                        <i class="fas fa-eye anwar-text-gold me-2"></i>
                        تفاصيل الدرس
                    </h1>
                    <div>
                        <a href="{{ route('admin.lessons.edit', $lesson) }}" class="btn anwar-btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>تعديل
                        </a>
                        <a href="{{ route('admin.lessons.index') }}" class="btn anwar-btn-secondary">
                            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Lesson Details -->
                <div class="col-lg-8">
                    <div class="card anwar-card islamic-pattern-subtle shadow mb-4">
                        <div class="card-header anwar-card-header">
                            <h5 class="card-title mb-0 anwar-text-gold">
                                <i class="fas fa-book me-2"></i>معلومات الدرس
                            </h5>
                        </div>
                        <div class="card-body anwar-card-body">
                            <table class="table table-borderless anwar-table">
                                <tr>
                                    <th class="anwar-text-gold" style="width: 200px;">اسم الدرس:</th>
                                    <td class="anwar-text-dark">{{ $lesson->name }}</td>
                                </tr>
                                <tr>
                                    <th class="anwar-text-gold">المادة:</th>
                                    <td class="anwar-text-dark">{{ $lesson->subject ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th class="anwar-text-gold">المعلم:</th>
                                    <td>
                                        <span class="badge anwar-badge-blue">{{ $lesson->teacher->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="anwar-text-gold">وقت الدرس:</th>
                                    <td class="anwar-text-dark">{{ $lesson->schedule_time ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ $lesson->created_at->format('Y/m/d - H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ $lesson->updated_at->format('Y/m/d - H:i') }}</td>
                                </tr>
                                @if($lesson->description)
                                <tr>
                                    <th>الوصف:</th>
                                    <td>{{ $lesson->description }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h3 class="text-primary">{{ $lesson->students->count() }}</h3>
                                <small class="text-muted">عدد الطلاب المسجلين</small>
                            </div>
                            
                            <div class="text-center mb-3">
                                <h3 class="text-success">{{ $lesson->attendances->count() }}</h3>
                                <small class="text-muted">إجمالي سجلات الحضور</small>
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-info">{{ $lesson->attendances()->whereDate('date', today())->count() }}</h3>
                                <small class="text-muted">حضور اليوم</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Students List -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>الطلاب المسجلين ({{ $lesson->students->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($lesson->students->count() > 0)
                        <div class="row">
                            @foreach($lesson->students as $student)
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                                            <h6 class="card-title">{{ $student->name }}</h6>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">لا يوجد طلاب مسجلين في هذا الدرس</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Attendances -->
            @if($lesson->attendances->count() > 0)
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>آخر سجلات الحضور
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>الطالب</th>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lesson->attendances()->latest()->take(10)->get() as $attendance)
                                    <tr>
                                        <td>{{ $attendance->student->name }}</td>
                                        <td>{{ $attendance->date }}</td>
                                        <td>
                                            @if($attendance->status === 'present')
                                                <span class="badge bg-success">حاضر</span>
                                            @elseif($attendance->status === 'absent')
                                                <span class="badge bg-danger">غائب</span>
                                            @else
                                                <span class="badge bg-warning">متأخر</span>
                                            @endif
                                        </td>
                                        <td>{{ $attendance->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($lesson->attendances->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.attendances.index', ['lesson_id' => $lesson->id]) }}" 
                               class="btn btn-outline-primary">
                                عرض جميع سجلات الحضور
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
