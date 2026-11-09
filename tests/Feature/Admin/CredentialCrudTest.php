<?php

declare(strict_types=1);

use App\Models\Credential;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('admin can view credentials index and filter by category', function () {
    Credential::factory()->create(['category' => 'certificate']);
    Credential::factory()->create(['category' => 'achievement']);

    $response = $this->get(route('admin.credentials.index'));
    $response->assertOk();

    $certResponse = $this->get(route('admin.credentials.index', ['category' => 'certificate']));
    $certResponse->assertOk();

    $searchResponse = $this->get(route('admin.credentials.index', ['search' => 'React']));
    $searchResponse->assertOk();
    $searchResponse->assertViewHas('search', 'React');
});

test('admin can store a credential with image upload', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('cert.png');

    $response = $this->post(route('admin.credentials.store'), [
        'category' => 'certificate',
        'title' => 'Belajar Dasar React',
        'issuer_or_level' => 'Dicoding Indonesia',
        'year' => '2024',
        'image' => $file,
        'credential_url' => 'https://dicoding.com/certificates/sample',
        'sort_order' => 1,
    ]);

    $response->assertRedirect(route('admin.credentials.index', ['category' => 'certificate']));
    $this->assertDatabaseHas('credentials', [
        'category' => 'certificate',
        'title' => 'Belajar Dasar React',
    ]);

    $credential = Credential::where('title', 'Belajar Dasar React')->first();
    expect($credential->image_or_icon)->toStartWith('/storage/credentials/');
});

test('admin can update and delete a credential', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('old-cert.png');
    $path = $file->store('credentials', 'public');

    $credential = Credential::factory()->create([
        'category' => 'achievement',
        'title' => 'Juara 2 Hackathon',
        'image_or_icon' => '/storage/'.$path,
    ]);

    $updateResponse = $this->put(route('admin.credentials.update', $credential), [
        'category' => 'achievement',
        'title' => 'Juara 1 Hackathon Nasional',
        'issuer_or_level' => 'Tingkat Nasional',
        'year' => '2025',
        'sort_order' => 1,
    ]);

    $updateResponse->assertRedirect(route('admin.credentials.index', ['category' => 'achievement']));
    $this->assertDatabaseHas('credentials', [
        'id' => $credential->id,
        'title' => 'Juara 1 Hackathon Nasional',
    ]);

    $deleteResponse = $this->delete(route('admin.credentials.destroy', $credential));
    $deleteResponse->assertRedirect(route('admin.credentials.index', ['category' => 'achievement']));
    $this->assertDatabaseMissing('credentials', ['id' => $credential->id]);
    Storage::disk('public')->assertMissing($path);
});

test('admin can store a tech stack with raw SVG code from devicon or iconify', function () {
    Storage::fake('public');

    $svgCode = '<svg viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"><path fill="#00d8ff" d="M10 10h108v108H10z"/></svg>';

    $response = $this->post(route('admin.credentials.store'), [
        'category' => 'tech_stack',
        'title' => 'Vue.js',
        'issuer_or_level' => 'Frontend',
        'image_or_icon' => $svgCode,
        'sort_order' => 10,
    ]);

    $response->assertRedirect(route('admin.credentials.index', ['category' => 'tech_stack']));

    $tech = Credential::where('title', 'Vue.js')->first();
    expect($tech)->not->toBeNull();
    expect($tech->image_or_icon)->toStartWith('/storage/credentials/svg-');
    expect($tech->image_or_icon)->toEndWith('.svg');

    $storedPath = str_replace('/storage/', '', $tech->image_or_icon);
    Storage::disk('public')->assertExists($storedPath);
    expect(Storage::disk('public')->get($storedPath))->toContain('<svg viewBox="0 0 128 128"');
});

test('admin can lookup icons for tech stack', function () {
    $response = $this->getJson(route('admin.credentials.lookup-icon', ['query' => 'docker']));
    $response->assertOk();
    $response->assertJsonStructure(['query', 'icons']);
});

test('admin can quick store a tech stack with auto icon resolution', function () {
    Storage::fake('public');

    $response = $this->postJson(route('admin.credentials.quick-tech-stack'), [
        'title' => 'Docker',
        'issuer_or_level' => 'DevOps',
    ]);

    $response->assertCreated();
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('credentials', [
        'category' => 'tech_stack',
        'title' => 'Docker',
    ]);
});

test('quick store tech stack handles existing gracefully', function () {
    Credential::factory()->create([
        'category' => 'tech_stack',
        'title' => 'PostgreSQL',
    ]);

    $response = $this->postJson(route('admin.credentials.quick-tech-stack'), [
        'title' => 'postgresql',
    ]);

    $response->assertOk();
    $response->assertJson([
        'success' => true,
        'is_new' => false,
    ]);
});
