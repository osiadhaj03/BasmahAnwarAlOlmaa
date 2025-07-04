<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Lesson table columns:\n";
$columns = Schema::getColumnListing('lessons');
foreach ($columns as $column) {
    echo "- " . $column . "\n";
}

echo "\nChecking if schedule_time exists: ";
echo Schema::hasColumn('lessons', 'schedule_time') ? 'YES' : 'NO';
echo "\n";
