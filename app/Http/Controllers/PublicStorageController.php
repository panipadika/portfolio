<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicStorageController extends Controller
{
    /**
     * Serve public storage files securely as a fallback when symlinks are unavailable on shared hosting.
     */
    public function __invoke(Request $request, string $path): BinaryFileResponse
    {
        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (
            $normalized === ''
            || str_contains($normalized, '..')
            || str_contains($normalized, "\0")
            || str_starts_with(basename($normalized), '.')
        ) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($normalized)) {
            abort(404);
        }

        $fullPath = realpath($disk->path($normalized));
        $rootPath = realpath($disk->path(''));

        if (
            $fullPath === false
            || $rootPath === false
            || ! str_starts_with($fullPath, $rootPath)
            || ! is_file($fullPath)
        ) {
            abort(404);
        }

        return response()->file($fullPath, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
