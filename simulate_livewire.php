<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// Simulate what Livewire Index does
$user = User::find(18); // thread author

// Mark all as read
echo "Before markAsRead:\n";
echo "Unread: " . $user->unreadNotifications()->count() . "\n";

// This is what mount() does
$user->unreadNotifications->markAsRead();

// Then get all notifications
$notifications = $user->notifications()->latest()->paginate(10);

echo "\nAfter paginate():\n";
echo "Total in this page: {$notifications->count()}\n";
echo "Is empty: " . ($notifications->isEmpty() ? 'YES' : 'NO') . "\n";

echo "\n--- Notifications ---\n";
foreach ($notifications as $notif) {
    $data = is_array($notif->data) ? $notif->data : json_decode($notif->data, true);
    echo "- {$data['type']}: {$data['message']}\n";
}
