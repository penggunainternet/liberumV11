<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Thread;
use App\Notifications\ThreadLikedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendThreadLikedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected User $liker,
        protected Thread $thread
    ) {}

    public function handle(): void
    {
        // Jangan kirim notifikasi jika user like postingan mereka sendiri
        if ($this->liker->is($this->thread->author())) {
            return;
        }

        $this->thread->author()->notify(
            new ThreadLikedNotification($this->liker, $this->thread)
        );
    }
}
