<?php

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Support\Str;

it('generates a uuid when a certificate is created', function () {
    $certificate = Certificate::factory()->create();

    expect($certificate->uuid)->not->toBeNull()
        ->and(Str::isUuid($certificate->uuid))->toBeTrue();
});

it('keeps a uuid that was supplied explicitly', function () {
    $uuid = (string) Str::uuid();

    $certificate = Certificate::factory()->create(['uuid' => $uuid]);

    expect($certificate->uuid)->toBe($uuid);
});

it('shows the digital certificate for a valid certificate', function () {
    $certificate = Certificate::factory()->create([
        'recipient_name' => 'Nur Amira binti Zakaria',
        'course_title' => 'ISO 9001:2015 Lead Auditor',
        'status' => 'valid',
        'expires_at' => now()->addYear(),
    ]);

    $this->get("/c/{$certificate->uuid}")
        ->assertOk()
        ->assertSee('Nur Amira binti Zakaria')
        ->assertSee('ISO 9001:2015 Lead Auditor')
        ->assertSee('Verified &amp; valid', false);
});

it('marks a revoked certificate as revoked', function () {
    $certificate = Certificate::factory()->create(['status' => 'revoked']);

    $this->get("/c/{$certificate->uuid}")
        ->assertOk()
        ->assertSee('Revoked')
        ->assertDontSee('Verified &amp; valid', false);
});

it('marks a lapsed certificate as expired', function () {
    $certificate = Certificate::factory()->create([
        'status' => 'valid',
        'issued_at' => now()->subYears(3),
        'expires_at' => now()->subDay(),
    ]);

    $this->get("/c/{$certificate->uuid}")
        ->assertOk()
        ->assertSee('Expired');
});

it('masks the ic number and never exposes it in full', function () {
    $certificate = Certificate::factory()->create(['ic_number' => '900101145678']);

    $this->get("/c/{$certificate->uuid}")
        ->assertOk()
        ->assertSee('5678')
        ->assertDontSee('900101145678');
});

it('keeps the page out of search engines', function () {
    $certificate = Certificate::factory()->create();

    $this->get("/c/{$certificate->uuid}")
        ->assertOk()
        ->assertSee('noindex, nofollow', false);
});

it('embeds the public url as the qr payload', function () {
    $certificate = Certificate::factory()->create();

    $this->get("/c/{$certificate->uuid}")
        ->assertOk()
        ->assertSee('data-qr="'.$certificate->publicUrl().'"', false);
});

it('returns 404 for an unknown uuid', function () {
    $this->get('/c/'.Str::uuid())->assertNotFound();
});

it('puts the digital certificate link on the printed certificate', function () {
    $certificate = Certificate::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('certificates.print', $certificate))
        ->assertOk()
        ->assertSee('data-qr="'.$certificate->publicUrl().'"', false)
        ->assertSee('Scan to verify');
});

it('links from the public register to the digital certificate', function () {
    $certificate = Certificate::factory()->create();

    $this->get(route('verify.index', ['number' => $certificate->certificate_number]))
        ->assertOk()
        ->assertSee($certificate->publicUrl());
});

it('still gets a uuid when model events are muted', function () {
    // Seeders use WithoutModelEvents, so the creating hook alone is not enough.
    $certificate = Certificate::withoutEvents(
        fn () => Certificate::factory()->create()
    );

    expect($certificate->uuid)->not->toBeNull()
        ->and(Str::isUuid($certificate->uuid))->toBeTrue();
});
