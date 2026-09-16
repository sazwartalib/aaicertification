<?php

use App\Models\User;
use App\Rules\Turnstile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Config::set('services.turnstile.site_key', 'test-site-key');
    Config::set('services.turnstile.secret_key', 'test-secret-key');
});

it('is disabled when no keys are configured', function () {
    Config::set('services.turnstile.secret_key', null);

    expect(Turnstile::isEnabled())->toBeFalse();
});

it('shares the site key with inertia pages when enabled', function () {
    $this->get('/login')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('turnstile.enabled', true)
            ->where('turnstile.siteKey', 'test-site-key'));
});

it('blocks a login when the challenge token is missing', function () {
    $user = User::factory()->create(['password' => 'password']);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('cf-turnstile-response');

    $this->assertGuest();
});

it('blocks a login when cloudflare rejects the token', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response(['success' => false], 200),
    ]);

    $user = User::factory()->create(['password' => 'password']);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'cf-turnstile-response' => 'bad-token',
    ])->assertSessionHasErrors('cf-turnstile-response');

    $this->assertGuest();
});

it('allows a login when cloudflare accepts the token', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response(['success' => true], 200),
    ]);

    $user = User::factory()->create(['password' => 'password']);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'cf-turnstile-response' => 'good-token',
    ])->assertRedirect('/admin');

    $this->assertAuthenticatedAs($user);
});

it('protects the public contact form', function () {
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response(['success' => false], 200),
    ]);

    $this->post('/contact', [
        'name' => 'Aina',
        'email' => 'aina@example.com',
        'phone' => '+60123456789',
        'message' => 'Please contact me.',
        'cf-turnstile-response' => 'bad-token',
    ])->assertSessionHasErrors('cf-turnstile-response');
});
