<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;

it('keeps account settings behind authentication', function () {
    $this->get('/admin/account')->assertRedirect('/login');
    $this->put('/admin/account/password', [])->assertRedirect('/login');
});

it('renders the account settings page', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/account')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Admin/Account'));
});

it('changes the password', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $this->actingAs($user)
        ->put('/admin/account/password', [
            'current_password' => 'old-password',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ])
        ->assertSessionHas('success');

    expect(Hash::check('brand-new-password', $user->refresh()->password))->toBeTrue();
});

it('keeps the current session signed in after a change', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $this->actingAs($user)->put('/admin/account/password', [
        'current_password' => 'old-password',
        'password' => 'brand-new-password',
        'password_confirmation' => 'brand-new-password',
    ]);

    $this->assertAuthenticatedAs($user);
});

it('rejects a wrong current password', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $this->actingAs($user)
        ->put('/admin/account/password', [
            'current_password' => 'not-my-password',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ])
        ->assertSessionHasErrors('current_password');

    expect(Hash::check('old-password', $user->refresh()->password))->toBeTrue();
});

it('rejects a mismatched confirmation', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $this->actingAs($user)
        ->put('/admin/account/password', [
            'current_password' => 'old-password',
            'password' => 'brand-new-password',
            'password_confirmation' => 'different-password',
        ])
        ->assertSessionHasErrors('password');
});

it('rejects reusing the current password', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $this->actingAs($user)
        ->put('/admin/account/password', [
            'current_password' => 'old-password',
            'password' => 'old-password',
            'password_confirmation' => 'old-password',
        ])
        ->assertSessionHasErrors('password');
});
