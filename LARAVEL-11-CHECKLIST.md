# Laravel 11 Upgrade Checklist

## ✅ Completed Tasks

### Core Framework

-   [x] Updated `composer.json` - Laravel 8.40 → 11.0
-   [x] Updated PHP requirement - 7.3/8.0 → 8.2
-   [x] Updated `bootstrap/app.php` - New Laravel 11 structure
-   [x] Updated `app/Exceptions/Handler.php` - New type hints
-   [x] Updated `app/Http/Middleware/TrustProxies.php` - Removed fideloper/proxy

### Dependencies

-   [x] Laravel Jetstream: 2.3 → 5.0
-   [x] Laravel Sanctum: 2.6 → 4.0
-   [x] Livewire: 2.10 → 3.5
-   [x] Intervention Image: 2.7 → 3.7
-   [x] Eloquent Viewable: 6.0 → 7.0
-   [x] Blade Icons packages upgraded
-   [x] PHPUnit: 9.3 → 11.0
-   [x] Added Laravel Pint
-   [x] Added Spatie Ignition
-   [x] Removed deprecated packages (fideloper/proxy, fruitcake/cors, facade/ignition)

### Frontend

-   [x] Updated `package.json` - Vite instead of Mix
-   [x] Created `vite.config.js`
-   [x] Updated `tailwind.config.js` - Tailwind v3 with ES modules
-   [x] Updated `postcss.config.js` - ES modules
-   [x] Tailwind CSS: 2.0 → 3.4
-   [x] Alpine.js: 3.3 → 3.14
-   [x] Updated `resources/views/layouts/app.blade.php` - @vite directive
-   [x] Updated `resources/views/layouts/base.blade.php` - @vite directive
-   [x] Updated `resources/views/layouts/guest.blade.php` - @vite directive
-   [x] Updated `resources/views/components/admin-layout.blade.php` - @vite directive
-   [x] Updated `resources/views/components/partials/head.blade.php` - @vite directive

### Livewire 3 Migration

-   [x] Updated `<livewire:scripts />` → `@livewireScripts` in all layouts
-   [x] Updated `<livewire:styles />` → `@livewireStyles` in all layouts
-   [x] Updated `$this->emit()` → `$this->dispatch()` in:
    -   [x] `app/Http/Livewire/Thread/ReplyForm.php`
    -   [x] `app/Http/Livewire/Reply/Delete.php`
-   [x] Updated `wire:model.defer` → `wire:model.blur` in:
    -   [x] `resources/views/livewire/thread/reply-form.blade.php`
-   [x] Updated `wire:model.lazy` → `wire:model.blur` in:
    -   [x] `resources/views/livewire/reply/update.blade.php`

### Documentation

-   [x] Created `UPGRADE-GUIDE.md`
-   [x] Created `LIVEWIRE-MIGRATION.md`
-   [x] Created `install-laravel11.ps1` PowerShell script

## ⚠️ Manual Tasks Required

### 1. Jetstream Views (Vendor)

The following Jetstream views still use `wire:model.defer` and need to be checked after installation:

-   [ ] `resources/views/vendor/jetstream/components/confirms-password.blade.php`
-   [ ] `resources/views/profile/delete-user-form.blade.php`
-   [ ] `resources/views/profile/update-password-form.blade.php`
-   [ ] `resources/views/profile/update-profile-information-form.blade.php`
-   [ ] `resources/views/profile/logout-other-browser-sessions-form.blade.php`
-   [ ] `resources/views/api/api-token-manager.blade.php`

**Note:** These might be overwritten by Jetstream 5. Test first, then update if needed.

### 2. Intervention Image 3

Check all image upload/processing code:

-   [ ] Profile photo uploads
-   [ ] Thread image uploads
-   [ ] Reply image uploads
-   [ ] Any image manipulation code

**Breaking change:** API completely different. See UPGRADE-GUIDE.md.

### 3. Testing Required

