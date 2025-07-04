<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=basmah', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Tables in database:\n";
    $result = $pdo->query('SHOW TABLES');
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }
    
    echo "\nChecking qr_tokens table structure:\n";
    try {
        $result = $pdo->query('DESCRIBE qr_tokens');
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "  {$row['Field']}: {$row['Type']}\n";
        }
    } catch (Exception $e) {
        echo "qr_tokens table does not exist\n";
    }
    
    echo "\nChecking lessons table for QR columns:\n";
    $result = $pdo->query('DESCRIBE lessons');
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if (strpos($row['Field'], 'qr') !== false) {
            echo "  {$row['Field']}: {$row['Type']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
