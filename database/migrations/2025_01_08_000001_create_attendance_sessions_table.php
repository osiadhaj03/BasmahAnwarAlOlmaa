<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->string('code', 6)->index(); // 6-digit random code
            $table->timestamp('expires_at');
            $table->enum('type', ['qr', 'code'])->default('code'); // Type of session
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure one active session per lesson
            $table->unique(['lesson_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
