<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$activities = App\Models\Activity::all();
echo "Total activities: " . $activities->count() . "\n";
foreach($activities as $a) {
    echo "Activity ID: {$a->id}, User ID: {$a->user_id}, Date: {$a->date}, Minutes: {$a->duration_minutes}\n";
}

$users = App\Models\User::all();
echo "Total users: " . $users->count() . "\n";
foreach($users as $u) {
    echo "User ID: {$u->id}, Email: {$u->email}\n";
}
