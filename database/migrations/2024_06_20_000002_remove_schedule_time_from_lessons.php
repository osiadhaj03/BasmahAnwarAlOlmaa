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
        Schema::table('lessons', function (Blueprint $table) {
            // حذف عمود schedule_time إذا كان موجود
            if (Schema::hasColumn('lessons', 'schedule_time')) {
                $table->dropColumn('schedule_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // إعادة إضافة العمود في حالة الرجوع
            $table->time('schedule_time')->nullable();
        });
    }
};
