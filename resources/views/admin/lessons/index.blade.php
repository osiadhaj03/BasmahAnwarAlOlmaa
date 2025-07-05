@extends('layouts.admin')

@section('title', 'إدارة الدروس')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
    
    body {
        font-family: 'Tajawal', sans-serif;
    }
    
    .islamic-pattern {
        background-color: #F8F8F8;
    }
    
    .status-scheduled {
        background-color: #3b82f6;
        color: white;
    }
    
    .status-active {
        background-color: #008080;
        color: white;
    }
    
    .status-completed {
        background-color: #4682B4;
        color: white;
    }
    
    .status-cancelled {
        background-color: #B22222;
        color: white;
    }
    
    .day-sunday {
        background-color: #3b82f6;
        color: white;
    }
    
    .day-monday {
        background-color: #008080;
        color: white;
    }
    
    .day-tuesday {
        background-color: #4682B4;
        color: white;
    }
    
    .day-wednesday {
        background-color: #DAA520;
        color: white;
    }
    
    .day-thursday {
        background-color: #B22222;
        color: white;
    }
    
    .day-friday {
        background-color: #4b5563;
        color: white;
    }
    
    .day-saturday {
        background-color: #6b7280;
        color: white;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    
    .pagination a, .pagination span {
        padding: 0.5rem 0.75rem;
        margin: 0 0.25rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        color: #4b5563;
    }
    
    .pagination a:hover {
        background-color: #f3f4f6;
    }
    
    .pagination .active {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .alert-success {
        background-color: #d1fae5;
        border-left: 4px solid #10b981;
        color: #065f46;
    }
    
    .alert-danger {
        background-color: #fee2e2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }
    
    .alert-info {
        background-color: #E0F2F1;
        border-left: 4px solid #008080;
        color: #008080;
    }
    
    .btn-primary {
        background-color: #DAA520;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #c6951c;
    }
    
    .btn-outline {
        border: 1px solid #d1d5db;
        color: #4b5563;
    }
    
    .btn-outline:hover {
        background-color: #f3f4f6;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filter-grid {
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="islamic-pattern min-h-screen p-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-teal-600 to-teal-800 rounded-lg shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-white mb-2">إدارة الدروس</h1>
                <p class="text-teal-100">إدارة وتنظيم دروس المؤسسة</p>
            </div>
            <div class="flex space-x-4 space-x-reverse">
                <a href="{{ route('admin.lessons.create') }}" class="btn-primary px-6 py-3 rounded-lg flex items-center space-x-2 space-x-reverse hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    <span>إضافة درس جديد</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 stats-grid">
        <!-- Total Lessons -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي الدروس</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalLessons ?? $lessons->total() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Scheduled Lessons -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">دروس مجدولة</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $scheduledLessons ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Lessons -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">دروس نشطة</p>
                    <p class="text-2xl font-bold text-teal-600">{{ $activeLessons ?? 0 }}</p>
                </div>
                <div class="p-3 bg-teal-100 rounded-full">
                    <i class="fas fa-play text-teal-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Completed Lessons -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">دروس مكتملة</p>
                    <p class="text-2xl font-bold text-green-600">{{ $completedLessons ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert-success p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle ml-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle ml-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">تصفية الدروس</h3>
        
        <form method="GET" action="{{ route('admin.lessons.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 filter-grid">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث في الدروس..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">حالة الدرس</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الحالات</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>مجدول</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغى</option>
                    </select>
                </div>

                <!-- Day Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اليوم</label>
                    <select name="day" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع الأيام</option>
                        <option value="sunday" {{ request('day') === 'sunday' ? 'selected' : '' }}>الأحد</option>
                        <option value="monday" {{ request('day') === 'monday' ? 'selected' : '' }}>الاثنين</option>
                        <option value="tuesday" {{ request('day') === 'tuesday' ? 'selected' : '' }}>الثلاثاء</option>
                        <option value="wednesday" {{ request('day') === 'wednesday' ? 'selected' : '' }}>الأربعاء</option>
                        <option value="thursday" {{ request('day') === 'thursday' ? 'selected' : '' }}>الخميس</option>
                        <option value="friday" {{ request('day') === 'friday' ? 'selected' : '' }}>الجمعة</option>
                        <option value="saturday" {{ request('day') === 'saturday' ? 'selected' : '' }}>السبت</option>
                    </select>
                </div>

                <!-- Teacher Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المدرس</label>
                    <select name="teacher_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">جميع المدرسين</option>
                        @if(isset($teachers))
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit" class="btn-primary px-6 py-2 rounded-lg flex items-center justify-center space-x-2 space-x-reverse">
                    <i class="fas fa-filter"></i>
                    <span>تطبيق التصفية</span>
                </button>
                
                <a href="{{ route('admin.lessons.index') }}" class="btn-outline px-6 py-2 rounded-lg flex items-center justify-center space-x-2 space-x-reverse">
                    <i class="fas fa-times"></i>
                    <span>إزالة التصفية</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Lessons Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">قائمة الدروس</h3>
        </div>
        
        <div class="table-responsive">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الدرس</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المدرس</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اليوم والوقت</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الطلاب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lessons as $lesson)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $lesson->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lesson->teacher)
                                    <div class="text-sm text-gray-900">{{ $lesson->teacher->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $lesson->teacher->email }}</div>
                                @else
                                    <span class="text-sm text-gray-500">غير محدد</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if($lesson->day_of_week)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full day-{{ $lesson->day_of_week }}">
                                            {{ 
                                                [
                                                    'sunday' => 'الأحد',
                                                    'monday' => 'الاثنين', 
                                                    'tuesday' => 'الثلاثاء',
                                                    'wednesday' => 'الأربعاء',
                                                    'thursday' => 'الخميس',
                                                    'friday' => 'الجمعة',
                                                    'saturday' => 'السبت'
                                                ][$lesson->day_of_week] ?? $lesson->day_of_week 
                                            }}
                                        </span>
                                    @endif
                                    @if($lesson->start_time && $lesson->end_time)
                                        <span class="text-xs text-gray-600">
                                            {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $lesson->attendances_count ?? 0 }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full status-{{ $lesson->status ?? 'scheduled' }}">
                                    {{ 
                                        [
                                            'scheduled' => 'مجدول',
                                            'active' => 'نشط',
                                            'completed' => 'مكتمل',
                                            'cancelled' => 'ملغى'
                                        ][$lesson->status ?? 'scheduled'] 
                                    }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2 space-x-reverse">
                                    <a href="{{ route('admin.lessons.show', $lesson) }}" 
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-all duration-200"
                                       title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50 transition-all duration-200"
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($lesson->qr_token)
                                        <a href="{{ route('lessons.qr', $lesson->qr_token) }}" 
                                           class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-all duration-200"
                                           title="رمز QR"
                                           target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @endif
                                    
                                    <form action="{{ route('admin.lessons.destroy', $lesson) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الدرس؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-all duration-200"
                                                title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <i class="fas fa-book-open text-gray-400 text-4xl"></i>
                                    <div class="text-gray-500">
                                        <p class="text-lg font-medium">لا توجد دروس</p>
                                        <p class="text-sm">قم بإضافة أول درس للمؤسسة</p>
                                    </div>
                                    <a href="{{ route('admin.lessons.create') }}" 
                                       class="btn-primary px-6 py-2 rounded-lg flex items-center space-x-2 space-x-reverse">
                                        <i class="fas fa-plus"></i>
                                        <span>إضافة درس جديد</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lessons->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        عرض {{ $lessons->firstItem() }} إلى {{ $lessons->lastItem() }} من أصل {{ $lessons->total() }} نتيجة
                    </div>
                    <div class="pagination">
                        {{ $lessons->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    const filterSelects = document.querySelectorAll('select[name="status"], select[name="day"], select[name="teacher_id"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Search with debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }

    // Confirm delete
    const deleteButtons = document.querySelectorAll('form[action*="destroy"] button[type="submit"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا الدرس؟ سيتم حذف جميع بيانات الحضور المرتبطة به.')) {
                e.preventDefault();
            }
        });
    });

    // Show success message animation
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        successAlert.style.opacity = '0';
        successAlert.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            successAlert.style.transition = 'all 0.5s ease';
            successAlert.style.opacity = '1';
            successAlert.style.transform = 'translateY(0)';
        }, 100);

        // Auto hide after 5 seconds
        setTimeout(() => {
            successAlert.style.transition = 'all 0.5s ease';
            successAlert.style.opacity = '0';
            successAlert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                successAlert.remove();
            }, 500);
        }, 5000);
    }
});
</script>
@endpush
