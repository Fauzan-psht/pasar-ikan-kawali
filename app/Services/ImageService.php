<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class ImageService
{
    private const MAX_DIMENSION = 1920;

    private const MAX_SOURCE_PIXELS = 20000000;

    public function store(UploadedFile $file, string $directory, string $field): string
    {
        $contents = file_get_contents($file->getRealPath());

        if ($contents === false) {
            throw new RuntimeException('Gagal membaca file gambar yang diunggah.');
        }

        $imageInfo = getimagesizefromstring($contents);

        if ($imageInfo === false || ! in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            throw ValidationException::withMessages([
                $field => 'Gambar harus berformat JPG, PNG, atau WEBP.',
            ]);
        }

        [$width, $height, $imageType] = $imageInfo;

        if ($width < 1 || $height < 1 || $width * $height > self::MAX_SOURCE_PIXELS) {
            throw ValidationException::withMessages([
                $field => 'Resolusi gambar terlalu besar.',
            ]);
        }

        $source = imagecreatefromstring($contents);

        if ($source === false) {
            throw ValidationException::withMessages([
                $field => 'File gambar tidak dapat diproses.',
            ]);
        }

        $scale = min(1, self::MAX_DIMENSION / $width, self::MAX_DIMENSION / $height);
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));
        $target = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($target === false) {
            imagedestroy($source);

            throw new RuntimeException('Gagal menyiapkan gambar untuk kompresi.');
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'pasar-image-');

        if ($temporaryPath === false) {
            imagedestroy($source);
            imagedestroy($target);

            throw new RuntimeException('Gagal menyiapkan file sementara untuk gambar.');
        }

        try {
            if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_WEBP) {
                imagealphablending($target, false);
                imagesavealpha($target, true);
                $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
                imagefill($target, 0, 0, $transparent);
            } else {
                $background = imagecolorallocate($target, 255, 255, 255);
                imagefill($target, 0, 0, $background);
            }

            if (! imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height)) {
                throw new RuntimeException('Gagal mengubah ukuran gambar.');
            }

            $extension = match ($imageType) {
                IMAGETYPE_JPEG => 'jpg',
                IMAGETYPE_PNG => 'png',
                IMAGETYPE_WEBP => 'webp',
            };

            $saved = match ($imageType) {
                IMAGETYPE_JPEG => imagejpeg($target, $temporaryPath, 82),
                IMAGETYPE_PNG => imagepng($target, $temporaryPath, 6),
                IMAGETYPE_WEBP => imagewebp($target, $temporaryPath, 82),
            };

            if (! $saved) {
                throw new RuntimeException('Gagal mengompres gambar.');
            }

            $compressedContents = file_get_contents($temporaryPath);

            if ($compressedContents === false) {
                throw new RuntimeException('Gagal membaca hasil kompresi gambar.');
            }

            $path = trim($directory, '/').'/'.Str::uuid().'.'.$extension;

            if (! Storage::disk('public')->put($path, $compressedContents)) {
                throw new RuntimeException('Gagal menyimpan gambar yang sudah dikompres.');
            }

            return $path;
        } finally {
            imagedestroy($source);
            imagedestroy($target);
            unlink($temporaryPath);
        }
    }
}
