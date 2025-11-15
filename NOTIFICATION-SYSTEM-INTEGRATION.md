# Notification System Integration - LiberumV11

**Status**: ✅ COMPLETED

## Overview

Comprehensive notification system for social features (like, reply, follow) with database persistence and email delivery.

## Components Implemented

### 1. Notification Classes

#### ThreadLikedNotification

-   **Location**: `app/Notifications/ThreadLikedNotification.php`
-   **Purpose**: Notify thread author when someone likes their thread
-   **Data Sent**:
    -   `type`: 'thread_liked'
    -   `user_id`: ID of the person who liked
    -   `user_name`: Name of the person who liked
    -   `thread_id`: ID of the liked thread
    -   `thread_title`: Title of the thread
    -   `thread_slug`: Slug of the thread
    -   `message`: Human-readable message
-   **Channels**: database, mail
-   **Constraint**: Does not notify if liker is the author

#### NewReplyNotification

-   **Location**: `app/Notifications/NewReplyNotification.php`
-   **Purpose**: Notify thread author when someone replies to their thread
-   **Data Sent**:
    -   `type`: 'new_reply'
    -   `user_id`: ID of the person who replied
    -   `user_name`: Name of the replier
    -   `thread_id`: ID of the thread
    -   `thread_title`: Title of the thread
    -   `thread_slug`: Slug of the thread
    -   `reply_body`: First 100 characters of reply
    -   `message`: Human-readable message
-   **Channels**: database, mail
-   **Constraint**: Does not notify if replier is the author

#### UserFollowedNotification

-   **Location**: `app/Notifications/UserFollowedNotification.php`
-   **Purpose**: Notify user when someone follows them
-   **Data Sent**:
    -   `type`: 'user_followed'
    -   `user_id`: ID of the follower
    -   `user_name`: Name of the follower
    -   `user_photo`: Profile photo URL
    -   `message`: Human-readable message
-   **Channels**: database, mail
-   **Constraint**: Only sends on follow action, not on unfollow

### 2. Job Integrations

#### LikeThreadJob

-   **Location**: `app/Jobs/LikeThreadJob.php`
-   **Integration**: Dispatches `ThreadLikedNotification` after like is saved
-   **Code**:
    ```php
    if (!$this->user->is($this->thread->author())) {
        $this->thread->author()->notify(
            new ThreadLikedNotification($this->user, $this->thread)
        );
    }
    ```

#### CreateReply Job

-   **Location**: `app/Jobs/CreateReply.php`
-   **Integration**: Dispatches `NewReplyNotification` after reply is created and event is fired
-   **Code**:
    ```php
    if ($this->replyAble instanceof Thread && !$this->author->is($this->replyAble->author())) {
        $this->replyAble->author()->notify(
            new NewReplyNotification($this->author, $this->replyAble, $this->body)
        );
    }
    ```

#### FollowController

-   **Location**: `app/Http/Controllers/Pages/FollowController.php`
-   **Integration**: Dispatches `UserFollowedNotification` after follow is established
-   **Code**:

    ```php
    $currentUser = auth()->user();
    $isFollowing = $currentUser->isFollowing($user);

    $currentUser->toggleFollow($user);

    if (!$isFollowing) {
        $user->notify(new UserFollowedNotification($currentUser));
    }
    ```

### 3. View Updates

#### Notification Dashboard

-   **Location**: `resources/views/livewire/notifications/index.blade.php`
-   **Status**: Updated with 3 new notification type handlers
-   **Handlers**:
    -   `thread_liked`: Red heart icon, "Postingan Disukai"
    -   `new_reply`: Blue chat icon, "Balasan Baru"
    -   `user_followed`: Purple user icon, "Pengikut Baru"
-   **Each handler**:
    -   Displays icon with appropriate color
    -   Shows message with user/thread context
    -   Provides link to relevant resource (thread or profile)
    -   Shows time relative to notification creation

## Database Schema

### Notifications Table

```sql
Column          | Type     | Description
----------------|----------|------------------------
id              | uuid     | Primary key
notifiable_id   | bigint   | User ID (receives notification)
notifiable_type | string   | 'App\Models\User'
type            | string   | 'database' channel
data            | json     | Notification payload
read_at         | datetime | When notification was read
created_at      | datetime | When notification created
updated_at      | datetime | Last update
```

