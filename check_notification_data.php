<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$notif = DB::table('notifications')->latest()->first();
echo "Latest Notification Data:\n";
print_r(json_decode($notif->data, true));
echo "\n\nNotification Type Column: {$notif->type}\n";
