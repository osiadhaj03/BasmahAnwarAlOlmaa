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
            // إضافة الحقول المفقودة
            if (!Schema::hasColumn('lessons', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('lessons', 'description')) {
                $table->text('description')->nullable()->after('end_time');
            }            if (!Schema::hasColumn('lessons', 'status')) {
                $table->enum('status', ['active', 'completed', 'cancelled', 'scheduled'])
                      ->default('scheduled')->after('description');
            }
            if (!Schema::hasColumn('lessons', 'qr_code')) {
                $table->text('qr_code')->nullable()->after('status');
            }
            if (!Schema::hasColumn('lessons', 'qr_generated_at')) {
                $table->timestamp('qr_generated_at')->nullable()->after('qr_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'description', 
                'status', 
                'qr_code',
                'qr_generated_at'
            ]);
        });
    }
};
