<?php

namespace App\Http\Controllers\Pages;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Notifications\UserFollowedNotification;

class FollowController extends Controller
{
    public function store(User $user)
    {
        $currentUser = auth()->user();
        $isFollowing = $currentUser->isFollowing($user);

        $currentUser->toggleFollow($user);

        // Notify user only when following (not unfollowing)
        if (!$isFollowing) {
            $user->notify(new UserFollowedNotification($currentUser));
        }

        return redirect()->route('profile', $user->userName());
    }
}
