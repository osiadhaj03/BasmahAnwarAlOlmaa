<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Attendance;
use App\Models\QrToken;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class QrCodeController extends Controller
{    /**
     * Generate QR code image for a lesson token
     */
    public function generateQR(Request $request, $lessonId)
    {
        try {
            $lesson = Lesson::findOrFail($lessonId);
            
            // التأكد من أن المستخدم معلم أو مدير ولديه صلاحية
            $user = auth()->user();
            if (!$user || (!$user->isAdmin() && $lesson->teacher_id !== $user->id)) {
                abort(403, 'غير مسموح لك بالوصول لهذا الدرس');
            }            // التحقق من إمكانية توليد QR في الوقت الحالي
            if (!$lesson->canGenerateQR()) {
                $timeUntil = $lesson->getTimeUntilQRGeneration();
                $message = 'لا يمكن توليد QR Code إلا خلال وقت الدرس';
                
                if ($timeUntil) {
                    $message .= ' - الدرس سيبدأ في ' . $timeUntil->format('Y-m-d H:i');
                } else {
                    $message .= ' (' . $this->getDayInArabic($lesson->day_of_week) . ' ';
                    $message .= 'من ' . $lesson->start_time->format('H:i') . ' إلى ' . $lesson->end_time->format('H:i') . ')';
                }
                
                return response()->json(['error' => $message], 403);
            }

            // الحصول على token صالح أو إنشاء جديد
            $qrToken = $lesson->getValidQRToken();
            if (!$qrToken) {
                $qrToken = $lesson->generateQRCodeToken();
            }

            // إنشاء QR Code يحتوي على رابط لمسح Token
            $scanUrl = url("/attendance/scan?token=" . urlencode($qrToken->token));
            
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($scanUrl);

            return response($qrCode)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            \Log::error('QR Generation Error: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ في توليد QR Code'], 500);
        }
    }

    /**
     * Display QR code page for classroom projection
     */
    public function displayQR(Lesson $lesson)
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && $lesson->teacher_id !== $user->id)) {
            abort(403, 'غير مسموح لك بالوصول لهذا الدرس');
        }

        // تحديد layout بناءً على دور المستخدم
        $layout = $user->isAdmin() ? 'layouts.admin' : 'layouts.admin';
        
        return view('admin.lessons.qr-display', compact('lesson', 'layout'));
    }

    /**
     * Process attendance scan using token
     */
    public function scanAttendance(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = auth()->user();
        if (!$user || !$user->isStudent()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب أن تكون طالب لتسجيل الحضور'
            ], 403);
        }

        // البحث عن Token في قاعدة البيانات
        $qrToken = QrToken::where('token', $request->token)->first();
        
        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'message' => 'رمز QR غير صحيح'
            ], 400);
        }        // التحقق من صلاحية Token
        if (!$qrToken->isValid()) {
            return response()->json([
                'success' => false,
                'message' => $qrToken->isExpired() ? 'انتهت صلاحية رمز QR (انتهى وقت الدرس)' : 'رمز QR غير صالح'
            ], 400);
        }

        $lesson = $qrToken->lesson;

        // التحقق من أن الدرس ما زال في وقته المحدد
        if (!$lesson->canGenerateQR()) {
            return response()->json([
                'success' => false,
                'message' => 'انتهى وقت الدرس - لا يمكن تسجيل الحضور بعد انتهاء الوقت المحدد'
            ], 400);
        }

        // التحقق من أن الطالب مسجل في هذا الدرس
        if (!$lesson->students()->where('student_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'أنت غير مسجل في هذا الدرس'
            ], 403);
        }

        // التحقق من عدم وجود حضور مسبق لنفس اليوم
        $today = now()->format('Y-m-d');
        $existingAttendance = Attendance::where('student_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'تم تسجيل حضورك مسبقاً في هذا الدرس اليوم'
            ], 400);
        }

        // تحديد حالة الحضور حسب وقت المسح
        $attendanceStatus = $lesson->getAttendanceStatus();
        
        // تسجيل الحضور
        $attendance = Attendance::create([
            'student_id' => $user->id,
            'lesson_id' => $lesson->id,
            'date' => $today,
            'status' => $attendanceStatus,
            'notes' => 'تم التسجيل عبر QR Code في ' . now()->format('H:i:s') . ' - حالة: ' . ($attendanceStatus === 'present' ? 'حاضر' : 'متأخر')
        ]);

        // لا نحتاج لتحديد Token كمستخدم لأنه يمكن استخدامه عدة مرات
        // $qrToken->markAsUsed();

        $statusText = $attendanceStatus === 'present' ? 'حاضر' : 'متأخر';
        
        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل حضورك بنجاح في درس ' . $lesson->name . ' (حالة: ' . $statusText . ')',
            'attendance_id' => $attendance->id,
            'lesson_name' => $lesson->name,
            'subject' => $lesson->subject,
            'time' => now()->format('H:i'),
            'status' => $attendanceStatus,
            'status_text' => $statusText
        ]);
    }

    /**
     * Get QR scanner page for students
     */
    public function scanner()
    {
        $user = auth()->user();
        if (!$user || !$user->isStudent()) {
            abort(403, 'هذه الصفحة مخصصة للطلاب فقط');
        }

        return view('student.qr-scanner');
    }    /**
     * Get QR token info for API (for real-time updates)
     */
    public function getTokenInfo(Lesson $lesson)
    {
        try {
            $user = auth()->user();
            if (!$user || (!$user->isAdmin() && $lesson->teacher_id !== $user->id)) {
                abort(403);
            }

            $validToken = $lesson->getValidQRToken();
            
            // التحقق من أن الدرس ما زال في وقته (بدون استثناءات)
            $canGenerate = $lesson->canGenerateQR();
            
            // إذا انتهى وقت الدرس، أزل أي tokens صالحة فوراً
            if (!$canGenerate && $validToken) {
                $validToken->update(['expires_at' => now()->subMinute()]);
                $validToken = null;
            }
            
            $timeUntil = $lesson->getTimeUntilQRGeneration();
            
            $response = [
                'lesson_id' => $lesson->id,
                'lesson_name' => $lesson->name,
                'subject' => $lesson->subject,
                'has_valid_token' => !is_null($validToken),
                'can_generate_qr' => $canGenerate,
                'token_expires_at' => $validToken ? $validToken->expires_at->format('Y-m-d H:i:s') : null,
                'token_remaining_minutes' => $validToken ? max(0, (int)$validToken->expires_at->diffInMinutes(now())) : 0,
                'lesson_remaining_minutes' => $lesson->getRemainingLessonTime(),
                'students_count' => $lesson->students()->count(),
                'qr_url' => $canGenerate ? route('quick.qr', $lesson->id) : null
            ];

            if (!$canGenerate && env('APP_ENV') !== 'local') {
                if ($timeUntil) {
                    $response['qr_availability_message'] = 'QR Code سيكون متاحاً مع بداية الدرس في ' . $timeUntil->format('Y-m-d H:i');
                    $response['minutes_until_available'] = now()->diffInMinutes($timeUntil);
                } else {
                    $response['qr_availability_message'] = 'QR Code متاح فقط خلال وقت الدرس (' . $this->getDayInArabic($lesson->day_of_week) . ' من ' . $lesson->start_time->format('H:i') . ' إلى ' . $lesson->end_time->format('H:i') . ')';
                }
            } elseif (env('APP_ENV') === 'local') {
                $response['qr_availability_message'] = 'وضع التطوير: QR Code متاح في أي وقت';
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('QR Token Info Error: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ في جلب معلومات QR'], 500);
        }
    }    /**
     * Refresh QR token (generate new one)
     */
    public function refreshToken(Lesson $lesson)
    {
        try {
            $user = auth()->user();
            if (!$user || (!$user->isAdmin() && $lesson->teacher_id !== $user->id)) {
                abort(403);
            }
            
            // التحقق من إمكانية توليد QR في الوقت الحالي (لجميع البيئات)
            if (!$lesson->canGenerateQR()) {
                $timeUntil = $lesson->getTimeUntilQRGeneration();
                $message = 'لا يمكن توليد QR Code إلا خلال وقت الدرس';
                
                if ($timeUntil) {
                    $message .= ' - الدرس سيبدأ في ' . $timeUntil->format('Y-m-d H:i');
                } else {
                    $message .= ' (' . $this->getDayInArabic($lesson->day_of_week) . ' ';
                    $message .= 'من ' . $lesson->start_time->format('H:i') . ' إلى ' . $lesson->end_time->format('H:i') . ')';
                }
                
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 403);
            }

            $newToken = $lesson->generateQRCodeToken();
            
            // حساب المدة المتبقية حتى نهاية الدرس
            $remainingMinutes = $lesson->getRemainingLessonTime();
            
            return response()->json([
                'success' => true,
                'message' => 'تم توليد رمز QR جديد - صالح حتى نهاية الدرس',
                'token_expires_at' => $newToken->expires_at->format('Y-m-d H:i:s'),
                'token_remaining_minutes' => $remainingMinutes,
                'lesson_remaining_minutes' => $remainingMinutes
            ]);
            
        } catch (\Exception $e) {
            \Log::error('QR Refresh Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في توليد QR جديد: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick QR generation for testing (bypasses time restrictions)
     */
    public function quickGenerate(Lesson $lesson)
    {
        try {
            // التحقق من أن البيئة development
            if (!app()->environment('local')) {
                return response()->json(['error' => 'هذه الميزة متاحة فقط في بيئة التطوير'], 403);
            }
            
            // إجبار توليد QR Token
            $qrToken = $lesson->forceGenerateQRToken();
            
            // إنشاء QR Code
            $scanUrl = url("/attendance/scan?token=" . urlencode($qrToken->token));
            
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($scanUrl);

            return response($qrCode)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            \Log::error('Quick QR Generation Error: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ في توليد QR Code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper function to get day name in Arabic
     */
    private function getDayInArabic($dayOfWeek)
    {
        $days = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت'
        ];
        
        return $days[$dayOfWeek] ?? $dayOfWeek;
    }
}
