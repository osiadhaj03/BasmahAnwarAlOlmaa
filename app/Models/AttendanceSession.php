<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'code',
        'expires_at',
        'type',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Check if the session is still valid
     */
    public function isValid(): bool
    {
        return $this->is_active && $this->expires_at->isFuture();
    }

    /**
     * Generate a new 6-digit code
     */
    public static function generateCode(): string
    {
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->where('is_active', true)->exists());
        
        return $code;
    }

    /**
     * Create or refresh attendance session for a lesson
     */
    public static function createOrRefresh(Lesson $lesson, string $type = 'code', int $durationMinutes = 2): self
    {
        // Deactivate any existing active sessions for this lesson
        self::where('lesson_id', $lesson->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Create new session
        return self::create([
            'lesson_id' => $lesson->id,
            'code' => self::generateCode(),
            'expires_at' => now()->addMinutes($durationMinutes),
            'type' => $type,
            'is_active' => true,
        ]);
    }

    /**
     * Get active session for a lesson
     */
    public static function getActiveSession(Lesson $lesson): ?self
    {
        return self::where('lesson_id', $lesson->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();
    }
}
