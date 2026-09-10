<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Re-encode (hancurkan payload tersembunyi), resize, simpan WebP + thumbnail.
     * @return array{path: string, thumb_path: string}
     */
    public function storePhoto(UploadedFile $file, string $dir, int $maxWidth = 1600, int $quality = 82): array
    {
        $folder = trim($dir, '/').'/'.now()->format('Y/m');
        $name = Str::random(24);
        \Storage::disk('public')->makeDirectory($folder);

        $image = $this->manager->decode($file->getRealPath());
        $image->scaleDown(width: $maxWidth);
        $path = $folder.'/'.$name.'.webp';
        $image->encode(new WebpEncoder(quality: $quality))->save(storage_path('app/public/'.$path));

        $thumb = $this->manager->decode($file->getRealPath());
        $thumb->coverDown(width: 480, height: 360);
        $thumbPath = $folder.'/'.$name.'-thumb.webp';
        $thumb->encode(new WebpEncoder(quality: 75))->save(storage_path('app/public/'.$thumbPath));

        return ['path' => $path, 'thumb_path' => $thumbPath];
    }

    public function delete(?string ...$paths): void
    {
        foreach ($paths as $path) {
            if ($path && \Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
            }
        }
    }
}
