<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('admin can view project list and create page', function () {
    Project::factory()->count(3)->create();

    $response = $this->get(route('admin.projects.index'));
    $response->assertOk();
    $response->assertViewIs('admin.projects.index');

    $createResponse = $this->get(route('admin.projects.create'));
    $createResponse->assertOk();
    $createResponse->assertViewIs('admin.projects.create');
});

test('admin can store a new project with image upload', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('project-preview.png', 800, 600);

    $response = $this->post(route('admin.projects.store'), [
        'title' => 'Aplikasi Manajemen Keuangan',
        'slug' => 'aplikasi-manajemen-keuangan',
        'description' => 'Sistem pencatatan kas dan laporan keuangan.',
        'image' => $image,
        'tech_tags' => 'Laravel, Inertia.js, Tailwind CSS',
        'demo_url' => 'https://finance.example.com',
        'repo_url' => 'https://github.com/example/finance',
        'is_featured' => '1',
    ]);

    $response->assertRedirect(route('admin.projects.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('projects', [
        'title' => 'Aplikasi Manajemen Keuangan',
        'slug' => 'aplikasi-manajemen-keuangan',
        'is_featured' => true,
    ]);

    $project = Project::where('slug', 'aplikasi-manajemen-keuangan')->first();
    expect($project)->not->toBeNull();
    expect($project->image_url)->toStartWith('/storage/projects/');

    $storedPath = str_replace('/storage/', '', $project->image_url);
    Storage::disk('public')->assertExists($storedPath);
});

test('admin can edit and update existing project', function () {
    $project = Project::factory()->create([
        'title' => 'Judul Lama',
    ]);

    $response = $this->get(route('admin.projects.edit', $project));
    $response->assertOk();
    $response->assertViewIs('admin.projects.edit');

    $updateResponse = $this->put(route('admin.projects.update', $project), [
        'title' => 'Judul Baru Diperbarui',
        'slug' => 'judul-baru-diperbarui',
        'description' => 'Deskripsi yang telah diperbarui.',
        'tech_tags' => 'Vue, Laravel',
        'demo_url' => 'https://demo.example.com',
        'repo_url' => 'https://github.com/example/repo',
        'is_featured' => '0',
    ]);

    $updateResponse->assertRedirect(route('admin.projects.index'));
    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'title' => 'Judul Baru Diperbarui',
        'is_featured' => false,
    ]);
});

test('admin can delete a project and clean up its stored image', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('delete-test.png');
    $path = $image->store('projects', 'public');

    $project = Project::factory()->create([
        'image_url' => '/storage/'.$path,
    ]);

    Storage::disk('public')->assertExists($path);

    $response = $this->delete(route('admin.projects.destroy', $project));
    $response->assertRedirect(route('admin.projects.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    Storage::disk('public')->assertMissing($path);
});

test('storing project automatically provisions missing tech tags into credentials', function () {
    Storage::fake('public');

    $response = $this->post(route('admin.projects.store'), [
        'title' => 'Sistem IoT Pertanian',
        'description' => 'Monitoring tanah dan cuaca otomatis.',
        'tech_tags' => 'MicroPython, ESP32',
    ]);

    $response->assertRedirect(route('admin.projects.index'));

    $this->assertDatabaseHas('credentials', [
        'category' => 'tech_stack',
        'title' => 'MicroPython',
    ]);

    $this->assertDatabaseHas('credentials', [
        'category' => 'tech_stack',
        'title' => 'ESP32',
    ]);
});
