@extends('layouts.admin')

@section('title', 'سجلات الحضور - ' . $lesson->subject)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">    <div>
        <h1 class="h3 mb-0">سجلات الحضور</h1>
        <p class="text-muted mb-0">{{ $lesson->subject }}</p>
    </div>    <div class="btn-group">
        <a href="{{ route('teacher.lessons.show', $lesson) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye"></i> عرض الدرس
        </a>
        <div class="alert alert-info mb-0" style="font-size: 0.85rem;">
            <i class="fas fa-info-circle me-1"></i>
            تسجيل الحضور يتم عبر الطلاب باستخدام QR Code
        </div>
    </div>
</div>

<!-- إحصائيات الحضور -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-white-75 small">إجمالي السجلات</div>
                        <div class="h3 mb-0">{{ $stats['total'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clipboard-list fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-white-75 small">حاضر</div>
                        <div class="h3 mb-0">{{ $stats['present'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-white-75 small">متأخر</div>
                        <div class="h3 mb-0">{{ $stats['late'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-white-75 small">غائب</div>
                        <div class="h3 mb-0">{{ $stats['absent'] }}</div>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- نسبة الحضور -->
<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title">معدل الحضور</h6>
        <div class="progress" style="height: 25px;">
            <div class="progress-bar bg-success" 
                 role="progressbar" 
                 style="width: {{ $stats['present_rate'] }}%"
                 aria-valuenow="{{ $stats['present_rate'] }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $stats['present_rate'] }}%
            </div>
        </div>
    </div>
</div>

<!-- جدول سجلات الحضور -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list"></i>
            سجلات الحضور ({{ $attendances->total() }})
        </h5>
    </div>
    <div class="card-body">
        @if($attendances->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الطالب</th>
                        <th>التاريخ</th>
                        <th>الوقت</th>
                        <th class="text-center">الحالة</th>
                        <th>ملاحظات</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $attendance->student->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $attendance->student->email }}</small>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y/m/d') }}</td>
                        <td>
                            @if($attendance->time)
                                {{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}
                            @else
                                <span class="text-muted">غير محدد</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($attendance->status === 'present')
                                <span class="badge bg-success">حاضر</span>
                            @elseif($attendance->status === 'late')
                                <span class="badge bg-warning">متأخر</span>
                            @else
                                <span class="badge bg-danger">غائب</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance->notes)
                                {{ Str::limit($attendance->notes, 50) }}
                            @else
                                <span class="text-muted">لا توجد ملاحظات</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" 
                                        class="btn btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewModal{{ $attendance->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $attendance->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $attendances->links() }}
        </div>
        @else        <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">لا توجد سجلات حضور</h5>
            <p class="text-muted">لم يتم تسجيل أي حضور لهذا الدرس بعد</p>
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle"></i>
                تسجيل الحضور يتم عبر الطلاب باستخدام QR Code
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modals للعرض والتعديل (يمكن إضافتها لاحقاً) -->
@foreach($attendances as $attendance)
<!-- View Modal -->
<div class="modal fade" id="viewModal{{ $attendance->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل الحضور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>الطالب:</strong></td>
                        <td>{{ $attendance->student->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>التاريخ:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y/m/d') }}</td>
                    </tr>
                    <tr>
                        <td><strong>الوقت:</strong></td>
                        <td>{{ $attendance->time ? \Carbon\Carbon::parse($attendance->time)->format('H:i') : 'غير محدد' }}</td>
                    </tr>
                    <tr>
                        <td><strong>الحالة:</strong></td>
                        <td>
                            @if($attendance->status === 'present')
                                <span class="badge bg-success">حاضر</span>
                            @elseif($attendance->status === 'late')
                                <span class="badge bg-warning">متأخر</span>
                            @else
                                <span class="badge bg-danger">غائب</span>
                            @endif
                        </td>
                    </tr>
                    @if($attendance->notes)
                    <tr>
                        <td><strong>ملاحظات:</strong></td>
                        <td>{{ $attendance->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Edit Modals -->
@foreach($attendances as $attendance)
<!-- تعديل حالة الحضور Modal -->
<div class="modal fade" id="editModal{{ $attendance->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل حالة الحضور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.attendances.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>الطالب:</strong></label>
                        <p class="form-control-plaintext">{{ $attendance->student->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>التاريخ:</strong></label>
                        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($attendance->date)->format('Y/m/d') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status{{ $attendance->id }}" class="form-label">حالة الحضور <span class="text-danger">*</span></label>
                        <select class="form-select" id="status{{ $attendance->id }}" name="status" required>
                            <option value="present" {{ $attendance->status === 'present' ? 'selected' : '' }}>حاضر</option>
                            <option value="late" {{ $attendance->status === 'late' ? 'selected' : '' }}>متأخر</option>
                            <option value="absent" {{ $attendance->status === 'absent' ? 'selected' : '' }}>غائب</option>
                            <option value="excused" {{ $attendance->status === 'excused' ? 'selected' : '' }}>غياب بعذر</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes{{ $attendance->id }}" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="notes{{ $attendance->id }}" name="notes" rows="3">{{ $attendance->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
