<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=basmah', 'root', '');
    
    $count = $pdo->query('SELECT COUNT(*) FROM lessons')->fetchColumn();
    echo "Number of lessons: $count\n";
    
    if ($count > 0) {
        echo "\nFirst 3 lessons:\n";
        $result = $pdo->query('SELECT id, subject, start_time, qr_code FROM lessons LIMIT 3');
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $hasQR = !empty($row['qr_code']) ? 'Yes' : 'No';
            echo "- Lesson {$row['id']}: {$row['subject']} at {$row['start_time']} - QR: $hasQR\n";
        }
    } else {
        echo "\nNo lessons found. Creating sample lessons...\n";
        
        // Create sample lessons
        $stmt = $pdo->prepare("
            INSERT INTO lessons (subject, grade, start_time, end_time, teacher_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $lessons = [
            ['Math', 'Grade 1', '2025-01-10 08:00:00', '2025-01-10 09:00:00', 1],
            ['Science', 'Grade 2', '2025-01-10 09:00:00', '2025-01-10 10:00:00', 1],
            ['English', 'Grade 3', '2025-01-10 10:00:00', '2025-01-10 11:00:00', 1]
        ];
        
        foreach ($lessons as $lesson) {
            $stmt->execute($lesson);
        }
        
        echo "Created 3 sample lessons\n";
    }
    
    // Check users
    $userCount = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    echo "\nNumber of users: $userCount\n";
    
    if ($userCount === 0) {
        echo "Creating admin user...\n";
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute(['Admin', 'admin@basmah.app', password_hash('admin123', PASSWORD_DEFAULT), 'admin']);
        echo "Admin user created (email: admin@basmah.app, password: admin123)\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
