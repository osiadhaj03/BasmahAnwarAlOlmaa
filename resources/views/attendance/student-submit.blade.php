@extends('layouts.app')

@section('title', 'تسجيل الحضور - ' . $lesson->subject)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-100 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                تسجيل الحضور
            </h1>
            <div class="inline-flex items-center px-4 py-2 bg-white rounded-full shadow-sm">
                <i class="fas fa-user-graduate text-green-500 mr-2"></i>
                <span class="text-gray-700">{{ auth()->user()->name }}</span>
            </div>
        </div>

        <!-- Lesson Info Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 max-w-md mx-auto">
            <div class="text-center">
                <div class="bg-blue-100 rounded-full p-3 w-12 h-12 mx-auto mb-4">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800 mb-1">{{ $lesson->subject }}</h2>
                <p class="text-gray-600 text-sm mb-2">{{ $lesson->name }}</p>
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-user-tie mr-1"></i>
                    {{ $lesson->teacher->name }}
                </p>
                <div class="flex items-center justify-center space-x-4 text-xs text-gray-400 mt-2">
                    <span>
                        <i class="fas fa-calendar-day mr-1"></i>
                        {{ __('days.' . $lesson->day_of_week) }}
                    </span>
                    <span>
                        <i class="fas fa-clock mr-1"></i>
                        {{ $lesson->start_time }} - {{ $lesson->end_time }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Student Attendance Submit Component -->
        <div class="max-w-md mx-auto">
            @livewire('student-attendance-submit', ['lesson' => $lesson])
        </div>

        <!-- Instructions -->
        <div class="max-w-md mx-auto mt-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-medium text-blue-800 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>
                    تعليمات مهمة
                </h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• تأكد من أنك في الفصل الدراسي</li>
                    <li>• اطلب الكود من معلمك</li>
                    <li>• أدخل الكود بسرعة قبل انتهاء صلاحيته</li>
                    <li>• يمكنك تسجيل الحضور مرة واحدة فقط في اليوم</li>
                </ul>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="{{ route('student.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                العودة إلى لوحة التحكم
            </a>
        </div>
    </div>
</div>
@endsection
