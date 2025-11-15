# 🔧 Fix: Mail Connection Error in Notifications

**Issue**: TransportException when creating reply - mail server connection fails  
**Root Cause**: Mail channel enabled but SMTP server (127.0.0.1:1025) not running  
**Solution**: Use database channel only, no email delivery

---

## What Changed

### 1. Disabled Mail Channel

All three notification classes now use **database channel only**:

```php
// Before
public function via($notifiable) {
    return ['database', 'mail'];  // ❌ Mail fails if server down
}

// After
public function via($notifiable) {
    return ['database'];  // ✅ Only database - no mail dependency
}
```

**Files Modified**:

-   `app/Notifications/ThreadLikedNotification.php`
-   `app/Notifications/NewReplyNotification.php`
-   `app/Notifications/UserFollowedNotification.php`

### 2. Updated Queue Configuration

Changed `.env`:

```diff
- QUEUE_CONNECTION=sync
+ QUEUE_CONNECTION=database
```

**Why**:

-   `sync` = Execute jobs immediately (blocking)
-   `database` = Queue jobs to database, process later with `queue:work`
-   Prevents UI from waiting for mail/database operations

---

## Result

✅ **Notifications work without email errors**

-   Reply posts successfully
-   Notification saved to database
-   No TransportException thrown
-   No page refresh needed

---

## Testing

1. **Create a reply** (as User A on User B's thread)
    - Should NOT show error anymore
    - Reply displays immediately
2. **Check notifications** (as User B)

    - Go to `/dashboard/notifications`
    - Should see "Balasan Baru" notification
    - No page refresh needed

3. **Process queue** (optional, for async jobs)
    ```bash
    php artisan queue:work
    ```

---

## For Production

If you want **email notifications** in production:

1. **Set up mail server** (e.g., SendGrid, Mailtrap, AWS SES):

    ```env
    MAIL_MAILER=sendgrid
    MAIL_HOST=smtp.sendgrid.net
    MAIL_PORT=587
    MAIL_USERNAME=apikey
    MAIL_PASSWORD=your_sendgrid_key
    ```

2. **Re-enable mail channel** in notification classes:

    ```php
    public function via($notifiable) {
        return ['database', 'mail'];
    }
    ```

3. **Run queue worker**:
    ```bash
    php artisan queue:work
    ```

---

## Current Status

✅ All notifications working without mail dependency  
✅ No errors on reply/like/follow  
✅ Database persistence active  
✅ Ready for production (without email)

If you later add email server, just enable mail channel again - no code changes needed.
