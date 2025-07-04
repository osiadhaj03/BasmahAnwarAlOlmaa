@extends('layouts.admin')

@section('title', 'المعلمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        المعلمين
                    </h4>
                    <div>
                        <a href="{{ route('admin.users.create') }}?role=teacher" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>
                            إضافة معلم جديد
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-users me-2"></i>
                            جميع المستخدمين
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- فلتر البحث -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.users.teachers') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="البحث في اسم المعلم أو البريد الإلكتروني..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <span class="badge bg-success fs-6">إجمالي المعلمين: {{ $teachers->total() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- جدول المعلمين -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>الرقم</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>عدد الدروس</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-success me-2">
                                                {{ substr($teacher->name, 0, 2) }}
                                            </div>
                                            {{ $teacher->name }}
                                        </div>
                                    </td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $teacher->teaching_lessons_count }} درس</span>
                                    </td>
                                    <td>{{ $teacher->created_at->format('Y/m/d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $teacher) }}" 
                                               class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $teacher) }}" 
                                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($teacher->id !== auth()->id())
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    title="حذف" onclick="confirmDelete({{ $teacher->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-chalkboard-teacher fa-2x mb-3"></i>
                                        <p>لا يوجد معلمين</p>
                                        <a href="{{ route('admin.users.create') }}?role=teacher" class="btn btn-primary">
                                            إضافة معلم جديد
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $teachers->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد حذف المعلم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد من حذف هذا المعلم؟ سيتم حذف جميع البيانات المرتبطة به.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    text-transform: uppercase;
    color: white;
}
</style>
@endsection

@section('scripts')
<script>
function confirmDelete(teacherId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `{{ route('admin.users.index') }}/${teacherId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
