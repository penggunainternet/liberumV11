<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Thread;

$thread = Thread::find(26);
echo "Thread ID: {$thread->id}\n";
echo "Thread Title: {$thread->title}\n";
echo "Thread Slug: {$thread->slug}\n";
echo "Category ID: {$thread->category_id}\n";

if ($thread->category) {
    echo "Category Name: {$thread->category->name}\n";
    echo "Category Slug: {$thread->category->slug}\n";
} else {
    echo "Category: NULL (No category relationship found)\n";
}