-   [ ] User registration
-   [ ] User login
-   [ ] Email verification
-   [ ] Password reset
-   [ ] Create thread
-   [ ] Upload thread images
-   [ ] Create reply
-   [ ] Upload reply images
-   [ ] Edit reply
-   [ ] Delete reply
-   [ ] Delete thread
-   [ ] Like/unlike thread
-   [ ] Like/unlike reply
-   [ ] Notifications
-   [ ] Profile management
-   [ ] Admin panel
-   [ ] API endpoints

### 4. Performance Testing

-   [ ] N+1 query check
-   [ ] Page load times
-   [ ] Asset loading (Vite)
-   [ ] Livewire real-time updates

### 5. Browser Compatibility

-   [ ] Chrome
-   [ ] Firefox
-   [ ] Safari
-   [ ] Edge

## 🚀 Installation Steps

### Option 1: Using PowerShell Script (Recommended)

```powershell
.\install-laravel11.ps1
```

### Option 2: Manual Installation

```bash
# 1. Remove old dependencies
rm -rf vendor composer.lock node_modules package-lock.json

# 2. Install Composer dependencies
composer install

# 3. Install NPM dependencies
npm install

# 4. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 5. Optimize
composer dump-autoload
php artisan optimize:clear

# 6. Run migrations (if needed)
php artisan migrate

# 7. Build assets
npm run dev
```

## 📋 Post-Installation Checks

### Verify Installation

```bash
# Check PHP version
php -v  # Should be 8.2+

# Check Laravel version
php artisan --version  # Should be Laravel Framework 11.x.x

# Check if Vite is working
npm run dev

# In another terminal, start server
php artisan serve
```

### Check for Errors

```bash
# Check logs
tail -f storage/logs/laravel.log

# Check for deprecation warnings
php artisan route:list
php artisan config:cache
```

## 🐛 Common Issues & Solutions

### Issue 1: Composer dependencies conflict

**Solution:** Make sure PHP 8.2 is installed and active

```bash
php -v
composer update --with-all-dependencies
```

### Issue 2: Vite not building assets

**Solution:**

```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### Issue 3: Livewire components not working

**Solution:** Clear view cache and check browser console

```bash
php artisan view:clear
# Check browser DevTools console for errors
```

### Issue 4: Images not uploading (Intervention Image)

**Solution:** Update image processing code to Intervention Image 3 API

### Issue 5: 404 on routes

**Solution:** Clear route cache

```bash
php artisan route:clear
php artisan route:cache
```

## 📊 Progress Summary

-   **Core Files:** 100% ✅
-   **Dependencies:** 100% ✅
-   **Frontend Build:** 100% ✅
-   **Livewire Migration:** 90% (Jetstream views pending)
-   **Testing:** 0% (Not started)
-   **Documentation:** 100% ✅

## 🔄 Rollback Instructions

If something goes wrong:

```bash
# 1. Restore from git
git reset --hard HEAD~1
git clean -fd

# 2. Restore database
mysql -u root -p liberum < backup_YYYYMMDD.sql

# 3. Reinstall old dependencies
composer install
npm install
```

## 📚 Resources

-   [Laravel 11 Docs](https://laravel.com/docs/11.x)
-   [Laravel 11 Upgrade Guide](https://laravel.com/docs/11.x/upgrade)
-   [Livewire 3 Docs](https://livewire.laravel.com/docs)
-   [Jetstream 5 Docs](https://jetstream.laravel.com/5.x/introduction.html)
-   [Intervention Image 3 Docs](https://image.intervention.io/v3)
-   [Vite Docs](https://vitejs.dev)

## ✨ New Features in Laravel 11

-   Streamlined application structure
-   Per-second rate limiting
-   Health routing
-   Graceful encryption key rotation
-   Queue interaction testing
-   New Artisan commands
-   Improved performance
-   Better developer experience

---

**Last Updated:** October 20, 2025
**Laravel Version:** 11.0
**PHP Version:** 8.2+
