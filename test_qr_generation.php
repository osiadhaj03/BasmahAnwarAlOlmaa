<?php

require_once 'vendor/autoload.php';

use App\Models\Lesson;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing QR Code Generation...\n";
    
    // Get a lesson
    $lesson = Lesson::first();
    if (!$lesson) {
        echo "No lessons found\n";
        exit(1);
    }
    
    echo "Found lesson: {$lesson->subject} (ID: {$lesson->id})\n";
      // Test QR code generation directly with GD
    echo "Testing basic QR code generation with GD...\n";
    $testText = "Hello World";
    $qrCode = QrCode::format('png')->size(200)->backend('GD')->generate($testText);
    
    if (strlen($qrCode) > 0) {
        echo "✓ Basic QR code generation works (size: " . strlen($qrCode) . " bytes)\n";
    } else {
        echo "✗ Basic QR code generation failed\n";
        exit(1);
    }
    
    // Test with URL
    $scanUrl = "http://localhost/attendance/scan?token=test123";
    echo "Testing QR code with URL: $scanUrl\n";
      $qrCode = QrCode::format('png')
        ->size(300)
        ->backend('GD')
        ->errorCorrection('H')
        ->generate($scanUrl);
    
    if (strlen($qrCode) > 0) {
        echo "✓ URL QR code generation works (size: " . strlen($qrCode) . " bytes)\n";
    } else {
        echo "✗ URL QR code generation failed\n";
        exit(1);
    }
    
    // Test lesson methods
    echo "Testing lesson QR methods...\n";
    
    try {
        $canGenerate = $lesson->canGenerateQR();
        echo "Can generate QR: " . ($canGenerate ? 'Yes' : 'No') . "\n";
        
        if (method_exists($lesson, 'forceGenerateQRToken')) {
            echo "Testing forceGenerateQRToken...\n";
            $token = $lesson->forceGenerateQRToken();
            echo "✓ Token generated: " . substr($token->token, 0, 10) . "...\n";
            
            // Test actual QR generation with token
            $finalUrl = url("/attendance/scan?token=" . urlencode($token->token));
            $finalQR = QrCode::format('png')->size(300)->backend('GD')->generate($finalUrl);
            
            if (strlen($finalQR) > 0) {
                echo "✓ Complete QR code generation successful!\n";
                
                // Save QR to file for testing
                file_put_contents('test_qr.png', $finalQR);
                echo "✓ QR code saved to test_qr.png\n";
            } else {
                echo "✗ Complete QR code generation failed\n";
            }
        } else {
            echo "✗ forceGenerateQRToken method not found\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error testing lesson methods: " . $e->getMessage() . "\n";
    }
    
    echo "\nQR Code system test completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
