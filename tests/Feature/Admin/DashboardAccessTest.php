<?php

declare(strict_types=1);

use App\Models\User;

test('guest cannot access any admin routes and is redirected to login', function (string $route) {
    $response = $this->get($route);
    $response->assertRedirect(route('login'));
})->with([
    'dashboard' => '/dashboard',
    'projects' => '/dashboard/projects',
    'experiences' => '/dashboard/experiences',
    'credentials' => '/dashboard/credentials',
    'messages' => '/dashboard/messages',
    'settings' => '/dashboard/settings',
]);

test('authenticated admin can view dashboard overview and modules', function (string $route) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get($route);
    $response->assertOk();
})->with([
    'dashboard' => '/dashboard',
    'projects' => '/dashboard/projects',
    'experiences' => '/dashboard/experiences',
    'credentials' => '/dashboard/credentials',
    'messages' => '/dashboard/messages',
    'settings' => '/dashboard/settings',
]);

test('inertia requests to admin routes are forced to hard location to avoid modal preview', function (string $route) {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get($route, ['X-Inertia' => 'true']);
    $response->assertStatus(409);
    $response->assertHeader('X-Inertia-Location', url($route));
})->with([
    'dashboard' => '/dashboard',
    'projects' => '/dashboard/projects',
    'experiences' => '/dashboard/experiences',
    'credentials' => '/dashboard/credentials',
    'messages' => '/dashboard/messages',
    'settings' => '/dashboard/settings',
]);

test('admin navbar renders global live search for menus and settings', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertOk();
    $response->assertSee('adminGlobalSearchInput', false);
    $response->assertSee('Kustomisasi URL Login Admin', false);
    $response->assertSee('Daftar Projek', false);
});
