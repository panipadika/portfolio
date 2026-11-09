<?php

declare(strict_types=1);

use App\Models\Experience;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('admin can view experience index with filter tabs', function () {
    Experience::factory()->create(['type' => 'education']);
    Experience::factory()->create(['type' => 'experience']);

    $response = $this->get(route('admin.experiences.index'));
    $response->assertOk();
    $response->assertViewIs('admin.experiences.index');

    $filteredResponse = $this->get(route('admin.experiences.index', ['type' => 'experience']));
    $filteredResponse->assertOk();
});

test('admin can store a new experience or education', function () {
    $response = $this->post(route('admin.experiences.store'), [
        'type' => 'experience',
        'institution' => 'Diskominfo Kota Tasikmalaya',
        'role_or_major' => 'Web Developer Intern',
        'period' => 'Jan 2025 - Mar 2025',
        'description' => 'Mengembangkan dashboard integrasi data publik.',
        'sort_order' => 1,
    ]);

    $response->assertRedirect(route('admin.experiences.index', ['type' => 'experience']));
    $this->assertDatabaseHas('experiences', [
        'institution' => 'Diskominfo Kota Tasikmalaya',
        'role_or_major' => 'Web Developer Intern',
        'type' => 'experience',
    ]);
});

test('admin can edit, update, and delete an experience', function () {
    $item = Experience::factory()->create([
        'type' => 'education',
        'institution' => 'Universitas Indonesia',
    ]);

    $editResponse = $this->get(route('admin.experiences.edit', $item));
    $editResponse->assertOk();

    $updateResponse = $this->put(route('admin.experiences.update', $item), [
        'type' => 'education',
        'institution' => 'Universitas Siliwangi',
        'role_or_major' => 'S1 Informatika',
        'period' => '2022 - Sekarang',
        'description' => 'Konsentrasi Rekayasa Perangkat Lunak',
        'sort_order' => 1,
    ]);

    $updateResponse->assertRedirect(route('admin.experiences.index', ['type' => 'education']));
    $this->assertDatabaseHas('experiences', [
        'id' => $item->id,
        'institution' => 'Universitas Siliwangi',
    ]);

    $deleteResponse = $this->delete(route('admin.experiences.destroy', $item));
    $deleteResponse->assertRedirect(route('admin.experiences.index', ['type' => 'education']));
    $this->assertDatabaseMissing('experiences', ['id' => $item->id]);
});
