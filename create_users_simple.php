<?php

use Illuminate\Support\Facades\DB;

try {
    // Simple database connection test
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=basmah', 
        'root', 
        '', 
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Database connection successful!\n";
    
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@basmahapp.com']);
    $existingAdmin = $stmt->fetch();
    
    if ($existingAdmin) {
        echo "✅ Admin user already exists: admin@basmahapp.com\n";
    } else {
        // Create admin user
        $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW(), NOW())
        ");
        
        $stmt->execute([
            'System Administrator',
            'admin@basmahapp.com', 
            $hashedPassword,
            'admin'
        ]);
        
        echo "✅ Admin user created successfully!\n";
    }
    
    // Check if teacher user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['teacher@basmahapp.com']);
    $existingTeacher = $stmt->fetch();
    
    if (!$existingTeacher) {
        $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW(), NOW())
        ");
        
        $stmt->execute([
            'Test Teacher',
            'teacher@basmahapp.com', 
            $hashedPassword,
            'teacher'
        ]);
        
        echo "✅ Teacher user created successfully!\n";
    }
    
    // Check if student user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['student@basmahapp.com']);
    $existingStudent = $stmt->fetch();
    
    if (!$existingStudent) {
        $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW(), NOW())
        ");
        
        $stmt->execute([
            'Test Student',
            'student@basmahapp.com', 
            $hashedPassword,
            'student'
        ]);
        
        echo "✅ Student user created successfully!\n";
    }
    
    echo "\n🔑 Login Credentials:\n";
    echo "   Admin: admin@basmahapp.com / password\n";
    echo "   Teacher: teacher@basmahapp.com / password\n";
    echo "   Student: student@basmahapp.com / password\n";
    echo "\n🌐 Access your application at: http://127.0.0.1:8000\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Please make sure:\n";
    echo "1. MySQL is running\n";
    echo "2. Database 'basmah' exists\n";
    echo "3. Database credentials in .env are correct\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
