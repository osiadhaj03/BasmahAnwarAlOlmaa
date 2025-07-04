<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixDatabaseStructure extends Command
{
    protected $signature = 'basmah:fix-database';
    protected $description = 'Fix database structure by adding missing columns';

    public function handle()
    {
        $this->info('Starting database structure fix...');

        try {
            // Check and add missing columns to lessons table
            if (!Schema::hasColumn('lessons', 'name')) {
                DB::statement('ALTER TABLE lessons ADD COLUMN name VARCHAR(255) AFTER id');
                $this->info('Added name column to lessons table');
            }

            if (!Schema::hasColumn('lessons', 'description')) {
                DB::statement('ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER end_time');
                $this->info('Added description column to lessons table');
            }

            if (!Schema::hasColumn('lessons', 'schedule_time')) {
                DB::statement('ALTER TABLE lessons ADD COLUMN schedule_time TIME NULL AFTER description');
                $this->info('Added schedule_time column to lessons table');
            }

            // Check and add missing columns to attendances table
            if (!Schema::hasColumn('attendances', 'notes')) {
                DB::statement('ALTER TABLE attendances ADD COLUMN notes TEXT NULL AFTER status');
                $this->info('Added notes column to attendances table');
            }

            // Update lessons with default names
            DB::table('lessons')
                ->whereNull('name')
                ->orWhere('name', '')
                ->update(['name' => DB::raw('CONCAT(subject, " - الدرس")')]);

            $this->info('Updated lesson names');

            // Update schedule_time to match start_time where null
            DB::table('lessons')
                ->whereNull('schedule_time')
                ->update(['schedule_time' => DB::raw('start_time')]);

            $this->info('Updated schedule times');

            $this->info('Database structure fix completed successfully!');

            return 0;

        } catch (\Exception $e) {
            $this->error('Error fixing database: ' . $e->getMessage());
            return 1;
        }
    }
}