## Data Flow

### Example: User Likes a Thread

1. User clicks like button
2. `LikeThreadJob` is dispatched (async via queue)
3. Job calls `$thread->likedBy($user)`
4. Job creates notification: `ThreadLikedNotification($user, $thread)`
5. Notification is stored in `notifications` table with:
    - `data` = JSON with type='thread_liked' and context
6. Email is sent to thread author
7. Dashboard displays notification with heart icon

### Example: User Replies to Thread

1. User submits reply form
2. `CreateReply` job is dispatched/executed
3. Reply is saved to database
4. `ReplyWasCreated` event is fired
5. Job creates notification: `NewReplyNotification($replier, $thread, $replyBody)`
6. Notification is stored with type='new_reply'
7. Dashboard shows notification with chat icon

### Example: User Follows Another User

1. User clicks follow button on profile
2. `FollowController::store()` is called
3. `toggleFollow()` is executed via `HasFollows` trait
4. If new follow (not already following):
    - Notification is created: `UserFollowedNotification($follower)`
    - Stored with type='user_followed'
    - Email sent to followed user
5. Dashboard shows notification with user icon

## Configuration

### Required Settings

1. **Queue Connection**:

    - Ensure `QUEUE_CONNECTION` in `.env` is set (default: 'database')
    - Run queue worker: `php artisan queue:work`

2. **Notification Channel**:

    - Database channel must be enabled in `config/app.php`
    - `notifications` migration must be run

3. **Email Configuration**:
    - Set `MAIL_DRIVER` in `.env`
    - Configure mail sender details

### Testing Queue

To process pending jobs immediately:

```bash
php artisan queue:work --stop-when-empty
```

## Testing Checklist

-   [ ] Like a thread → Check `/dashboard/notifications` for "Postingan Disukai" notification
-   [ ] Reply to thread → Check for "Balasan Baru" notification
-   [ ] Follow a user → Check for "Pengikut Baru" notification
-   [ ] Verify email is sent for each action
-   [ ] Verify links in notification view work correctly
-   [ ] Test that self-actions don't create notifications (like own thread, reply own thread)

## Debugging

### Notifications not appearing

1. Check queue is running: `php artisan queue:work --verbose`
2. Check notifications table has records:
    ```bash
    php artisan tinker
    DB::table('notifications')->count()
    ```
3. Check notification data format matches view expectations
4. Verify user is not the notifiable (no self-notifications)

### Email not sending

1. Check mail configuration in `.env`
2. Check Laravel logs: `storage/logs/laravel.log`
3. Test with: `php artisan tinker`
    ```php
    $user = User::first();
    $thread = Thread::first();
    $user->notify(new ThreadLikedNotification(User::find(2), $thread));
    ```

## Integration Notes

All changes follow the user constraint: **"jangan ubah apapun oke apalagi mengubah kode yang ada sesuaikan kode yang baru dengan kode yang ada"** (don't change anything, adapt new code to existing code).

### Code Changes Made

1. **LikeThreadJob**: Added notification dispatch after existing like logic (non-breaking)
2. **CreateReply**: Added notification dispatch after existing event dispatch (non-breaking)
3. **FollowController**: Added notification dispatch with conditional check for new follows
4. **Notification View**: Added @elseif blocks for new types (non-breaking, extends existing structure)

### No Breaking Changes

-   All existing functionality preserved
-   All existing methods/traits unchanged
-   Notifications are opt-in (only dispatch when conditions met)
-   Self-notifications prevented by conditional checks

## Files Modified

1. `app/Notifications/ThreadLikedNotification.php` (created)
2. `app/Notifications/NewReplyNotification.php` (created)
3. `app/Notifications/UserFollowedNotification.php` (created)
4. `app/Jobs/LikeThreadJob.php` (modified - added notification)
5. `app/Jobs/CreateReply.php` (modified - added notification import and dispatch)
6. `app/Http/Controllers/Pages/FollowController.php` (modified - added notification)
7. `resources/views/livewire/notifications/index.blade.php` (modified - added 3 handlers)

## Related Documentation

-   API Endpoints: `docs/API-ENDPOINTS.md`
-   Use Cases: `docs/USE-CASE-DIAGRAM.md`
-   ERD: `ERD-DIAGRAM.md`
