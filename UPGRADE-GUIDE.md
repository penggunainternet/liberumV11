# Panduan Upgrade Laravel 8 ke Laravel 11

## Perubahan Utama

### 1. PHP Requirement

-   **Sebelum:** PHP ^7.3|^8.0
-   **Sekarang:** PHP ^8.2+

### 2. Dependencies Yang Di-upgrade

#### Laravel Core

-   `laravel/framework`: ^8.40 → ^11.0
-   `laravel/jetstream`: ^2.3 → ^5.0
-   `laravel/sanctum`: ^2.6 → ^4.0
-   `laravel/tinker`: ^2.5 → ^2.9

#### Livewire (Breaking Change)

-   `livewire/livewire`: ^2.10 → ^3.5
-   **PENTING:** Livewire 3 memiliki perubahan sintaks. Lihat panduan migrasi di bawah.

#### Packages Lainnya

-   `intervention/image`: ^2.7 → ^3.7 (Breaking Change)
-   `cyrildewit/eloquent-viewable`: ^6.0 → ^7.0
-   `blade-ui-kit/blade-heroicons`: ^1.3 → ^2.4
-   `blade-ui-kit/blade-icons`: ^1.0 → ^1.7
-   `blade-ui-kit/blade-zondicons`: ^1.3 → ^2.2
-   `guzzlehttp/guzzle`: ^7.0.1 → ^7.8

#### Dev Dependencies

-   `phpunit/phpunit`: ^9.3.3 → ^11.0
-   `nunomaduro/collision`: ^5.0 → ^8.0
-   `fakerphp/faker`: ^1.9.1 → ^1.23
-   `laravel/sail`: ^1.0.1 → ^1.26
-   `spatie/laravel-ignition`: Baru (menggantikan facade/ignition)
-   `laravel/pint`: ^1.13 (Baru - Code Style Fixer)

#### Packages Yang Dihapus

-   `fideloper/proxy`: Sudah built-in di Laravel
-   `fruitcake/laravel-cors`: Sudah built-in di Laravel
-   `blade-ui-kit/blade-ui-kit`: Package abandoned

### 3. Build Tools

-   **Sebelum:** Laravel Mix (Webpack)
-   **Sekarang:** Vite
-   File `webpack.mix.js` diganti dengan `vite.config.js`

### 4. Struktur Bootstrap

File `bootstrap/app.php` sekarang menggunakan sintaks baru Laravel 11 dengan fluent configuration.

### 5. Middleware

-   Middleware sekarang didefinisikan di `bootstrap/app.php`
-   File `app/Http/Kernel.php` masih ada tapi tidak digunakan di Laravel 11
-   `TrustProxies` sekarang menggunakan built-in Laravel class

### 6. Frontend (Tailwind CSS)

-   **Sebelum:** Tailwind CSS v2
-   **Sekarang:** Tailwind CSS v3
-   Config file sekarang menggunakan ES modules

## Langkah-langkah Instalasi

### 1. Pastikan PHP 8.2 atau lebih tinggi terinstall

```bash
php -v
```

### 2. Backup database dan kode

```bash
# Backup database
mysqldump -u root -p liberum > backup_$(date +%Y%m%d).sql

# Backup kode via git
git add .
git commit -m "Backup sebelum upgrade Laravel 11"
```

### 3. Install dependencies baru

```bash
# Hapus vendor dan composer.lock
rm -rf vendor composer.lock

# Install ulang dengan dependencies baru
composer install
```

### 4. Install frontend dependencies baru

```bash
# Hapus node_modules dan package-lock.json
rm -rf node_modules package-lock.json

# Install ulang
npm install
```

### 5. Jalankan migrasi jika ada perubahan schema

```bash
php artisan migrate
```

### 6. Clear cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 7. Build assets dengan Vite

```bash
# Development
npm run dev

# Production
npm run build
```

## Migrasi Livewire 2 ke 3

### Perubahan Sintaks Utama

#### 1. Wire:model

