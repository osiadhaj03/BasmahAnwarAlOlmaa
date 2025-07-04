<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.dashboard';
    
    protected static ?string $navigationLabel = 'الرئيسية';
    
    protected static ?string $title = 'لوحة التحكم';
    
    public function getTitle(): string | Htmlable
    {
        return 'لوحة التحكم - BasmahApp';
    }
    
    public function getWidgets(): array
    {
        return [
            // Add your widgets here
        ];
    }
    
    public function getViewData(): array
    {
        $user = auth()->user();
        $data = [];
        
        if ($user->role === 'admin') {
            $data = [
                'totalUsers' => User::count(),
                'totalTeachers' => User::where('role', 'teacher')->count(),
                'totalStudents' => User::where('role', 'student')->count(),
                'totalLessons' => Lesson::count(),
                'totalAttendances' => Attendance::count(),
                'todayAttendances' => Attendance::whereDate('date', today())->count(),
            ];
        } elseif ($user->role === 'teacher') {
            $teacherLessons = Lesson::where('teacher_id', $user->id)->pluck('id');
            $data = [
                'myLessons' => $teacherLessons->count(),
                'myStudents' => Lesson::where('teacher_id', $user->id)
                    ->withCount('students')
                    ->get()
                    ->sum('students_count'),
                'todayAttendances' => Attendance::whereIn('lesson_id', $teacherLessons)
                    ->whereDate('date', today())
                    ->count(),
                'totalAttendances' => Attendance::whereIn('lesson_id', $teacherLessons)->count(),
            ];
        }
        
        return $data;
    }
}
