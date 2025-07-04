<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Teacher's lessons relationship
     */
    public function teacherLessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    /**
     * Alias for teacherLessons for better naming
     */
    public function teachingLessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'teacher_id');
    }

    /**
     * Student's enrolled lessons relationship
     */
    public function studentLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_student', 'student_id', 'lesson_id')
                    ->withTimestamps();
    }

    /**
     * Student's attendances relationship
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    /**
     * Get lessons based on user role
     */
    public function lessons()
    {
        if ($this->role === 'student') {
            return $this->studentLessons();
        } elseif ($this->role === 'teacher') {
            return $this->teacherLessons();
        }
        
        // For admin or other roles, return empty collection
        return $this->studentLessons()->whereRaw('1 = 0');
    }
}
