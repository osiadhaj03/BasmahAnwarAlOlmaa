<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=basmah', 'root', '');
$stmt = $pdo->prepare('INSERT IGNORE INTO migrations (migration, batch) VALUES (?, ?)');
$stmt->execute(['2025_06_22_164857_add_grade_to_lessons_table', 3]);
echo "Migration marked as completed\n";
