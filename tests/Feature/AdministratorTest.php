<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia;

it('keeps the administrator list behind authentication', function () {
    $this->get('/admin/administrators')->assertRedirect('/login');
    $this->post('/admin/administrators', [])->assertRedirect('/login');
});

it('lists administrators and flags the current user', function () {
    $current = User::factory()->create(['name' => 'AAA Current Admin']);
    User::factory()->create(['name' => 'ZZZ Other Admin']);

    $this->actingAs($current)
        ->get('/admin/administrators')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Administrators/Index')
            ->where('administrators.data.0.name', 'AAA Current Admin')
            ->where('administrators.data.0.isCurrentUser', true)
            ->where('administrators.data.1.isCurrentUser', false));
});

it('lets an administrator create another administrator', function () {
    $this->actingAs(User::factory()->create())
        ->post('/admin/administrators', [
            'name' => 'Nur Amira',
            'email' => 'amira@aaicertification.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])
        ->assertRedirect('/admin/administrators');

    expect(User::where('email', 'amira@aaicertification.test')->exists())->toBeTrue();
});

it('lets the new administrator sign in with the issued password', function () {
    $this->actingAs(User::factory()->create())
        ->post('/admin/administrators', [
            'name' => 'Nur Amira',
            'email' => 'amira@aaicertification.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ]);

    $this->post('/logout');

    $this->post('/login', [
        'email' => 'amira@aaicertification.test',
        'password' => 'secret-password',
    ])->assertRedirect('/admin');

    $this->assertAuthenticated();
});

it('rejects a duplicate email', function () {
    $existing = User::factory()->create();

    $this->actingAs(User::factory()->create())
        ->post('/admin/administrators', [
            'name' => 'Duplicate',
            'email' => $existing->email,
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])
        ->assertSessionHasErrors('email');
});

it('removes another administrator', function () {
    $other = User::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete("/admin/administrators/{$other->id}")
        ->assertRedirect('/admin/administrators');

    expect(User::find($other->id))->toBeNull();
});

it('refuses to remove your own account', function () {
    $current = User::factory()->create();
    User::factory()->create();

    $this->actingAs($current)
        ->delete("/admin/administrators/{$current->id}")
        ->assertSessionHas('error');

    expect(User::find($current->id))->not->toBeNull();
});

it('refuses to remove the last administrator', function () {
    $only = User::factory()->create();

    $this->actingAs($only)
        ->delete("/admin/administrators/{$only->id}")
        ->assertSessionHas('error');

    expect(User::count())->toBe(1);
});
