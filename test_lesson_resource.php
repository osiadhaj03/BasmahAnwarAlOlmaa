<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use App\Filament\Resources\LessonResource;

echo "Testing LessonResource...\n";

try {
    // Test if the resource exists
    $resource = LessonResource::class;
    echo "Resource class exists: " . $resource . "\n";
    
    // Test pages
    $pages = LessonResource::getPages();
    echo "Pages defined:\n";
    foreach ($pages as $name => $page) {
        echo "- {$name}: " . get_class($page) . "\n";
    }
    
    // Test routes
    echo "\nTesting route generation:\n";
    try {
        $indexUrl = LessonResource::getUrl('index');
        echo "Index URL: " . $indexUrl . "\n";
    } catch (Exception $e) {
        echo "Error getting index URL: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
