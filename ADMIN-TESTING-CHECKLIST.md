# 🔍 ADMIN FEATURE TESTING CHECKLIST

## Status: Testing in Progress

Date: 2025-10-20

---

## 📋 ADMIN FEATURES TO TEST

### 1️⃣ **ADMIN DASHBOARD** (`/admin`)

-   [ ] Access admin dashboard
-   [ ] View statistics (users, threads, categories)
-   [ ] Check all widgets display correctly
-   [ ] No errors in console

### 2️⃣ **USER MANAGEMENT** (`/admin/users`)

-   [ ] View active users list
-   [ ] **Promote user to moderator**
    -   Route: `/admin/users/{user}/promote`
    -   Expected: User type changes to moderator
-   [ ] **Demote moderator to user**
    -   Route: `/admin/users/{user}/demote`
    -   Expected: User type reverts to normal user
-   [ ] **Delete user**
    -   Route: `/admin/users/{user}/delete`
    -   Expected: User deleted, all related data handled
    -   ⚠️ WARNING: Test with dummy account!

### 3️⃣ **THREAD MODERATION** (`/admin/threads`)

#### Pending Threads (`/admin/threads/pending`)

-   [ ] View list of pending threads (status = 0)
-   [ ] **Approve thread**
    -   Route: `POST /admin/threads/{thread}/approve`
    -   Expected: Thread status = 1 (approved)
    -   Expected: Thread visible to public
-   [ ] **Reject thread**
    -   Route: `POST /admin/threads/{thread}/reject`
    -   Expected: Thread status = 2 (rejected)
    -   Expected: Thread hidden from public

#### Approved Threads (`/admin/threads/approved`)

-   [ ] View list of approved threads (status = 1)
-   [ ] Can navigate to thread details
-   [ ] Can still reject if needed

#### Rejected Threads (`/admin/threads/rejected`)

-   [ ] View list of rejected threads (status = 2)
-   [ ] Can view details
-   [ ] Can re-approve if needed

#### Thread Detail (`/admin/threads/{thread}`)

-   [ ] View thread full details
-   [ ] View author information
-   [ ] View all replies
-   [ ] Approve/Reject buttons work
-   [ ] **Check if images load** ← IMPORTANT!

### 4️⃣ **CATEGORY MANAGEMENT** (`/admin/categories`)

#### List Categories (`/admin/categories`)

-   [ ] View all categories
-   [ ] See category name, slug, created date
-   [ ] Edit and Delete buttons present
-   [ ] Zondicon icons display correctly (edit-pencil, trash)

#### Create Category (`/admin/categories/create`)

-   [ ] Form displays correctly
-   [ ] **Create new category**
    -   Required: Name, Slug
    -   Expected: Category created in database
    -   Expected: Redirect to categories list
-   [ ] Validation works (empty fields)
-   [ ] Slug auto-generation (if implemented)

#### Edit Category (`/admin/categories/edit/{slug}`)

-   [ ] Form pre-filled with category data
-   [ ] **Update category**
    -   Can change name
    -   Can change slug
    -   Expected: Changes saved
-   [ ] Validation works

#### Delete Category (`/admin/categories/{slug}`)

-   [ ] Category deleted successfully
-   [ ] Related threads handled properly
    -   Option 1: Threads moved to default category
    -   Option 2: Threads cascade deleted
    -   ⚠️ CHECK: What happens to threads in deleted category?

---

## 🖼️ **IMAGE UPLOAD TESTING** (Critical!)

### Thread Creation with Image

-   [ ] Go to `/threads/create`
-   [ ] Fill in title, body, category
-   [ ] **Upload JPEG image** (< 5MB)
    -   Expected: Upload successful
    -   Expected: Image resized to max 1920x1080
    -   Expected: Thumbnail created (300x300)
    -   Expected: Image path stored in database
-   [ ] **Upload PNG image**
    -   Expected: Same as JPEG
-   [ ] **Upload WebP image** (if supported)
    -   Expected: Same as JPEG
-   [ ] **Upload large image** (> 1920x1080)
    -   Expected: Auto-resized to 1920x1080
    -   Expected: Aspect ratio preserved
-   [ ] Submit thread
-   [ ] Thread created successfully
-   [ ] Image displays in thread view
-   [ ] Thumbnail displays in thread list

### Reply with Image

-   [ ] Go to any thread
-   [ ] Add reply with image
-   [ ] **Upload image**
    -   Expected: Same checks as thread image
-   [ ] Submit reply
-   [ ] Reply created with image
-   [ ] Image displays correctly

### Profile Photo Upload

