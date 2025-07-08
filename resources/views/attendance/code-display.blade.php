@extends('layouts.app')

@section('title', 'جلسة تسجيل الحضور - ' . $lesson->subject)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                إدارة جلسة الحضور
            </h1>
            <div class="inline-flex items-center px-4 py-2 bg-white rounded-full shadow-sm">
                <i class="fas fa-chalkboard-teacher text-blue-500 mr-2"></i>
                <span class="text-gray-700">{{ auth()->user()->name }}</span>
            </div>
        </div>

        <!-- Lesson Info Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8 max-w-2xl mx-auto">
            <div class="text-center">
                <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $lesson->subject }}</h2>
                <p class="text-gray-600 mb-1">{{ $lesson->name }}</p>
                <div class="flex items-center justify-center space-x-4 text-sm text-gray-500">
                    <span>
                        <i class="fas fa-calendar-day mr-1"></i>
                        {{ __('days.' . $lesson->day_of_week) }}
                    </span>
                    <span>
                        <i class="fas fa-clock mr-1"></i>
                        {{ $lesson->start_time }} - {{ $lesson->end_time }}
                    </span>
                    <span>
                        <i class="fas fa-users mr-1"></i>
                        {{ $lesson->students->count() }} طالب
                    </span>
                </div>
            </div>
        </div>

        <!-- Attendance Code Component -->
        <div class="max-w-2xl mx-auto">
            @livewire('attendance-code-display', ['lesson' => $lesson])
        </div>

        <!-- Quick Actions -->
        <div class="text-center mt-8">
            <a href="{{ route('admin.lessons.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                العودة إلى قائمة الدروس
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom animations */
    @keyframes pulse-border {
        0%, 100% { border-color: #3B82F6; }
        50% { border-color: #1D4ED8; }
    }
    
    .pulse-border {
        animation: pulse-border 2s ease-in-out infinite;
    }
</style>
@endpush
@endsection
