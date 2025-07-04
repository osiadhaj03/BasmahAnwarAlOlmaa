<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

// Load Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Running migrations manually...\n";

try {
    // Check if columns exist first
    $columns = DB::select("SHOW COLUMNS FROM lessons");
    $columnNames = array_column($columns, 'Field');
    
    echo "Current columns in lessons table: " . implode(', ', $columnNames) . "\n";
    
    if (!in_array('name', $columnNames)) {
        DB::statement("ALTER TABLE lessons ADD COLUMN name VARCHAR(255) AFTER id");
        echo "Added 'name' column\n";
    }
    
    if (!in_array('description', $columnNames)) {
        DB::statement("ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER end_time");
        echo "Added 'description' column\n";
    }
    
    if (!in_array('schedule_time', $columnNames)) {
        DB::statement("ALTER TABLE lessons ADD COLUMN schedule_time TIME NULL AFTER description");
        echo "Added 'schedule_time' column\n";
    }
    
    // Check if notes column exists in attendances table
    $attendanceColumns = DB::select("SHOW COLUMNS FROM attendances");
    $attendanceColumnNames = array_column($attendanceColumns, 'Field');
    
    if (!in_array('notes', $attendanceColumnNames)) {
        DB::statement("ALTER TABLE attendances ADD COLUMN notes TEXT NULL AFTER status");
        echo "Added 'notes' column to attendances table\n";
    }
    
    echo "Migrations completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
