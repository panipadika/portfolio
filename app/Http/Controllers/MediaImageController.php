<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaImageController extends Controller
{
    /**
     * Allowed target output widths to prevent cache flooding.
     *
     * @var array<int, int>
     */
    private const ALLOWED_WIDTHS = [128, 640, 960, 1280];

    /**
     * Serve resized, cached WebP versions of local portfolio images with immutable cache headers.
     */
    public function __invoke(Request $request, string $width, string $path): BinaryFileResponse
    {
        $targetWidth = (int) $width;
        if (! in_array($targetWidth, self::ALLOWED_WIDTHS, true)) {
            abort(404);
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');
        if (
            $normalized === ''
            || str_contains($normalized, '..')
            || str_contains($normalized, "\0")
            || str_starts_with(basename($normalized), '.')
        ) {
            abort(404);
        }

        $sourcePath = $this->resolveAllowedSourcePath($normalized);
        if ($sourcePath === null) {
            abort(404);
        }

        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        if (! in_array($extension, ['png', 'jpg', 'jpeg', 'webp'], true)) {
            abort(404);
        }

        $cacheHeaders = [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ];

        if (! function_exists('imagewebp')) {
            return response()->file($sourcePath, $cacheHeaders);
        }

        $cachePath = $this->getOrBuildWebpCache($sourcePath, $extension, $targetWidth);
        if ($cachePath === null || ! is_file($cachePath)) {
            return response()->file($sourcePath, $cacheHeaders);
        }

        return response()->file($cachePath, [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    /**
     * Resolve and strictly confine the path to either public storage or whitelisted public image directories.
     */
    private function resolveAllowedSourcePath(string $normalized): ?string
    {
        if (str_starts_with($normalized, 'storage/')) {
            $relativeStorage = substr($normalized, strlen('storage/'));
            $disk = Storage::disk('public');
            $root = realpath($disk->path(''));
            $candidate = realpath($disk->path($relativeStorage));

            if (
                $root === false
                || $candidate === false
                || ! str_starts_with($candidate, $root.DIRECTORY_SEPARATOR)
                || ! is_file($candidate)
            ) {
                return null;
            }

            return $candidate;
        }

        $allowedPublicExact = [
            'logo-pp.png',
            'profile-transparent.png',
            'card-front.webp',
            'card-back.webp',
        ];

        $isWhitelistedPublic = str_starts_with($normalized, 'assets/images/')
            || in_array($normalized, $allowedPublicExact, true);

        if (! $isWhitelistedPublic) {
            return null;
        }

        $publicRoot = realpath(public_path());
        $candidate = realpath(public_path($normalized));

        if (
            $publicRoot === false
            || $candidate === false
            || ! str_starts_with($candidate, $publicRoot.DIRECTORY_SEPARATOR)
            || ! is_file($candidate)
        ) {
            return null;
        }

        return $candidate;
    }

    /**
     * Resize to target width without upscaling and cache as WebP on disk.
     */
    private function getOrBuildWebpCache(string $sourcePath, string $extension, int $targetWidth): ?string
    {
        $sourceMtime = @filemtime($sourcePath) ?: 0;
        $cacheDir = storage_path('framework/media-cache/'.$targetWidth);

        if (! is_dir($cacheDir) && ! @mkdir($cacheDir, 0755, true) && ! is_dir($cacheDir)) {
            return null;
        }

        $cacheFile = $cacheDir.'/'.hash('sha256', $sourcePath.'|'.$sourceMtime).'.webp';
        if (is_file($cacheFile)) {
            return $cacheFile;
        }

        $srcImage = $this->createImageResource($sourcePath, $extension);
        if ($srcImage === null) {
            return null;
        }

        $srcW = imagesx($srcImage);
        $srcH = imagesy($srcImage);
        if ($srcW <= 0 || $srcH <= 0) {
            unset($srcImage);

            return null;
        }

        $outW = min($srcW, $targetWidth);
        $outH = max(1, (int) round(($srcH / $srcW) * $outW));

        $dstImage = imagecreatetruecolor($outW, $outH);
        if ($dstImage === false) {
            unset($srcImage);

            return null;
        }

        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 0, 0, 0, 127);
        imagefilledrectangle($dstImage, 0, 0, $outW, $outH, $transparent);

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $outW, $outH, $srcW, $srcH);
        unset($srcImage);

        $tempFile = $cacheFile.'.tmp_'.uniqid('', true);
        $quality = $targetWidth <= 128 ? 85 : 80;
        $written = @imagewebp($dstImage, $tempFile, $quality);
        unset($dstImage);

        if (! $written || ! is_file($tempFile)) {
            @unlink($tempFile);

            return null;
        }

        if (! @rename($tempFile, $cacheFile)) {
            @unlink($tempFile);

            return is_file($cacheFile) ? $cacheFile : null;
        }

        return $cacheFile;
    }

    /**
     * Load GD image resource across PNG/JPEG/WebP, respecting EXIF orientation on JPEGs.
     */
    private function createImageResource(string $sourcePath, string $extension): ?\GdImage
    {
        $resource = match ($extension) {
            'png' => @imagecreatefrompng($sourcePath),
            'jpg', 'jpeg' => @imagecreatefromjpeg($sourcePath),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            default => false,
        };

        if (! $resource instanceof \GdImage) {
            return null;
        }

        if ($extension === 'png') {
            if (! imageistruecolor($resource)) {
                @imagepalettetotruecolor($resource);
            }
            imagealphablending($resource, true);
            imagesavealpha($resource, true);
        }

        if (($extension === 'jpg' || $extension === 'jpeg') && function_exists('exif_read_data')) {
            $exif = @exif_read_data($sourcePath);
            $orientation = (int) ($exif['Orientation'] ?? 1);
            $rotated = match ($orientation) {
                3 => @imagerotate($resource, 180, 0),
                6 => @imagerotate($resource, -90, 0),
                8 => @imagerotate($resource, 90, 0),
                default => null,
            };
            if ($rotated instanceof \GdImage) {
                $resource = $rotated;
            }
        }

        return $resource;
    }
}
