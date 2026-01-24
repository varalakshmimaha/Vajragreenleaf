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
$email = 'admin@gmail.com';
$password = '12345678';
$name = 'Admin User';
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "User with email {$email} already exists (id={$row['id']}).\n";
        exit(0);
    }
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $now = date('Y-m-d H:i:s');
    $insert = $pdo->prepare('INSERT INTO users (`name`,`email`,`password`,`role`,`is_active`,`email_verified_at`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,?,?)');
    $insert->execute([$name, $email, $hash, 'admin', 1, $now, $now, $now]);
    echo "Created admin user {$email}.\n";
} catch (Exception $e) {
    echo "DB error: " . $e->getMessage() . "\n";
    exit(1);
}
