<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Thread;
use App\Exceptions\CannotLikeItem;
use App\Notifications\ThreadLikedNotification;

class LikeThreadJob
{
    private $thread;
    private $user;

    public function __construct(Thread $thread, User $user)
    {
        $this->thread = $thread;
        $this->user = $user;
    }

    public function handle()
    {
        if ($this->thread->isLikedBy($this->user)) {
            throw CannotLikeItem::alreadyLiked('thread');
        }

        $this->thread->likedBy($this->user);

        // Kirim notifikasi ke author thread (jika bukan dari author sendiri)
        if (!$this->user->is($this->thread->author())) {
            $this->thread->author()->notify(
                new ThreadLikedNotification($this->user, $this->thread)
            );
        }
    }
}
