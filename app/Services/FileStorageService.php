<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

class FileStorageService
{
    protected static array $allowedFolders = [
        'thumbnails',
        'videos',
        'proofs',
        'system',
    ];

    /**
     * Get root upload path
     */
    public static function getUploadPath(?string $subfolder = null): string
    {
        $base = storage_path('uploads');
        if (!File::isDirectory($base)) {
            File::makeDirectory($base, 0755, true);
        }

        if ($subfolder) {
            $folder = self::sanitizeFolder($subfolder);
            $full = $base . DIRECTORY_SEPARATOR . $folder;
            if (!File::isDirectory($full)) {
                File::makeDirectory($full, 0755, true);
            }
            return $full;
        }

        return $base;
    }

    /**
     * Ensure subfolder is strictly whitelisted
     */
    public static function sanitizeFolder(string $folder): string
    {
        $clean = strtolower(trim($folder));
        if (!in_array($clean, self::$allowedFolders, true)) {
            throw new \InvalidArgumentException("Akses ke folder penyimpanan tidak diizinkan: {$folder}");
        }
        return $clean;
    }

    /**
     * Sanitize filename to prevent directory traversal
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Remove null bytes and directory separators
        $cleaned = str_replace(["\0", '/', '\\', '..'], '', $filename);
        $base = basename($cleaned);
        if (empty($base) || $base !== $filename) {
            throw new \InvalidArgumentException("Nama file tidak valid atau mengandung path traversal.");
        }
        return $base;
    }

    /**
     * Store uploaded file securely with randomized name
     */
    public function storeUploadedFile(
        UploadedFile $file,
        string $subfolder,
        string $prefix = 'file',
        array $allowedExtensions = ['jpg', 'jpeg', 'png', 'mp4'],
        int $maxSizeBytes = 104857600 // 100MB
    ): string {
        $folder = self::sanitizeFolder($subfolder);
        $targetDir = self::getUploadPath($folder);

        if ($file->getSize() > $maxSizeBytes) {
            throw new \RuntimeException('Ukuran file melebihi batas maksimum yang diizinkan.');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions, true)) {
            throw new \RuntimeException("Format file .{$extension} tidak diizinkan.");
        }

        $uniqueName = sprintf(
            '%s_%s_%s.%s',
            $prefix,
            date('Ymd_His'),
            Str::random(12),
            $extension
        );

        $file->move($targetDir, $uniqueName);

