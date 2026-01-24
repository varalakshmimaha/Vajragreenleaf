<?php
$env = @file_get_contents(__DIR__ . '/../.env');
if (!$env) {
    echo "Could not read .env\n";
    exit(1);
}
$lines = explode("\n", $env);
$vars = [];
foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
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
    $stmt = $pdo->query("SELECT id, name, email, role, is_active FROM users ORDER BY id");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        echo "No users found.\n";
        exit(0);
    }
    foreach ($rows as $r) {
        echo sprintf("%d\t%s\t%s\t%s\tactive=%s\n", $r['id'], $r['name'], $r['email'], $r['role'] ?? '', $r['is_active'] ? 'true' : 'false');
    }
} catch (Exception $e) {
    echo "DB error: " . $e->getMessage() . "\n";
    exit(1);
}
