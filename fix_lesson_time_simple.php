<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lesson;

$lesson = Lesson::first();
$lesson->start_time = '19:30:00';
$lesson->end_time = '20:30:00';
$lesson->save();

echo "✅ تم تحديث الدرس:\n";
echo "   📝 الاسم: " . $lesson->name . "\n";
echo "   ⏰ الوقت الجديد: " . $lesson->start_time->format('H:i') . " - " . $lesson->end_time->format('H:i') . "\n";
