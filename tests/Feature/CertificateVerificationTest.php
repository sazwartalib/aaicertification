<?php

use App\Models\Certificate;

it('renders the verification form', function () {
    $this->get(route('verify.index'))
        ->assertOk()
        ->assertSee('Verify a certificate');
});

it('confirms a valid certificate', function () {
    $certificate = Certificate::factory()->create([
        'certificate_number' => 'AAI-2025-01234',
        'recipient_name' => 'Farah Lim',
        'expires_at' => now()->addYear(),
    ]);

    $this->get(route('verify.index', ['number' => 'AAI-2025-01234']))
        ->assertOk()
        ->assertSee('Valid certificate')
        ->assertSee('Farah Lim');
});

it('matches the certificate number case-insensitively', function () {
    Certificate::factory()->create(['certificate_number' => 'AAI-2025-09999']);

    $this->get(route('verify.index', ['number' => 'aai-2025-09999']))
        ->assertOk()
        ->assertSee('Valid certificate');
});

it('flags a revoked certificate', function () {
    Certificate::factory()->revoked()->create(['certificate_number' => 'AAI-2024-00042']);

    $this->get(route('verify.index', ['number' => 'AAI-2024-00042']))
        ->assertOk()
        ->assertSee('revoked');
});

it('flags an expired certificate', function () {
    Certificate::factory()->expired()->create(['certificate_number' => 'AAI-2019-00001']);

    $this->get(route('verify.index', ['number' => 'AAI-2019-00001']))
        ->assertOk()
        ->assertSee('expired');
});

it('reports when no certificate is found', function () {
    $this->get(route('verify.index', ['number' => 'AAI-0000-00000']))
        ->assertOk()
        ->assertSee('No certificate found');
});

it('masks the IC number instead of showing it in full', function () {
    $certificate = Certificate::factory()->create([
        'certificate_number' => 'AAI-2025-05678',
        'ic_number' => '901231-14-5566',
    ]);

    $this->get(route('verify.index', ['number' => 'AAI-2025-05678']))
        ->assertOk()
        ->assertSee($certificate->maskedIcNumber())
        ->assertDontSee('901231-14-5566');
});
