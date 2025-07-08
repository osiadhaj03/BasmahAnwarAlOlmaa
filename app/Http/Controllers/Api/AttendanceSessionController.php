<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttendanceSessionController extends Controller
{
    /**
     * Start or refresh attendance session for a lesson
     */
    public function startSession(Request $request, Lesson $lesson): JsonResponse
    {
        // Check if user can manage this lesson
        $user = auth()->user();
        if (!$user || ($user->role !== 'admin' && $lesson->teacher_id !== $user->id)) {
            return response()->json(['error' => 'غير مسموح لك بإدارة هذا الدرس'], 403);
        }

        $type = $request->input('type', 'code'); // 'code' or 'qr'
        $duration = $request->input('duration', 2); // Duration in minutes

        try {
            $session = AttendanceSession::createOrRefresh($lesson, $type, $duration);
            
            return response()->json([
                'success' => true,
                'session' => [
                    'id' => $session->id,
                    'code' => $session->code,
                    'type' => $session->type,
                    'expires_at' => $session->expires_at->toISOString(),
                    'expires_in_seconds' => $session->expires_at->diffInSeconds(now()),
                ],
                'message' => 'تم بدء جلسة الحضور بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ في إنشاء الجلسة'], 500);
        }
    }

    /**
     * Get current active session for a lesson
     */
    public function getCurrentSession(Lesson $lesson): JsonResponse
    {
        $user = auth()->user();
        if (!$user || ($user->role !== 'admin' && $lesson->teacher_id !== $user->id)) {
            return response()->json(['error' => 'غير مسموح لك بعرض هذا الدرس'], 403);
        }

        $session = AttendanceSession::getActiveSession($lesson);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد جلسة حضور نشطة'
            ]);
        }

        return response()->json([
            'success' => true,
            'session' => [
                'id' => $session->id,
                'code' => $session->code,
                'type' => $session->type,
                'expires_at' => $session->expires_at->toISOString(),
                'expires_in_seconds' => max(0, $session->expires_at->diffInSeconds(now())),
            ]
        ]);
    }

    /**
     * Submit attendance code by student
     */
    public function submitCode(Request $request, Lesson $lesson): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = auth()->user();
        if (!$user || $user->role !== 'student') {
            return response()->json(['error' => 'يجب أن تكون طالباً لتسجيل الحضور'], 403);
        }

        // Check if student is enrolled in this lesson
        if (!$lesson->students()->where('student_id', $user->id)->exists()) {
            return response()->json(['error' => 'أنت غير مسجل في هذا الدرس'], 403);
        }

        // Check if student already attended today
        $today = now()->toDateString();
        $existingAttendance = Attendance::where('lesson_id', $lesson->id)
            ->where('student_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتسجيل حضورك لهذا اليوم مسبقاً'
            ]);
        }

        // Find active session with matching code
        $session = AttendanceSession::where('lesson_id', $lesson->id)
            ->where('code', $request->code)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'الكود غير صحيح أو انتهت صلاحيته'
            ]);
        }

        try {
            // Record attendance
            Attendance::create([
                'lesson_id' => $lesson->id,
                'student_id' => $user->id,
                'date' => $today,
                'status' => 'present',
                'notes' => 'تم التسجيل عبر الكود: ' . $request->code
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل حضورك بنجاح! ✅'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ في تسجيل الحضور'], 500);
        }
    }

    /**
     * Stop active session
     */
    public function stopSession(Lesson $lesson): JsonResponse
    {
        $user = auth()->user();
        if (!$user || ($user->role !== 'admin' && $lesson->teacher_id !== $user->id)) {
            return response()->json(['error' => 'غير مسموح لك بإدارة هذا الدرس'], 403);
        }

        AttendanceSession::where('lesson_id', $lesson->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'تم إيقاف جلسة الحضور'
        ]);
    }
}
