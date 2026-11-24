<?php

namespace Tests\Feature;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    public function test_stores_a_resized_jpeg_image_on_the_public_disk(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('produk.jpg', width: 2400, height: 1200);

        $path = app(ImageService::class)->store($file, 'produk', 'foto');

        Storage::disk('public')->assertExists($path);
        $imageInfo = getimagesizefromstring(Storage::disk('public')->get($path));

        $this->assertSame(1920, $imageInfo[0]);
        $this->assertSame(960, $imageInfo[1]);
        $this->assertSame(IMAGETYPE_JPEG, $imageInfo[2]);
    }
}
