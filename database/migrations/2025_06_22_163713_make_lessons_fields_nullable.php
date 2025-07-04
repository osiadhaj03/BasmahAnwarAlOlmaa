<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    /**
     * Run the migrations.
     */    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('day_of_week')->nullable()->change();
            $table->time('start_time')->nullable()->change();
            $table->time('end_time')->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('day_of_week')->nullable(false)->change();
            $table->time('start_time')->nullable(false)->change();
            $table->time('end_time')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
};
