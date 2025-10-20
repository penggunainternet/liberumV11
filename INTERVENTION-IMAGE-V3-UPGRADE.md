# Intervention Image v3 Upgrade

## Status: ✅ COMPLETED

## Changes Made

### File Updated: `app/Traits/HasMedia.php`

**Old (Intervention Image v2):**

```php
use Intervention\Image\Facades\Image;

// Make image
$image = Image::make($file);

// Resize
$image->resize(1920, 1080, function ($constraint) {
    $constraint->aspectRatio();
    $constraint->upsize();
});

// Encode
$image->encode('jpg', 85);

// Save
$image->save($fullPath);
```

**New (Intervention Image v3):**

```php
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

// Initialize manager
$manager = new ImageManager(new Driver());

// Read image
$image = $manager->read($file->getPathname());

// Resize (auto aspect ratio and no upscaling)
$image->scale(width: 1920, height: 1080);

// Save with format conversion
$image->toJpeg(85)->save($fullPath);
// or
$image->toPng()->save($fullPath);
// or
$image->toWebp(80)->save($fullPath);
```

## Key API Changes

| v2 API                       | v3 API                           | Notes                      |
| ---------------------------- | -------------------------------- | -------------------------- |
| `Image::make()`              | `$manager->read()`               | No more Facade             |
| `->resize($w, $h, callback)` | `->scale(width: $w, height: $h)` | Auto aspect ratio          |
| `->encode('jpg', 85)`        | `->toJpeg(85)`                   | Format-specific methods    |
| `->save($path)`              | `->toJpeg()->save($path)`        | Explicit format conversion |
| `clone $image`               | `$manager->read($path)`          | Re-read instead of clone   |

## Testing Checklist

### 📸 Upload Image Tests

-   [ ] **Thread Creation with Image**

    -   Go to: `/threads/create`
    -   Upload: JPG/JPEG image
    -   Expected: Thread created, image optimized to max 1920x1080, thumbnail created

-   [ ] **Reply with Image**

    -   Go to any thread
    -   Add reply with image
    -   Expected: Image uploaded, optimized, and displayed

-   [ ] **Profile Photo Upload**
    -   Go to: `/user/profile`
    -   Upload profile photo
    -   Expected: Photo uploaded and resized

### 🖼️ Image Format Tests

Test with different formats:

-   [ ] **JPEG** (.jpg, .jpeg) - Should save as JPEG with 85% quality
-   [ ] **PNG** (.png) - Should save as PNG
-   [ ] **WebP** (.webp) - Should save as WebP with 80% quality

### 📐 Image Size Tests

-   [ ] **Small image** (< 1920x1080) - Should NOT be upscaled
-   [ ] **Large image** (> 1920x1080) - Should be downscaled to max 1920x1080
-   [ ] **Portrait image** (tall) - Should maintain aspect ratio
-   [ ] **Landscape image** (wide) - Should maintain aspect ratio

### 🔍 Thumbnail Tests

-   [ ] Thumbnail created at 300x300 max
-   [ ] Thumbnail maintains aspect ratio
-   [ ] Thumbnail saved as JPEG with 80% quality

## Locations That Use Image Upload

1. **Thread Creation**

    - File: `app/Jobs/CreateThread.php`
    - Uses: `HasMedia` trait
    - Collection: `threads`

2. **Reply Creation**

    - File: `app/Jobs/CreateReply.php`
    - Uses: `HasMedia` trait
    - Collection: `replies`

3. **Profile Photo**
    - File: `app/Actions/Fortify/UpdateUserProfileInformation.php`
    - May use different method

## Storage Paths

-   **Original Images**: `storage/app/public/{collection}/{random_filename}`
-   **Thumbnails**: `storage/app/public/{collection}/thumbnails/{random_filename}`

## Error Handling

If you encounter errors:

1. **"Class 'GD' not found"**

    - Install GD extension: `php -m | grep -i gd`
    - Or use Imagick driver: `use Intervention\Image\Drivers\Imagick\Driver;`

2. **"Cannot read image"**

    - Check file permissions
    - Verify file path is accessible

3. **"Memory limit exceeded"**
    - Increase PHP memory_limit in php.ini
    - Or process smaller images

## Performance Notes

-   ✅ Images are automatically optimized during upload
-   ✅ Thumbnails are created asynchronously
-   ✅ Compression quality: JPEG 85%, WebP 80%, Thumbnail 80%
-   ✅ Max dimensions: 1920x1080 (Full HD)
-   ✅ Thumbnail dimensions: 300x300

## Breaking Changes

None! The trait interface remains the same:

```php
$model->addMedia($uploadedFile, 'collection-name');
```

Internal implementation changed, but public API is backward compatible.
