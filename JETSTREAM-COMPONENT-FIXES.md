# Jetstream Component Fixes - Laravel 11 Upgrade

## Overview

Dokumentasi perbaikan komponen Jetstream untuk memastikan semua komponen menggunakan prefix `jet-` yang konsisten.

## Masalah yang Diperbaiki

### 1. **Konfigurasi Jetstream Stack**

**File:** `config/jetstream.php`

-   **Masalah:** Stack dikonfigurasi sebagai 'inertia' padahal aplikasi menggunakan Livewire
-   **Perbaikan:**
    ```php
    'stack' => 'livewire', // Changed from 'inertia'
    ```
-   **Impact:** Error "Class Inertia\Inertia not found" saat akses `/user/profile`

---

### 2. **Form Components**

**Files:**

-   `resources/views/pages/threads/create.blade.php`
-   `resources/views/pages/threads/edit.blade.php`
-   `resources/views/pages/profiles/show.blade.php`
-   `resources/views/admin/categories/create.blade.php`
-   `resources/views/admin/categories/edit.blade.php`

**Perbaikan:**

-   `<x-form>` → `<form method="POST">` + `@csrf` + `@method()`
-   `<x-form.label>` → `<x-jet-label>`
-   `<x-form.input>` → `<x-jet-input>`
-   `<x-form.error for="field">` → `@error('field') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror`
-   `</x-form>` → `</form>`

---

### 3. **Section Title Components**

**Files:**

-   `resources/views/components/jet-form-section.blade.php`
-   `resources/views/components/jet-action-section.blade.php`
-   `resources/views/components/jet/form-section.blade.php`
-   `resources/views/components/jet/action-section.blade.php`

**Perbaikan:**

```blade
<!-- Before -->
<x-section-title>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="description">{{ $description }}</x-slot>
</x-section-title>

<!-- After -->
<x-jet-section-title>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="description">{{ $description }}</x-slot>
</x-jet-section-title>
```

---

### 4. **Welcome Component**

**Files:**

-   `resources/views/components/jet-welcome.blade.php`
-   `resources/views/components/jet/welcome.blade.php`

**Perbaikan:**

```blade
<!-- Before -->
<x-application-logo class="block h-12 w-auto" />

<!-- After -->
<x-jet-application-logo class="block h-12 w-auto" />
```

---

### 5. **Confirms Password Component**

**Files:**

-   `resources/views/components/jet-confirms-password.blade.php`
-   `resources/views/components/jet/confirms-password.blade.php`

**Perbaikan:**

```blade
<!-- Before -->
<x-dialog-modal wire:model.live="confirmingPassword">
    ...
    <x-input type="password" ... />
    <x-input-error for="confirmable_password" ... />
    ...
    <x-secondary-button ...>Cancel</x-secondary-button>
    <x-button ...>Confirm</x-button>
</x-dialog-modal>

<!-- After -->
<x-jet-dialog-modal wire:model.live="confirmingPassword">
    ...
    <x-jet-input type="password" ... />
    <x-jet-input-error for="confirmable_password" ... />
    ...
    <x-jet-secondary-button ...>Cancel</x-jet-secondary-button>
    <x-jet-button ...>Confirm</x-jet-button>
</x-jet-dialog-modal>
```

---

### 6. **Modal Components**

**Files:**

-   `resources/views/components/jet/dialog-modal.blade.php`
-   `resources/views/components/jet/confirmation-modal.blade.php`

**Perbaikan:**

```blade
<!-- Before -->
<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    ...
</x-modal>

<!-- After -->
<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    ...
</x-jet-modal>
```

---

### 7. **@bukScripts Directive Cleanup**

**Files:**

-   `resources/views/pages/threads/create.blade.php`
-   `resources/views/pages/threads/edit.blade.php`

**Perbaikan:**

-   Removed `@bukScripts(true)` yang muncul sebagai text di pojok bawah halaman

---

## Komponen Jetstream yang Sudah Konsisten

Semua komponen berikut sudah menggunakan prefix `jet-` dengan benar:

-   ✅ `<x-jet-input>`
-   ✅ `<x-jet-label>`
-   ✅ `<x-jet-button>`
-   ✅ `<x-jet-checkbox>`
-   ✅ `<x-jet-input-error>`
-   ✅ `<x-jet-action-message>`
-   ✅ `<x-jet-authentication-card>`
-   ✅ `<x-jet-authentication-card-logo>`
-   ✅ `<x-jet-application-logo>`
-   ✅ `<x-jet-application-mark>`
-   ✅ `<x-jet-banner>`
-   ✅ `<x-jet-danger-button>`
-   ✅ `<x-jet-secondary-button>`
-   ✅ `<x-jet-dropdown>`
-   ✅ `<x-jet-dropdown-link>`
-   ✅ `<x-jet-nav-link>`
-   ✅ `<x-jet-responsive-nav-link>`
-   ✅ `<x-jet-section-border>`
-   ✅ `<x-jet-section-title>`
-   ✅ `<x-jet-modal>`
-   ✅ `<x-jet-dialog-modal>`
-   ✅ `<x-jet-confirmation-modal>`
-   ✅ `<x-jet-form-section>`
-   ✅ `<x-jet-action-section>`
-   ✅ `<x-jet-confirms-password>`
-   ✅ `<x-jet-switchable-team>`
-   ✅ `<x-jet-welcome>`

