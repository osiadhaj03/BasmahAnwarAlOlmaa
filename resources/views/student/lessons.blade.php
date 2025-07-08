@extends('layouts.app')

@section('title', 'دروسي')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">دروسي</h1>
        <p class="text-gray-600">اختر درساً لتسجيل الحضور</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse(auth()->user()->lessons as $lesson)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">{{ $lesson->subject }}</h3>
                    <p class="text-blue-100 text-sm">{{ $lesson->name }}</p>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-user-tie w-4 mr-2"></i>
                            <span>{{ $lesson->teacher->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day w-4 mr-2"></i>
                            <span>{{ __('days.' . $lesson->day_of_week) }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock w-4 mr-2"></i>
                            <span>{{ $lesson->start_time }} - {{ $lesson->end_time }}</span>
                        </div>
                    </div>

                    <!-- Attendance Status -->
                    @php
                        $hasAttendedToday = $lesson->attendances()
                            ->where('student_id', auth()->id())
                            ->whereDate('date', today())
                            ->exists();
                    @endphp

                    @if($hasAttendedToday)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                            <div class="flex items-center text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span class="text-sm font-medium">تم تسجيل حضورك اليوم</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                            <div class="flex items-center text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="text-sm font-medium">لم تسجل حضورك اليوم</span>
                            </div>
                        </div>
                    @endif

                    <!-- Action Button -->
                    <a href="{{ route('lessons.attendance.submit', $lesson) }}" 
                       class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 text-center block">
                        <i class="fas fa-hand-paper mr-2"></i>
                        تسجيل الحضور
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-graduation-cap text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-500 mb-2">لا توجد دروس مسجلة</h3>
                <p class="text-gray-400">اتصل بالإدارة لتسجيلك في الدروس</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
