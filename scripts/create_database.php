<?php
// Creates the `laravel` database if it doesn't exist using root@127.0.0.1
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `laravel` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "OK: database laravel exists or was created\n";
} catch (Exception $e) {
    echo 'ERR: ' . $e->getMessage() . "\n";
    exit(1);
}
