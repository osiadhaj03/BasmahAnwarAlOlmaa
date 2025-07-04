<?php
// Simple database fix script
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'basmah';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully!\n";
    
    // Add missing columns
    $queries = [
        "ALTER TABLE lessons ADD COLUMN name VARCHAR(255) AFTER id",
        "ALTER TABLE lessons ADD COLUMN description TEXT NULL AFTER end_time", 
        "ALTER TABLE lessons ADD COLUMN schedule_time TIME NULL AFTER description",
        "ALTER TABLE attendances ADD COLUMN notes TEXT NULL AFTER status"
    ];
    
    foreach ($queries as $query) {
        try {
            $pdo->exec($query);
            echo "Executed: $query\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "Column already exists, skipping: $query\n";
            } else {
                echo "Error with query '$query': " . $e->getMessage() . "\n";
            }
        }
    }
    
    // Update lessons with default names
    $updateQuery = "UPDATE lessons SET name = CONCAT(subject, ' - الدرس') WHERE name IS NULL OR name = ''";
    $pdo->exec($updateQuery);
    echo "Updated lesson names\n";
    
    echo "Database fix completed!\n";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    echo "Please check your database configuration\n";
}