        return $uniqueName;
    }

    /**
     * Locate system FFmpeg executable
     */
    public static function getFfmpegPath(): ?string
    {
        $knownPaths = [
            'C:\\Users\\LENOVO\\AppData\\Local\\Microsoft\\WinGet\\Links\\ffmpeg.exe',
            'C:\\ffmpeg\\bin\\ffmpeg.exe',
            'C:\\Program Files\\ffmpeg\\bin\\ffmpeg.exe',
        ];

        foreach ($knownPaths as $p) {
            if (file_exists($p)) {
                return $p;
            }
        }

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $testCommand = $isWindows ? 'where ffmpeg 2>nul' : 'which ffmpeg 2>/dev/null';
        $output = @shell_exec($testCommand);
        if ($output) {
            $lines = explode("\n", trim($output));
            if (!empty($lines[0])) {
                return trim($lines[0]);
            }
        }

        return 'ffmpeg';
    }

    /**
     * Locate system ffprobe executable
     */
    public static function getFfprobePath(): ?string
    {
        $knownPaths = [
            'C:\\Users\\LENOVO\\AppData\\Local\\Microsoft\\WinGet\\Links\\ffprobe.exe',
            'C:\\ffmpeg\\bin\\ffprobe.exe',
            'C:\\Program Files\\ffmpeg\\bin\\ffprobe.exe',
        ];

        foreach ($knownPaths as $p) {
            if (file_exists($p)) {
                return $p;
            }
        }

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $testCommand = $isWindows ? 'where ffprobe 2>nul' : 'which ffprobe 2>/dev/null';
        $output = @shell_exec($testCommand);
        if ($output) {
            $lines = explode("\n", trim($output));
            if (!empty($lines[0])) {
                return trim($lines[0]);
            }
        }

        return 'ffprobe';
    }

    /**
     * Extract video duration in total integer seconds via ffprobe
     */
    public static function getVideoDurationSeconds(string $filePath): ?int
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $ffprobe = self::getFfprobePath();
        if (!$ffprobe) {
            return null;
        }

        try {
            $cmd = sprintf(
                '"%s" -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s 2>&1',
                $ffprobe,
                escapeshellarg($filePath)
            );
            $output = @shell_exec($cmd);
            if ($output !== null && is_numeric(trim($output))) {
                $seconds = (float) trim($output);
                return (int) round($seconds);
            }
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        return null;
    }

    /**
     * Store and compress uploaded image, converting directly to high-efficiency WebP format (.webp)
     */
    public function storeUploadedImageAsWebp(
        UploadedFile $file,
        string $subfolder = 'thumbnails',
        string $prefix = 'thumb',
        int $quality = 82,
        int $maxSizeBytes = 10485760 // 10MB
    ): string {
        $folder = self::sanitizeFolder($subfolder);
        $targetDir = self::getUploadPath($folder);

        if ($file->getSize() > $maxSizeBytes) {
            throw new \RuntimeException('Ukuran berkas gambar melebihi batas maksimum 10MB.');
        }

        $sourceExt = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'bmp', 'gif'];

        if (!in_array($sourceExt, $allowedExtensions, true)) {
            throw new \RuntimeException("Format gambar .{$sourceExt} tidak didukung. Harap gunakan format JPG, PNG, atau WEBP.");
        }

        $uniqueName = sprintf(
            '%s_%s_%s.webp',
            $prefix,
            date('Ymd_His'),
            Str::random(12)
        );
        $targetFilePath = $targetDir . DIRECTORY_SEPARATOR . $uniqueName;
        $sourcePath = $file->getRealPath();

        // Convert & compress via GD if available
        if (function_exists('imagewebp') && file_exists($sourcePath)) {
            $image = match ($sourceExt) {
                'jpg', 'jpeg' => @imagecreatefromjpeg($sourcePath),
                'png' => @imagecreatefrompng($sourcePath),
                'webp' => @imagecreatefromwebp($sourcePath),
                'gif' => @imagecreatefromgif($sourcePath),
                'bmp' => @imagecreatefrombmp($sourcePath),
                default => null,
            };

            if ($image !== false && $image !== null) {
                // Preserve transparency
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);

                $saved = @imagewebp($image, $targetFilePath, $quality);
                imagedestroy($image);

                if ($saved && file_exists($targetFilePath) && filesize($targetFilePath) > 0) {
                    return $uniqueName;
                }
            }
        }

        // If input is already webp and conversion was skipped, move directly
        if ($sourceExt === 'webp') {
            $file->move($targetDir, $uniqueName);
            return $uniqueName;
        }

        // Fallback to standard storage if conversion unavailable
        return $this->storeUploadedFile($file, $subfolder, $prefix, $allowedExtensions, $maxSizeBytes);
    }

    /**
     * Store and compress uploaded video, converting to high-efficiency WebM format (.webm)
     * strictly maintaining original video resolution without downscaling
     */
    public function storeUploadedVideoAsWebm(
        UploadedFile $file,
        string $subfolder = 'videos',
        string $prefix = 'vid',
        int $maxSizeBytes = 104857600 // 100MB
    ): string {
        $folder = self::sanitizeFolder($subfolder);
        $targetDir = self::getUploadPath($folder);

        if ($file->getSize() > $maxSizeBytes) {
            throw new \RuntimeException('Ukuran berkas video melebihi batas maksimum 100MB.');
        }

        $sourceExt = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['mp4', 'webm', 'mov', 'mkv', 'avi', 'm4v'];

        if (!in_array($sourceExt, $allowedExtensions, true)) {
            throw new \RuntimeException("Format video .{$sourceExt} tidak didukung. Harap gunakan format MP4, WEBM, atau MOV.");
        }

        $uniqueName = sprintf(
            '%s_%s_%s.webm',
            $prefix,
            date('Ymd_His'),
            Str::random(12)
        );
        $targetFilePath = $targetDir . DIRECTORY_SEPARATOR . $uniqueName;
        $sourcePath = $file->getRealPath();

        $ffmpeg = self::getFfmpegPath();

        if ($ffmpeg) {
            // Compress and convert to WebM with VP9 & Opus
            // Strictly preserves original resolution while achieving high-efficiency compression
            // -crf 32 with -b:v 0 provides optimal visual quality compression (~8-15MB for a 2m skit)
            // -row-mt 1 and -deadline good -cpu-used 4 enables fast multi-threaded processing
            // -map 0:v:0 -map 0:a? safely maps video and optional audio (prevents errors on silent clips)
            $cmd = sprintf(
                '"%s" -y -i %s -map 0:v:0 -map 0:a? -c:v libvpx-vp9 -crf 32 -b:v 0 -row-mt 1 -deadline good -cpu-used 4 -c:a libopus -b:a 96k %s 2>&1',
                $ffmpeg,
                escapeshellarg($sourcePath),
                escapeshellarg($targetFilePath)
            );

            $output = [];
            $returnCode = 0;
            @exec($cmd, $output, $returnCode);

            if ($returnCode === 0 && file_exists($targetFilePath) && filesize($targetFilePath) > 0) {
                return $uniqueName;
            }
        }

        // Fallback: If input is already webm, move directly
        if ($sourceExt === 'webm') {
            $file->move($targetDir, $uniqueName);
            return $uniqueName;
        }

        // Fallback to standard storage if conversion fails
        return $this->storeUploadedFile($file, $subfolder, $prefix, $allowedExtensions, $maxSizeBytes);
    }

    /**
     * Delete file securely
     */
    public function deleteFile(string $subfolder, ?string $filename): bool
    {
        if (empty($filename)) {
            return false;
        }

        try {
            $folder = self::sanitizeFolder($subfolder);
            $cleanName = self::sanitizeFilename($filename);
            $filePath = self::getUploadPath($folder) . DIRECTORY_SEPARATOR . $cleanName;

            if (File::exists($filePath)) {
                return File::delete($filePath);
            }
        } catch (\Throwable $e) {
            // Log or ignore safely
        }

        return false;
    }

    /**
     * Stream file with full HTTP Range support for video players
     */
    public function streamFileResponse(string $subfolder, string $filename, ?string $downloadName = null): Response
    {
        $folder = self::sanitizeFolder($subfolder);
        $cleanName = self::sanitizeFilename($filename);
        $filePath = self::getUploadPath($folder) . DIRECTORY_SEPARATOR . $cleanName;

        if (!File::exists($filePath)) {
            abort(404, 'File tidak ditemukan di penyimpanan server.');
        }

        $fileSize = filesize($filePath);
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = match ($ext) {
            'webm' => 'video/webm',
            'mp4' => 'video/mp4',
            'webp' => 'image/webp',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => File::mimeType($filePath) ?: 'application/octet-stream',
        };

        $headers = [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=604800, immutable',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => 'Range, Authorization, Content-Type, Accept, X-Requested-With',
            'Access-Control-Expose-Headers' => 'Content-Range, Content-Length, Accept-Ranges, Content-Type',
        ];

        if ($downloadName) {
            $headers['Content-Disposition'] = 'inline; filename="' . addslashes($downloadName) . '"';
        }

        $response = response()->file($filePath, $headers);
        $response->prepare(request());
        return $response;
    }
}
