<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\WithPagination;
use App\Policies\NotificationPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public function mount()
    {
        // Mark all unread notifications as read when page loads
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('livewire.notifications.index', [
            'notifications' => Auth::user()->notifications()->latest()->paginate(10),
        ]);
    }
}
