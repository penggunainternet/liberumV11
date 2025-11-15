<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Check user 18 (thread author)
$user = User::find(18);
if (!$user) {
    echo "User 18 not found\n";
    exit;
}

echo "User: {$user->name} (ID: {$user->id})\n\n";

// Get all notifications for this user
$allNotifications = $user->notifications()->latest()->get();
echo "Total notifications: {$allNotifications->count()}\n\n";

// Check unread
$unread = $user->unreadNotifications()->get();
echo "Unread notifications: {$unread->count()}\n\n";

echo "--- Latest Notifications ---\n";
foreach ($allNotifications->take(5) as $notif) {
    $data = is_array($notif->data) ? $notif->data : json_decode($notif->data, true);
    echo "ID: {$notif->id}\n";
    echo "Type: {$notif->type}\n";
    echo "Data Type: {$data['type']}\n";
    echo "Full Data: " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "Read: " . ($notif->read_at ? 'Yes' : 'No') . "\n";
    echo "Created: {$notif->created_at}\n";
    echo "---\n\n";
}
