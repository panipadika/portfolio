<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

test('it optimizes and caches valid storage images into webp', function () {
    Storage::fake('public');

    ob_start();
    $img = imagecreatetruecolor(40, 20);
    imagepng($img);
    $pngData = (string) ob_get_clean();
    unset($img);

    Storage::disk('public')->put('projects/test-thumb.png', $pngData);

    $response = $this->get('/media-opt/640/storage/projects/test-thumb.png');

    $response->assertOk();
    $response->assertHeader('Cache-Control', 'immutable, max-age=31536000, public');
    expect($response->headers->get('Content-Type'))->toContain('image/');
});

test('it rejects invalid widths traversal and non-whitelisted paths', function (string $url) {
    Storage::fake('public');

    $response = $this->get($url);

    $response->assertNotFound();
})->with([
    'invalid width' => '/media-opt/999/logo-pp.png',
    'traversal' => '/media-opt/640/storage/../.env',
    'dotfile' => '/media-opt/640/storage/.gitignore',
    'non-whitelisted public file' => '/media-opt/640/index.php',
]);

