<?php

namespace App\Http\Controllers\API;

use App\Models\Thread;
use App\Jobs\SendThreadLikedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    public function toggleLike(Request $request, Thread $thread): JsonResponse
    {
        $user = $request->user();

        // Check if user already liked this thread
        $liked = $thread->likes()
            ->where('user_id', $user->id)
            ->where('likeable_type', Thread::class)
            ->exists();

        if ($liked) {
            // Unlike
            $thread->likes()
                ->where('user_id', $user->id)
                ->where('likeable_type', Thread::class)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Unlike berhasil',
                'liked' => false,
                'likes_count' => $thread->likes()->count()
            ]);
        } else {
            // Like
            $thread->likes()->create([
                'user_id' => $user->id,
                'likeable_type' => Thread::class,
                'likeable_id' => $thread->id,
            ]);

            // Dispatch notification job
            SendThreadLikedNotification::dispatch($user, $thread);

            return response()->json([
                'status' => 'success',
                'message' => 'Like berhasil',
                'liked' => true,
                'likes_count' => $thread->likes()->count()
            ], 201);
        }
    }
}
