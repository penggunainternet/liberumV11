<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserFollowedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected User $follower
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'user_followed',
            'user_id' => $this->follower->id,
            'user_name' => $this->follower->name,
            'user_photo' => $this->follower->profile_photo_url,
            'message' => "{$this->follower->name} mulai mengikuti Anda",
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("{$this->follower->name} mulai mengikuti Anda")
            ->greeting("Halo {$notifiable->name},")
            ->line("{$this->follower->name} mulai mengikuti Anda!")
            ->action('Kunjungi Profil', route('profile', $this->follower))
            ->line('Terima kasih telah menggunakan forum kami!');
    }
}