-   [ ] Go to `/user/profile`
-   [ ] Click "Select A New Photo"
-   [ ] **Upload profile photo**
    -   Expected: Photo uploaded
    -   Expected: Photo resized appropriately
    -   Expected: Old photo deleted (if exists)
-   [ ] Photo displays in navigation
-   [ ] Photo displays in profile

---

## 🚨 **POTENTIAL ERRORS TO WATCH FOR**

### Intervention Image Errors

-   ❌ "Class 'GD' not found" → Need GD extension
-   ❌ "Cannot read image" → File permission issue
-   ❌ "Memory limit exceeded" → PHP memory too low
-   ❌ "Call to undefined method" → API mismatch v2/v3

### Livewire 3 Errors

-   ❌ "Unable to find component" → Namespace issue
-   ❌ "Access level must be public" → DispatchesJobs conflict
-   ❌ "Property not found" → Missing public property

### Heroicons Errors

-   ❌ "Unable to locate component [heroicon-*]" → Icon renamed in v2

### Route Errors

-   ❌ "Target class does not exist" → Controller not found
-   ❌ "Route not defined" → Missing route registration

---

## 🔧 **COMPONENT CHECKS**

### Blade Components Used in Admin

-   [x] `<x-admin-layout>` - Admin master layout
-   [x] `<x-table.head>` - Table header
-   [x] `<x-table.data>` - Table data cell
-   [x] `<x-zondicon-edit-pencil>` - Edit icon
-   [x] `<x-zondicon-trash>` - Delete icon
-   [x] `<x-zondicon-user>` - User icon
-   [x] `<x-zondicon-user-group>` - User group icon
-   [x] `<x-zondicon-notifications-outline>` - Notification icon
-   [x] `<x-zondicon-view-tile>` - View icon
-   [x] `<x-zondicon-compose>` - Compose icon

### Livewire Components in Admin

-   [ ] `@livewire('notifications.index')` - Notifications list
-   [ ] `@livewire('notifications.indicator')` - Notification bell
-   [ ] `@livewire('notifications.count')` - Notification count badge

---

## 📝 **DATABASE CHECKS**

### Tables to Monitor

-   `users` - User management changes
-   `threads` - Thread status changes (pending/approved/rejected)
-   `categories` - Category CRUD operations
-   `media` - Image upload records
-   `notifications` - Admin action notifications

### Status Codes

-   **Thread Status:**

    -   `0` = Pending
    -   `1` = Approved
    -   `2` = Rejected

-   **User Type:**
    -   `1` = Normal User
    -   `2` = Moderator
    -   `3` = Admin

---

## 🎯 **PRIORITY TESTING ORDER**

1. **CRITICAL** - Image Upload (Intervention Image v3)

    - Thread with image
    - Reply with image
    - Profile photo

2. **HIGH** - Thread Moderation

    - Approve pending thread
    - Reject thread
    - View approved/rejected lists

3. **MEDIUM** - Category Management

    - Create category
    - Edit category
    - Delete category (check thread handling)

4. **LOW** - User Management
    - View users
    - Promote/demote (test with dummy account)

---

## 🐛 **BUG TRACKING**

### Found Issues:

1. ⚠️ `wire:model.defer` still used (12 locations) - MINOR

    - Status: Not critical, backward compatible
    - Fix: Optional upgrade to `wire:model.blur`

2. ✅ Intervention Image v2 → v3 - FIXED
    - Status: Updated in HasMedia.php
    - Testing: REQUIRED

### To Be Fixed:

-   [ ] None currently

---

## 📊 **TESTING RESULTS**

### ✅ Passed Tests:

-   (Will be filled during testing)

### ❌ Failed Tests:

-   (Will be logged here)

### ⚠️ Warnings:

-   (Non-critical issues)

---

## 🔄 **ROLLBACK PLAN**

If critical errors found:

1. **Intervention Image Issues:**

    - Revert HasMedia.php to backup
    - Downgrade intervention/image to v2.7

2. **Livewire Issues:**

    - Check namespace in app/Livewire
    - Verify component registration

3. **General Errors:**
    - `php artisan optimize:clear`
    - `composer dump-autoload`
    - Check PHP error logs

---

## 📞 **SUPPORT CHECKLIST**

Before reporting bugs:

-   [ ] Clear cache: `php artisan optimize:clear`
-   [ ] Check PHP error log
-   [ ] Check browser console for JS errors
-   [ ] Verify database connections
-   [ ] Check file permissions (storage/app/public)

---

**Testing Started:** [Pending]
**Testing Completed:** [Pending]
**Status:** READY FOR TESTING
