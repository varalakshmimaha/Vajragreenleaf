<?php
$env = @file_get_contents(__DIR__ . '/../.env');
if (!$env) {
    echo "Could not read .env\n";
    exit(1);
}
$lines = explode("\n", $env);
$vars = [];
foreach ($lines as $line) {
    if (trim($line) === '' || str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
    [$k,$v] = explode('=', $line, 2);
    $vars[trim($k)] = trim($v);
}
$host = $vars['DB_HOST'] ?? '127.0.0.1';
$port = $vars['DB_PORT'] ?? '3306';
$db = $vars['DB_DATABASE'] ?? '';
$user = $vars['DB_USERNAME'] ?? '';
$pass = $vars['DB_PASSWORD'] ?? '';
$charset = 'utf8mb4';
$dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $email = 'raju@gmail.com';
    $stmt = $pdo->prepare('SELECT id,name,email,username,sponsor_id,role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) {
        echo "No user found for {$email}\n";
        exit(0);
    }
    echo json_encode($r, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "DB error: " . $e->getMessage() . "\n";
    exit(1);
}
