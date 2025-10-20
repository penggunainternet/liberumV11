<?php

namespace App\Livewire;

use App\Models\Reply;
use Livewire\Component;
use App\Jobs\LikeReplyJob;
use App\Jobs\UnlikeReplyJob;
use Illuminate\Support\Facades\Auth;

class LikeReply extends Component
{
    public $reply;

    public function mount(Reply $reply)
    {
        $this->reply = $reply;
    }

    public function toggleLike()
    {
        if ($this->reply->isLikedBy(Auth::user())) {
            dispatch_sync(new UnlikeReplyJob($this->reply, Auth::user()));
        } else {
            dispatch_sync(new LikeReplyJob($this->reply, Auth::user()));
        }
    }

    public function render()
    {
        return view('livewire.like-reply');
    }
}
