<?php

use App\Models\Certificate;
use App\Models\User;

it('redirects guests away from the certificate print view', function () {
    $certificate = Certificate::factory()->create();

    $this->get(route('certificates.print', $certificate))
        ->assertRedirect('/login');
});

it('shows the full certificate details to an authenticated user', function () {
    $this->actingAs(User::factory()->create());

    $certificate = Certificate::factory()->create([
        'recipient_name' => 'Farah Lim',
        'ic_number' => '901231-14-5566',
    ]);

    $this->get(route('certificates.print', $certificate))
        ->assertOk()
        ->assertSee('Farah Lim')
        ->assertSee('901231-14-5566');
});