## Komponen Custom Aplikasi (Bukan Jetstream)

Komponen berikut adalah custom aplikasi dan TIDAK perlu prefix jet-:

-   `<x-buttons.primary>` - Custom button
-   `<x-buttons.secondary>` - Custom button
-   `<x-logos.main>` - Application logo
-   `<x-logos.avatar>` - User avatar
-   `<x-logos.bg>` - Background logo
-   `<x-user.avatar>` - User avatar component
-   `<x-links.main>` - Custom link
-   `<x-links.secondary>` - Custom link
-   `<x-alerts.main>` - Alert component
-   `<x-background-music>` - Music player
-   `<x-dashboard.*>` - Dashboard components
-   `<x-sidenav.*>` - Sidebar navigation
-   `<x-partials.*>` - Partial components
-   `<x-lazy-image>` - Lazy loading image
-   `<x-validation-errors>` - Validation errors
-   `<x-social-meta>` - Social meta tags
-   `<x-guest-layout>` - Guest layout
-   `<x-app-layout>` - App layout
-   `<x-base-layout>` - Base layout
-   `<x-admin-layout>` - Admin layout

## Testing Checklist

### Guest User Testing

-   [ ] `/login` - Login form
-   [ ] `/register` - Registration form
-   [ ] `/forgot-password` - Password reset request
-   [ ] `/reset-password` - Password reset form
-   [ ] `/` - Homepage
-   [ ] `/threads` - Thread list
-   [ ] `/threads/{slug}` - Thread detail
-   [ ] `/threads/create` - Create thread (requires login)

### Authenticated User Testing

-   [ ] `/user/profile` - Profile management
    -   [ ] Update profile information
    -   [ ] Update password
    -   [ ] Two-factor authentication
    -   [ ] Browser sessions
    -   [ ] Delete account
-   [ ] `/threads/create` - Create thread with image upload
-   [ ] `/threads/{slug}/edit` - Edit thread

### Admin User Testing

-   [ ] `/admin/categories` - Category management
-   [ ] `/admin/categories/create` - Create category
-   [ ] `/admin/categories/{id}/edit` - Edit category
-   [ ] `/admin/threads/pending` - Pending threads
-   [ ] `/admin/users/active` - Active users

## Commands Run

```bash
# Clear configuration cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Clear all caches
php artisan optimize:clear
```

## Files Modified Summary

**Total: 15 files**

### Configuration (1 file)

-   `config/jetstream.php`

### Views - Thread Forms (2 files)

-   `resources/views/pages/threads/create.blade.php`
-   `resources/views/pages/threads/edit.blade.php`

### Views - Admin Forms (2 files)

-   `resources/views/admin/categories/create.blade.php`
-   `resources/views/admin/categories/edit.blade.php`

### Views - Profile (1 file)

-   `resources/views/pages/profiles/show.blade.php`

### Components - Root Level (4 files)

-   `resources/views/components/jet-form-section.blade.php`
-   `resources/views/components/jet-action-section.blade.php`
-   `resources/views/components/jet-welcome.blade.php`
-   `resources/views/components/jet-confirms-password.blade.php`

### Components - jet/ Folder (5 files)

-   `resources/views/components/jet/form-section.blade.php`
-   `resources/views/components/jet/action-section.blade.php`
-   `resources/views/components/jet/welcome.blade.php`
-   `resources/views/components/jet/confirms-password.blade.php`
-   `resources/views/components/jet/dialog-modal.blade.php`
-   `resources/views/components/jet/confirmation-modal.blade.php`

## Known Issues Fixed

1. ✅ **"Unable to locate a class or view for component [form]"**

    - Root cause: Custom `<x-form>` component tidak ada
    - Solution: Ganti dengan native HTML `<form>` + Jetstream components

2. ✅ **"Class Inertia\Inertia not found"**

    - Root cause: Jetstream stack config salah
    - Solution: Change stack dari 'inertia' ke 'livewire'

3. ✅ **"Unable to locate a class or view for component [section-title]"**

    - Root cause: Component menggunakan `<x-section-title>` tanpa prefix
    - Solution: Ganti dengan `<x-jet-section-title>`

4. ✅ **Text "@bukScripts(true)" muncul di halaman**
    - Root cause: Directive tidak dikenali
    - Solution: Hapus directive yang tidak perlu

## Notes

-   Semua komponen Jetstream harus menggunakan prefix `jet-` untuk konsistensi
-   Komponen custom aplikasi TIDAK menggunakan prefix `jet-`
-   Pastikan `config/jetstream.php` menggunakan stack 'livewire' bukan 'inertia'
-   Setelah perubahan komponen, selalu jalankan `php artisan view:clear`

## Created

-   **Date:** October 20, 2025
-   **Laravel Version:** 11.46.1
-   **Jetstream Version:** 5.3.8
-   **Livewire Version:** 3.6.4
