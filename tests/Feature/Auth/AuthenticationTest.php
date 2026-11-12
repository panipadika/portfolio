<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Features;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users authenticating via inertia are redirected to dashboard via hard location reload', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ], [
        'X-Inertia' => 'true',
    ]);

    $this->assertAuthenticated();
    $response->assertStatus(409);
    $response->assertHeader('X-Inertia-Location', route('dashboard'));
});

test('users with two factor enabled are redirected to two factor challenge', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $response->assertSessionHas('login.id', $user->id);
    $this->assertGuest();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout and are redirected back to admin login page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status', 'Anda berhasil keluar dari panel admin.');

    $this->assertGuest();
});

test('users logging out via inertia are redirected to admin login via hard location', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'), [], [
        'X-Inertia' => 'true',
    ]);

    $this->assertGuest();
    $response->assertStatus(409);
    $response->assertHeader('X-Inertia-Location', route('login'));
});

test('users logging out are redirected to custom admin login slug when configured', function () {
    SiteSetting::set('admin_login_slug', 'portal-rahasia', 'admin');
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(url('/portal-rahasia'));
});

test('users are rate limited', function () {
    $user = User::factory()->create();

    RateLimiter::increment(md5('login'.implode('|', [$user->email, '127.0.0.1'])), amount: 5);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertTooManyRequests();
});
