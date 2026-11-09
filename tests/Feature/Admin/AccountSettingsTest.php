<?php

declare(strict_types=1);

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'Admin Lama',
        'email' => 'admin.lama@portfolio.com',
        'password' => Hash::make('password_lama_123'),
        'email_verified_at' => now(),
    ]);
});

test('visiting /admin redirects guests to login and authenticated users to dashboard', function () {
    $guestResponse = $this->get('/admin');
    $guestResponse->assertRedirect(route('login'));

    $this->actingAs($this->user);
    $authResponse = $this->get('/admin');
    $authResponse->assertRedirect('/dashboard');
});

test('guest cannot visit account settings page', function () {
    $response = $this->get(route('admin.account.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated admin can view account settings page', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('admin.account.index'));
    $response->assertOk();
    $response->assertViewIs('admin.account.index');
    $response->assertSee('Admin Lama');
    $response->assertSee('admin.lama@portfolio.com');
});

test('admin can update their name and email address', function () {
    $this->actingAs($this->user);

    $response = $this->put(route('admin.account.profile'), [
        'name' => 'Pani Padika Baru',
        'email' => 'panipadika.baru@portfolio.com',
    ]);

    $response->assertRedirect(route('admin.account.index'));
    $response->assertSessionHas('success');

    $this->user->refresh();
    expect($this->user->name)->toBe('Pani Padika Baru');
    expect($this->user->email)->toBe('panipadika.baru@portfolio.com');
    expect($this->user->email_verified_at)->not->toBeNull();
});

test('admin cannot update email to another existing user email', function () {
    $otherUser = User::factory()->create(['email' => 'other@portfolio.com']);
    $this->actingAs($this->user);

    $response = $this->put(route('admin.account.profile'), [
        'name' => 'Admin Test',
        'email' => 'other@portfolio.com',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->user->refresh();
    expect($this->user->email)->toBe('admin.lama@portfolio.com');
});

test('admin can change their password with valid current password', function () {
    $this->actingAs($this->user);

    $response = $this->put(route('admin.account.password'), [
        'current_password' => 'password_lama_123',
        'password' => 'password_baru_456',
        'password_confirmation' => 'password_baru_456',
    ]);

    $response->assertRedirect(route('admin.account.index'));
    $response->assertSessionHas('success');

    $this->user->refresh();
    expect(Hash::check('password_baru_456', $this->user->password))->toBeTrue();
});

test('admin cannot change password if current password is wrong', function () {
    $this->actingAs($this->user);

    $response = $this->put(route('admin.account.password'), [
        'current_password' => 'password_salah',
        'password' => 'password_baru_456',
        'password_confirmation' => 'password_baru_456',
    ]);

    $response->assertSessionHasErrors(['current_password']);
    $this->user->refresh();
    expect(Hash::check('password_lama_123', $this->user->password))->toBeTrue();
});

test('admin can customize the admin login url slug and access login page via that slug', function () {
    $this->actingAs($this->user);

    $response = $this->put(route('admin.account.login-slug'), [
        'login_slug' => 'portal-rahasia',
    ]);

    $response->assertRedirect(route('admin.account.index'));
    $response->assertSessionHas('success');

    expect(SiteSetting::get('admin_login_slug'))->toBe('portal-rahasia');

    // Visiting as guest renders the login view
    auth()->logout();
    $loginResponse = $this->get('/portal-rahasia');
    $loginResponse->assertOk();

    // Wrong slug returns 404
    $wrongResponse = $this->get('/non-existent-custom-slug');
    $wrongResponse->assertNotFound();
});

test('custom login slug rejects reserved system keywords', function () {
    $this->actingAs($this->user);

    $response = $this->put(route('admin.account.login-slug'), [
        'login_slug' => 'dashboard',
    ]);

    $response->assertSessionHasErrors(['login_slug']);
});
