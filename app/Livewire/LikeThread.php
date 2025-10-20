<?php

namespace App\Livewire;

use App\Jobs\LikeThreadJob;
use App\Jobs\UnlikeThreadJob;
use App\Models\Thread;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LikeThread extends Component
{
    public $thread;

    public function mount(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function toggleLike()
    {
        if ($this->thread->isLikedBy(Auth::user())) {
            dispatch_sync(new UnlikeThreadJob($this->thread, Auth::user()));
        } else {
            dispatch_sync(new LikeThreadJob($this->thread, Auth::user()));
        }
    }

    public function render()
    {
        return view('livewire.like-thread');
    }
}
