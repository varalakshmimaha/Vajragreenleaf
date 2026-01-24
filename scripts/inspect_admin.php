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
    $sql = "SELECT u.id,u.email,u.name,u.username,u.role,u.sponsor_id,u.is_active, GROUP_CONCAT(r.slug) as role_slugs
        FROM users u
        LEFT JOIN user_roles ur ON ur.user_id = u.id
        LEFT JOIN roles r ON r.id = ur.role_id
        GROUP BY u.id
        ORDER BY u.id DESC
        LIMIT 100";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        echo "No users found\n";
        exit(0);
    }
    foreach ($rows as $r) {
        echo "ID: {$r['id']} | Email: {$r['email']} | Name: {$r['name']} | role(col): {$r['role']} | sponsor_id: {$r['sponsor_id']} | is_active: {$r['is_active']} | role_slugs: {$r['role_slugs']}\n";
    }
} catch (Exception $e) {
    echo "DB error: " . $e->getMessage() . "\n";
    exit(1);
}
