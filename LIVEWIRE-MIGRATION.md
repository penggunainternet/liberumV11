# Panduan Migrasi Livewire 2 ke 3

## Perubahan Yang Dilakukan

### 1. Blade Directives

✅ Sudah diupdate di semua layout files:

-   `<livewire:scripts />` → `@livewireScripts`
-   `<livewire:styles />` → `@livewireStyles`

### 2. File-file Yang Sudah Diupdate

-   [x] `resources/views/layouts/app.blade.php`
-   [x] `resources/views/layouts/base.blade.php`
-   [x] `resources/views/layouts/guest.blade.php`
-   [x] `resources/views/components/admin-layout.blade.php`
-   [x] `resources/views/components/partials/head.blade.php`

## Perubahan Yang Perlu Dilakukan Manual

### 1. Wire Model Modifiers

Cari dan ganti di seluruh views:

```bash
# PowerShell command untuk mencari wire:model.defer
Get-ChildItem -Path "resources\views" -Filter "*.blade.php" -Recurse | Select-String "wire:model.defer" | Select-Object Path, LineNumber, Line
```

**Perubahan:**

-   `wire:model.defer` → `wire:model.blur` atau `wire:model.live.debounce.500ms`
-   `wire:model.lazy` → `wire:model.blur`
-   `wire:model` (default) → `wire:model.live` (jika ingin real-time)

### 2. Event Emitting (di PHP)

**Livewire 2:**

```php
$this->emit('eventName', $data);
$this->emitTo('component-name', 'eventName', $data);
$this->emitSelf('eventName', $data);
$this->emitUp('eventName', $data);
```

**Livewire 3:**

```php
$this->dispatch('eventName', data: $data);
$this->dispatch('eventName')->to('component-name');
$this->dispatch('eventName')->self();
$this->dispatch('eventName')->up();
```

### 3. Event Listening (di Blade)

Tidak ada perubahan untuk event di Blade:

```blade
<!-- Tetap sama -->
<div wire:click="$emit('eventName')">Click</div>
<div wire:click="$dispatch('eventName')">Click</div>
```

### 4. File Uploads

File uploads masih menggunakan `WithFileUploads` trait - tidak ada perubahan.

### 5. Pagination

Tidak ada perubahan signifikan, tapi pastikan views menggunakan Livewire pagination:

```php
return view('livewire.component', [
    'items' => Model::paginate(10)
]);
```

### 6. Query String

**Livewire 2:**

```php
protected $queryString = ['search'];
```

**Livewire 3 (opsional, bisa tetap pakai cara lama):**

```php
use Livewire\Attributes\Url;

#[Url]
public $search = '';
```

## Commands untuk Testing

```bash
# Cari wire:model.defer
Get-ChildItem -Path "resources\views" -Recurse -Include "*.blade.php" | Select-String "wire:model\.defer"

# Cari wire:model.lazy
Get-ChildItem -Path "resources\views" -Recurse -Include "*.blade.php" | Select-String "wire:model\.lazy"

# Cari $this->emit
Get-ChildItem -Path "app\Http\Livewire" -Recurse -Include "*.php" | Select-String "\$this->emit"

# Cari $this->emitTo
Get-ChildItem -Path "app\Http\Livewire" -Recurse -Include "*.php" | Select-String "\$this->emitTo"

# Cari $this->emitSelf
Get-ChildItem -Path "app\Http\Livewire" -Recurse -Include "*.php" | Select-String "\$this->emitSelf"

# Cari $this->emitUp
Get-ChildItem -Path "app\Http\Livewire" -Recurse -Include "*.php" | Select-String "\$this->emitUp"

# Cari protected $listeners
Get-ChildItem -Path "app\Http\Livewire" -Recurse -Include "*.php" | Select-String "protected \$listeners"
```

## Components Yang Ada

Berdasarkan scan, Livewire components di project:

-   `app/Http/Livewire/LikeThread.php`
-   `app/Http/Livewire/LikeReply.php`
-   `app/Http/Livewire/Thread/Delete.php`
-   `app/Http/Livewire/Thread/RepliesList.php`
-   `app/Http/Livewire/Thread/ReplyForm.php`
-   `app/Http/Livewire/Reply/Delete.php`
-   `app/Http/Livewire/Reply/Update.php`
-   `app/Http/Livewire/Notifications/Count.php`
-   `app/Http/Livewire/Notifications/Index.php`
-   `app/Http/Livewire/Notifications/Indicator.php`

## Breaking Changes Yang Perlu Diperhatikan

### 1. Component Namespace

Livewire 3 merekomendasikan pindah dari `app/Http/Livewire` ke `app/Livewire`, tapi tidak wajib.

### 2. Protected Properties

Semua public properties masih bekerja sama.

### 3. Mount Method

Tidak ada perubahan.

### 4. Rules

Validation rules tetap sama.

## Testing Checklist

Setelah install dependencies, test:

-   [ ] Like/Unlike thread
-   [ ] Like/Unlike reply
-   [ ] Create reply dengan upload gambar
-   [ ] Delete thread
-   [ ] Delete reply
-   [ ] Update reply
-   [ ] Notifications count
-   [ ] Notifications list
-   [ ] Real-time updates

## Resources

-   [Livewire 3 Upgrade Guide](https://livewire.laravel.com/docs/upgrading)
-   [Livewire 3 Documentation](https://livewire.laravel.com/docs)
