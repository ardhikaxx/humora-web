<?php

namespace Tests\Unit;

use App\Services\FileStorageService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileStorageServiceTest extends TestCase
{
    protected FileStorageService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FileStorageService();
    }

    public function test_image_is_compressed_and_stored_as_webp(): void
    {
        // Create a real PNG image in memory
        $tempImg = imagecreatetruecolor(200, 200);
        $bg = imagecolorallocate($tempImg, 255, 230, 0);
        imagefill($tempImg, 0, 0, $bg);
        $tempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test_upload_' . uniqid() . '.png';
        imagepng($tempImg, $tempPath);
        imagedestroy($tempImg);

        $uploadedFile = new UploadedFile($tempPath, 'test_thumb.png', 'image/png', null, true);

        $storedName = $this->service->storeUploadedImageAsWebp($uploadedFile, 'thumbnails', 'test_thumb', 80);

        $this->assertStringEndsWith('.webp', $storedName);
        $storedPath = FileStorageService::getUploadPath('thumbnails') . DIRECTORY_SEPARATOR . $storedName;
        $this->assertFileExists($storedPath);

        // Verify stored file is a valid WebP image
        $info = @getimagesize($storedPath);
        $this->assertNotEmpty($info);
        $this->assertSame('image/webp', $info['mime']);

        // Clean up
        $this->service->deleteFile('thumbnails', $storedName);
        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }
    }

    public function test_ffmpeg_path_is_detected(): void
    {
        $ffmpeg = FileStorageService::getFfmpegPath();
        $this->assertNotEmpty($ffmpeg);
    }
}
