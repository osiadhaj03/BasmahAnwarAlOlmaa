<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrToken extends Model
{
    protected $fillable = [
        'lesson_id',
        'token',
        'generated_at',
        'expires_at',
        'used_at'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    public function isUsed(): bool
    {
        // QR Token يمكن استخدامه عدة مرات من طلاب مختلفين
        // لذا نعيد false دائماً
        return false;
    }

    public function isValid(): bool
    {
        // QR صالح إذا لم ينته وقته
        return !$this->isExpired();
    }

    public function markAsUsed(): void
    {
        // لا نحتاج لتحديد QR كمستخدم لأنه يمكن استخدامه عدة مرات
        // يمكن الاحتفاظ بهذه الدالة فارغة للتوافق مع الكود الموجود
    }
}
