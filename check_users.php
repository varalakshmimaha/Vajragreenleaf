<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::take(20)->get();
echo "ID | Email | Role | Sponsor ID" . PHP_EOL;
echo str_repeat("-", 50) . PHP_EOL;
foreach($users as $u) {
    echo "{$u->id} | {$u->email} | {$u->role} | " . ($u->sponsor_id ?? 'NULL') . PHP_EOL;
}
