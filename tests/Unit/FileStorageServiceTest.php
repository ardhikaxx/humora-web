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
        $this->service = new FileStorageService;
    }

    public function test_image_is_compressed_and_stored_as_webp(): void
    {
        // Create a real PNG image in memory
        $tempImg = imagecreatetruecolor(200, 200);
        $bg = imagecolorallocate($tempImg, 255, 230, 0);
        imagefill($tempImg, 0, 0, $bg);
        $tempPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'test_upload_'.uniqid().'.png';
        imagepng($tempImg, $tempPath);
        imagedestroy($tempImg);

        $uploadedFile = new UploadedFile($tempPath, 'test_thumb.png', 'image/png', null, true);

        $storedName = $this->service->storeUploadedImageAsWebp($uploadedFile, 'thumbnails', 'test_thumb', 80);

        $this->assertStringEndsWith('.webp', $storedName);
        $storedPath = FileStorageService::getUploadPath('thumbnails').DIRECTORY_SEPARATOR.$storedName;
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

    public function test_ffprobe_path_is_detected(): void
    {
        $ffprobe = FileStorageService::getFfprobePath();
        $this->assertNotEmpty($ffprobe);
    }

    public function test_video_duration_is_extracted_accurately(): void
    {
        $ffmpeg = FileStorageService::getFfmpegPath();
        $this->assertNotEmpty($ffmpeg);

        // Generate a 3-second test video
        $tempVideo = sys_get_temp_dir().DIRECTORY_SEPARATOR.'test_dur_'.uniqid().'.mp4';
        $cmd = sprintf(
            '"%s" -y -f lavfi -i testsrc=duration=3:size=320x180:rate=15 -f lavfi -i sine=frequency=440:duration=3 -c:v libx264 -c:a aac %s 2>&1',
            $ffmpeg,
            escapeshellarg($tempVideo)
        );
        $out = [];
        $ret = 0;
        @exec($cmd, $out, $ret);

        if ($ret === 0 && file_exists($tempVideo)) {
            $duration = FileStorageService::getVideoDurationSeconds($tempVideo);
            $this->assertSame(3, $duration);
            @unlink($tempVideo);
        }
    }

    public function test_video_is_compressed_and_stored_as_webm(): void
    {
        $ffmpeg = FileStorageService::getFfmpegPath();
        $this->assertNotEmpty($ffmpeg);

        $tempVideo = sys_get_temp_dir().DIRECTORY_SEPARATOR.'test_vid_'.uniqid().'.mp4';
        $cmd = sprintf(
            '"%s" -y -f lavfi -i testsrc=duration=1:size=320x180:rate=15 -f lavfi -i sine=frequency=440:duration=1 -c:v libx264 -c:a aac %s 2>&1',
            $ffmpeg,
            escapeshellarg($tempVideo)
        );
        $out = [];
        $ret = 0;
        @exec($cmd, $out, $ret);

        if ($ret === 0 && file_exists($tempVideo)) {
            $uploaded = new UploadedFile($tempVideo, 'sample.mp4', 'video/mp4', null, true);
            $storedName = $this->service->storeUploadedVideoAsWebm($uploaded, 'videos', 'test_vid_unit');

            $this->assertStringEndsWith('.webm', $storedName);
            $storedPath = FileStorageService::getUploadPath('videos').DIRECTORY_SEPARATOR.$storedName;
            $this->assertFileExists($storedPath);
            $this->assertGreaterThan(0, filesize($storedPath));

            // Clean up
            $this->service->deleteFile('videos', $storedName);
            if (file_exists($tempVideo)) {
                @unlink($tempVideo);
            }
        }
    }
}
