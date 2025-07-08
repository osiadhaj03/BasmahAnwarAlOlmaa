<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\AttendanceSession;
use App\Models\Attendance;

class StudentAttendanceSubmit extends Component
{
    public Lesson $lesson;
    public string $code = '';
    public string $message = '';
    public string $messageType = '';
    public bool $hasAttendedToday = false;

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->checkTodayAttendance();
    }

    public function submitCode()
    {
        $this->validate([
            'code' => 'required|string|size:6'
        ], [
            'code.required' => 'يرجى إدخال الكود',
            'code.size' => 'الكود يجب أن يكون 6 أرقام'
        ]);

        $user = auth()->user();
        
        // Check if student is enrolled in this lesson
        if (!$this->lesson->students()->where('student_id', $user->id)->exists()) {
            $this->showMessage('أنت غير مسجل في هذا الدرس', 'error');
            return;
        }

        // Check if already attended today
        if ($this->hasAttendedToday) {
            $this->showMessage('لقد قمت بتسجيل حضورك لهذا اليوم مسبقاً', 'warning');
            return;
        }

        // Find active session with matching code
        $session = AttendanceSession::where('lesson_id', $this->lesson->id)
            ->where('code', $this->code)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            $this->showMessage('الكود غير صحيح أو انتهت صلاحيته', 'error');
            return;
        }

        try {
            // Record attendance
            Attendance::create([
                'lesson_id' => $this->lesson->id,
                'student_id' => $user->id,
                'date' => now()->toDateString(),
                'status' => 'present',
                'notes' => 'تم التسجيل عبر الكود: ' . $this->code
            ]);

            $this->hasAttendedToday = true;
            $this->code = '';
            $this->showMessage('تم تسجيل حضورك بنجاح! ✅', 'success');
            
        } catch (\Exception $e) {
            $this->showMessage('حدث خطأ في تسجيل الحضور', 'error');
        }
    }

    private function checkTodayAttendance()
    {
        $user = auth()->user();
        $today = now()->toDateString();
        
        $this->hasAttendedToday = Attendance::where('lesson_id', $this->lesson->id)
            ->where('student_id', $user->id)
            ->whereDate('date', $today)
            ->exists();
    }

    private function showMessage(string $message, string $type)
    {
        $this->message = $message;
        $this->messageType = $type;
        
        // Clear message after 5 seconds
        $this->dispatch('clear-message');
    }

    public function clearMessage()
    {
        $this->message = '';
        $this->messageType = '';
    }

    public function render()
    {
        return view('livewire.student-attendance-submit');
    }
}
