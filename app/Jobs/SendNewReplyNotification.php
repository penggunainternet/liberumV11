<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Thread;
use App\Notifications\NewReplyNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewReplyNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected User $replier,
        protected Thread $thread,
        protected string $replyBody
    ) {}

    public function handle(): void
    {
        // Jangan kirim notifikasi jika user reply postingan mereka sendiri
        if ($this->replier->is($this->thread->author())) {
            return;
        }

        $this->thread->author()->notify(
            new NewReplyNotification($this->replier, $this->thread, $this->replyBody)
        );
    }
}
