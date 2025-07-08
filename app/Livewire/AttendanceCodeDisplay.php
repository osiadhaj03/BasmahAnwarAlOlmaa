<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\AttendanceSession;

class AttendanceCodeDisplay extends Component
{
    public Lesson $lesson;
    public ?AttendanceSession $session = null;
    public bool $isActive = false;
    public int $remainingTime = 0;
    public string $currentCode = '';
    public string $sessionType = 'code';
    public int $duration = 2; // Duration in minutes

    protected $listeners = ['refreshSession' => 'refreshCurrentSession'];

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->refreshCurrentSession();
    }

    public function startSession()
    {
        try {
            $this->session = AttendanceSession::createOrRefresh(
                $this->lesson, 
                $this->sessionType, 
                $this->duration
            );
            
            $this->isActive = true;
            $this->currentCode = $this->session->code;
            $this->calculateRemainingTime();
            
            $this->dispatch('session-started', [
                'message' => 'تم بدء جلسة الحضور بنجاح'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('session-error', [
                'message' => 'حدث خطأ في بدء الجلسة'
            ]);
        }
    }

    public function stopSession()
    {
        AttendanceSession::where('lesson_id', $this->lesson->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->isActive = false;
        $this->session = null;
        $this->currentCode = '';
        $this->remainingTime = 0;

        $this->dispatch('session-stopped', [
            'message' => 'تم إيقاف جلسة الحضور'
        ]);
    }

    public function refreshCurrentSession()
    {
        $this->session = AttendanceSession::getActiveSession($this->lesson);
        
        if ($this->session && $this->session->isValid()) {
            $this->isActive = true;
            $this->currentCode = $this->session->code;
            $this->sessionType = $this->session->type;
            $this->calculateRemainingTime();
        } else {
            $this->isActive = false;
            $this->session = null;
            $this->currentCode = '';
            $this->remainingTime = 0;
        }
    }

    public function refreshCode()
    {
        if ($this->isActive && $this->session) {
            try {
                $this->session = AttendanceSession::createOrRefresh(
                    $this->lesson, 
                    $this->sessionType, 
                    $this->duration
                );
                
                $this->currentCode = $this->session->code;
                $this->calculateRemainingTime();
                
                $this->dispatch('code-refreshed', [
                    'message' => 'تم تجديد الكود'
                ]);
            } catch (\Exception $e) {
                $this->dispatch('session-error', [
                    'message' => 'حدث خطأ في تجديد الكود'
                ]);
            }
        }
    }

    private function calculateRemainingTime()
    {
        if ($this->session) {
            $this->remainingTime = max(0, $this->session->expires_at->diffInSeconds(now()));
        }
    }

    public function render()
    {
        return view('livewire.attendance-code-display');
    }
}
