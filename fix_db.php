<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$activities = App\Models\Activity::all();
foreach($activities as $a) {
    if ($a->duration_minutes < 0) {
        $a->duration_minutes = abs($a->duration_minutes);
        $a->save();
        echo "Fixed Activity ID: {$a->id} to {$a->duration_minutes} minutes\n";
    }
}

// Now sync gamification
$kernel->call('gamification:sync');
echo "Gamification synced.\n";
