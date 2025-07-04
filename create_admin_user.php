<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

try {
    // Check if admin user already exists
    $existingAdmin = User::where('email', 'admin@basmahapp.com')->first();
    
    if ($existingAdmin) {
        echo "✅ Admin user already exists: admin@basmahapp.com\n";
        echo "   Password: password\n";
    } else {
        // Create new admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@basmahapp.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Admin user created successfully!\n";
        echo "   Email: admin@basmahapp.com\n";
        echo "   Password: password\n";
        echo "   Role: admin\n";
    }
    
    // Also create a teacher user for testing
    $existingTeacher = User::where('email', 'teacher@basmahapp.com')->first();
    
    if (!$existingTeacher) {
        $teacher = User::create([
            'name' => 'Test Teacher',
            'email' => 'teacher@basmahapp.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Teacher user created successfully!\n";
        echo "   Email: teacher@basmahapp.com\n";
        echo "   Password: password\n";
        echo "   Role: teacher\n";
    }
    
    // Create a student user for testing
    $existingStudent = User::where('email', 'student@basmahapp.com')->first();
    
    if (!$existingStudent) {
        $student = User::create([
            'name' => 'Test Student',
            'email' => 'student@basmahapp.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Student user created successfully!\n";
        echo "   Email: student@basmahapp.com\n";
        echo "   Password: password\n";
        echo "   Role: student\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error creating users: " . $e->getMessage() . "\n";
}
