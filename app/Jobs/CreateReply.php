<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Reply;
use App\Models\ReplyAble;
use App\Models\Thread;
use App\Events\ReplyWasCreated;
use App\Http\Requests\CreateReplyRequest;
use App\Notifications\NewReplyNotification;

class CreateReply
{
    private $body;
    private $author;
    private $replyAble;
    private $images;

    public function __construct(string $body, User $author, ReplyAble $replyAble, array $images = [])
    {
        $this->body = $body;
        $this->author = $author;
        $this->replyAble = $replyAble;
        $this->images = $images;
    }

    public static function fromRequest(CreateReplyRequest $request): self
    {
        return new static(
            $request->body(),
            $request->author(),
            $request->replyAble(),
            $request->images()
        );
    }

    public function handle(): Reply
    {
        $reply = new Reply([
            'body' => $this->body
        ]);

        $reply->authoredBy($this->author);
        $reply->to($this->replyAble);
        $reply->save();

        // Handle image uploads
        if (!empty($this->images)) {
            $reply->addMediaFromArray($this->images, 'replies');
        }

        event(new ReplyWasCreated($reply));

        // Notify thread author about new reply
        if ($this->replyAble instanceof Thread && !$this->author->is($this->replyAble->author())) {
            $this->replyAble->author()->notify(new NewReplyNotification($this->author, $this->replyAble, $this->body));
        }

        return $reply;
    }
}
