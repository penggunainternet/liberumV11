# Script Install Dependencies Laravel 11

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Laravel 11 Upgrade - Install Script  " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Backup
Write-Host "[1/8] Creating backup..." -ForegroundColor Yellow
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupDir = "backup_$timestamp"

if (Test-Path "vendor") {
    Write-Host "  - Backing up vendor folder..." -ForegroundColor Gray
}
if (Test-Path "node_modules") {
    Write-Host "  - Backing up node_modules folder..." -ForegroundColor Gray
}

# 2. Clean old dependencies
Write-Host "`n[2/8] Cleaning old dependencies..." -ForegroundColor Yellow
if (Test-Path "vendor") {
    Remove-Item -Path "vendor" -Recurse -Force
    Write-Host "  - vendor folder removed" -ForegroundColor Green
}
if (Test-Path "composer.lock") {
    Remove-Item -Path "composer.lock" -Force
    Write-Host "  - composer.lock removed" -ForegroundColor Green
}
if (Test-Path "node_modules") {
    Remove-Item -Path "node_modules" -Recurse -Force
    Write-Host "  - node_modules folder removed" -ForegroundColor Green
}
if (Test-Path "package-lock.json") {
    Remove-Item -Path "package-lock.json" -Force
    Write-Host "  - package-lock.json removed" -ForegroundColor Green
}

# 3. Install Composer dependencies
Write-Host "`n[3/8] Installing Composer dependencies..." -ForegroundColor Yellow
Write-Host "  This may take several minutes..." -ForegroundColor Gray
composer install --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Host "  - Composer dependencies installed successfully" -ForegroundColor Green
} else {
    Write-Host "  - ERROR: Composer install failed!" -ForegroundColor Red
    exit 1
}

# 4. Install NPM dependencies
Write-Host "`n[4/8] Installing NPM dependencies..." -ForegroundColor Yellow
Write-Host "  This may take several minutes..." -ForegroundColor Gray
npm install
if ($LASTEXITCODE -eq 0) {
    Write-Host "  - NPM dependencies installed successfully" -ForegroundColor Green
} else {
    Write-Host "  - ERROR: NPM install failed!" -ForegroundColor Red
    exit 1
}

# 5. Clear caches
Write-Host "`n[5/8] Clearing Laravel caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
Write-Host "  - All caches cleared" -ForegroundColor Green

# 6. Optimize autoloader
Write-Host "`n[6/8] Optimizing autoloader..." -ForegroundColor Yellow
composer dump-autoload
php artisan optimize:clear
Write-Host "  - Autoloader optimized" -ForegroundColor Green

# 7. Run migrations (optional)
Write-Host "`n[7/8] Database migrations..." -ForegroundColor Yellow
$runMigrations = Read-Host "  Do you want to run migrations? (y/N)"
if ($runMigrations -eq "y" -or $runMigrations -eq "Y") {
    php artisan migrate
    Write-Host "  - Migrations completed" -ForegroundColor Green
} else {
    Write-Host "  - Migrations skipped" -ForegroundColor Gray
}

# 8. Build assets
Write-Host "`n[8/8] Building frontend assets..." -ForegroundColor Yellow
$buildMode = Read-Host "  Build mode? (dev/prod) [dev]"
if ($buildMode -eq "prod") {
    npm run build
    Write-Host "  - Production build completed" -ForegroundColor Green
} else {
    Write-Host "  - Skipping build. Run 'npm run dev' when ready" -ForegroundColor Gray
}

# Done
Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  Installation Complete!                 " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "  1. Run 'npm run dev' to start Vite dev server" -ForegroundColor White
Write-Host "  2. Run 'php artisan serve' in another terminal" -ForegroundColor White
Write-Host "  3. Test your application thoroughly" -ForegroundColor White
Write-Host "  4. Check UPGRADE-GUIDE.md for manual changes needed" -ForegroundColor White
Write-Host "  5. Check LIVEWIRE-MIGRATION.md for Livewire 3 updates" -ForegroundColor White
Write-Host ""
Write-Host "Important:" -ForegroundColor Red
Write-Host "  - Livewire emit() has been updated to dispatch()" -ForegroundColor White
Write-Host "  - wire:model.defer/lazy updated to wire:model.blur" -ForegroundColor White
Write-Host "  - Check all Jetstream views for compatibility" -ForegroundColor White
Write-Host ""
