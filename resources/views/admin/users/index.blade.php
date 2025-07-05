@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section with Green Gradient -->
            <div style="background: linear-gradient(135deg, #0d9488 0%, #1e40af 100%); border-radius: 15px; box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);" class="p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2" style="color: white; font-weight: 700;">
                            <i class="fas fa-users me-2"></i>
                            إدارة المستخدمين
                        </h1>
                        <p class="mb-0" style="color: rgba(255, 255, 255, 0.8);">إدارة وتنظيم مستخدمي النظام</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn" style="background: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); padding: 12px 24px; border-radius: 10px; backdrop-filter: blur(10px);">
                        <i class="fas fa-plus me-2"></i>
                        إضافة مستخدم جديد
                    </a>
                </div>
            </div>
            
            <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1);")
                
                <div class="card-body">
                    <!-- رسائل النجاح والخطأ -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- فلاتر البحث -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="البحث في الاسم أو البريد الإلكتروني..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.users.index') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <select name="role" class="form-select" onchange="this.form.submit()">
                                    <option value="">جميع الأدوار</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>مدير</option>
                                    <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>معلم</option>
                                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>طالب</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- الروابط السريعة -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.index') }}" 
                                   class="btn {{ !request('role') ? 'btn-primary' : 'btn-outline-primary' }}">
                                    جميع المستخدمين ({{ App\Models\User::count() }})
                                </a>
                                <a href="{{ route('admin.users.teachers') }}" 
                                   class="btn btn-outline-success">
                                    المعلمين ({{ App\Models\User::where('role', 'teacher')->count() }})
                                </a>
                                <a href="{{ route('admin.users.students') }}" 
                                   class="btn btn-outline-info">
                                    الطلاب ({{ App\Models\User::where('role', 'student')->count() }})
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- جدول المستخدمين -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>الرقم</th>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الدور</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-danger">مدير</span>
                                        @elseif($user->role === 'teacher')
                                            <span class="badge bg-success">معلم</span>
                                        @elseif($user->role === 'student')
                                            <span class="badge bg-info">طالب</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('Y/m/d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>                                            @if($user->id !== auth()->id())
                                            <!-- نموذج حذف مضمن -->
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('هل أنت متأكد من حذف المستخدم {{ $user->name }}؟ هذا الإجراء لا يمكن التراجع عنه.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <p>لا توجد مستخدمين</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links() }}
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
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>            <div class="modal-body">
                <p>هل أنت متأكد من حذف المستخدم <strong id="userNameToDelete"></strong>؟</p>
                <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه.</p>
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
    background-color: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    text-transform: uppercase;
}
</style>
@endsection

@section('scripts')
<script>
function confirmDelete(userId, userName) {
    // تحديث اسم المستخدم في النموذج
    document.getElementById('userNameToDelete').textContent = userName;
    
    // تحديث action في النموذج
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = "{{ url('admin/users') }}/" + userId;
    
    // إضافة تسجيل للتصحيح
    console.log('Delete URL:', deleteForm.action);
    
    // إظهار النموذج
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// التحقق من إرسال النموذج
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    console.log('Form submitted with action:', this.action);
});
</script>
@endsection
