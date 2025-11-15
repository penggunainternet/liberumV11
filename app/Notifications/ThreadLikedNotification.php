<?php

namespace App\Notifications;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ThreadLikedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected User $liker,
        protected Thread $thread
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        // Reload thread with category to ensure relationship is loaded
        $thread = \App\Models\Thread::with('category')->find($this->thread->id) ?? $this->thread;

        return [
            'type' => 'thread_liked',
            'user_id' => $this->liker->id,
            'user_name' => $this->liker->name,
            'thread_id' => $thread->id,
            'thread_title' => $thread->title,
            'thread_slug' => $thread->slug,
            'category_slug' => $thread->category?->slug,
            'message' => "{$this->liker->name} menyukai postingan Anda: {$thread->title}",
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Reload thread with category to ensure relationship is loaded
        $thread = \App\Models\Thread::with('category')->find($this->thread->id) ?? $this->thread;

        return (new MailMessage)
            ->subject("{$this->liker->name} menyukai postingan Anda")
            ->greeting("Halo {$notifiable->name},")
            ->line("{$this->liker->name} menyukai postingan Anda: {$thread->title}")
            ->action('Lihat Postingan', route('threads.show', [
                'category' => $thread->category?->slug,
                'thread' => $thread->slug
            ]))
            ->line('Terima kasih telah menggunakan forum kami!');
    }
}