```blade
<!-- Livewire 2 -->
<input wire:model.defer="name">

<!-- Livewire 3 -->
<input wire:model.blur="name">
```

#### 2. Wire:loading

```blade
<!-- Tidak ada perubahan, tetap sama -->
<div wire:loading>Loading...</div>
```

#### 3. Events

```blade
<!-- Livewire 2 -->
$this->emit('eventName', $data);

<!-- Livewire 3 -->
$this->dispatch('eventName', data: $data);
```

#### 4. Listeners

```php
// Livewire 2
protected $listeners = ['eventName' => 'methodName'];

// Livewire 3 (tetap sama, tapi bisa juga menggunakan attributes)
use Livewire\Attributes\On;

#[On('eventName')]
public function methodName($data) {}
```

### File-file Yang Perlu Dicek

1. **Semua Livewire Components** di `app/Http/Livewire/`

    - Update `wire:model.defer` menjadi `wire:model.blur` atau `wire:model.live`
    - Update `$this->emit()` menjadi `$this->dispatch()`
    - Update listeners jika perlu

2. **Blade Views** yang menggunakan Livewire
    - Cari semua file dengan `wire:` directives
    - Update sintaks sesuai Livewire 3

## Migrasi Intervention Image 2 ke 3

### Perubahan API Utama

```php
// Image 2
use Intervention\Image\Facades\Image;

$image = Image::make($file);
$image->resize(300, 200);
$image->save($path);

// Image 3
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

$manager = new ImageManager(new Driver());
$image = $manager->read($file);
$image->scale(width: 300);
$image->save($path);
```

### File-file Yang Perlu Dicek

1. Controllers yang upload gambar
2. Actions untuk profile photo
3. Seeder yang generate gambar

## Testing

### 1. Test Basic Functionality

-   [ ] Login/Register
-   [ ] Create Thread
-   [ ] Upload Images
-   [ ] Reply/Comment
-   [ ] Like/Unlike
-   [ ] Profile Management

### 2. Test Admin Features

-   [ ] Admin Dashboard
-   [ ] Moderate Content
-   [ ] User Management

### 3. Test Email

-   [ ] Email Verification
-   [ ] Password Reset
-   [ ] Notifications

## Rollback Plan

Jika terjadi masalah kritis:

```bash
# 1. Restore dari git
git reset --hard HEAD~1

# 2. Restore database
mysql -u root -p liberum < backup_YYYYMMDD.sql

# 3. Install dependencies lama
composer install
npm install
```

## Catatan Penting

⚠️ **BREAKING CHANGES:**

1. **Livewire 3** - Harus update semua components dan views
2. **Intervention Image 3** - API berubah total
3. **Vite** - Asset URLs berubah dari mix() ke @vite()

✅ **Kompatibilitas:**

-   UI tetap sama (Tailwind CSS)
-   Database schema tidak berubah
-   Alur bisnis tidak berubah
-   Route tetap sama

## Resources

-   [Laravel 11 Release Notes](https://laravel.com/docs/11.x/releases)
-   [Laravel 11 Upgrade Guide](https://laravel.com/docs/11.x/upgrade)
-   [Livewire 3 Upgrade Guide](https://livewire.laravel.com/docs/upgrading)
-   [Intervention Image 3 Docs](https://image.intervention.io/v3)
-   [Jetstream 5 Docs](https://jetstream.laravel.com/5.x/introduction.html)

## Status Upgrade

-   [x] composer.json updated
-   [x] package.json updated
-   [x] bootstrap/app.php migrated
-   [x] Exception Handler updated
-   [x] TrustProxies middleware updated
-   [x] Vite config created
-   [x] Tailwind config updated
-   [x] PostCSS config created
-   [ ] Install composer dependencies
-   [ ] Install npm dependencies
-   [ ] Update Livewire components
-   [ ] Update Intervention Image usage
-   [ ] Update blade views (@vite directives)
-   [ ] Test all features
-   [ ] Deploy to production
