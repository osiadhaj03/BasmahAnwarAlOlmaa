<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=basmah', 'root', '');
    $result = $pdo->query('DESCRIBE lessons');
    $found = false;
    
    echo "Checking for grade column in lessons table:\n";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['Field'] === 'grade') {
            echo "Grade column exists: " . $row['Type'] . "\n";
            $found = true;
        }
    }
    
    if (!$found) {
        echo "Grade column does not exist\n";
        echo "Running migration...\n";
        
        $sql = "ALTER TABLE lessons ADD COLUMN grade VARCHAR(255) NULL AFTER subject";
        $pdo->exec($sql);
        echo "Grade column added successfully\n";
        
        // Mark migration as run
        $migration = '2025_06_22_164857_add_grade_to_lessons_table';
        $batch = $pdo->query("SELECT MAX(batch) + 1 as next_batch FROM migrations")->fetch()['next_batch'] ?? 1;
        $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
        echo "Migration marked as completed\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
