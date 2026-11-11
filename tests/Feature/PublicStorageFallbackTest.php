<?php

use Illuminate\Support\Facades\Storage;

test('it serves existing files from the public storage disk as a symlink fallback', function () {
    Storage::fake('public');
    Storage::disk('public')->put('projects/sample.txt', 'hello-portfolio');

    $response = $this->get('/storage/projects/sample.txt');

    $response->assertOk();
    expect(file_get_contents($response->getFile()->getPathname()))->toBe('hello-portfolio');
});

test('it returns not found for missing files in public storage', function () {
    Storage::fake('public');

    $response = $this->get('/storage/projects/does-not-exist.png');

    $response->assertNotFound();
});

test('it blocks path traversal and hidden dotfiles in public storage fallback', function (string $maliciousPath) {
    Storage::fake('public');
    Storage::disk('public')->put('.gitignore', "*\n!.gitignore\n");

    $response = $this->get('/storage/'.$maliciousPath);

    $response->assertNotFound();
})->with([
    'parent traversal' => '../.env',
    'nested traversal' => 'projects/../../.env',
    'dotfile' => '.gitignore',
]);
