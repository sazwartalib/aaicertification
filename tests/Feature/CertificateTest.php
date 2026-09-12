<?php

use App\Models\Certificate;

it('flags a valid certificate expiring within 60 days as expiring soon', function () {
    $certificate = Certificate::factory()->create(['expires_at' => now()->addDays(30)]);

    expect($certificate->isExpiringSoon())->toBeTrue();
});

it('does not flag a certificate expiring beyond 60 days as expiring soon', function () {
    $certificate = Certificate::factory()->create(['expires_at' => now()->addDays(90)]);

    expect($certificate->isExpiringSoon())->toBeFalse();
});

it('does not flag a revoked certificate as expiring soon', function () {
    $certificate = Certificate::factory()->revoked()->create(['expires_at' => now()->addDays(30)]);

    expect($certificate->isExpiringSoon())->toBeFalse();
});

it('does not flag an already expired certificate as expiring soon', function () {
    $certificate = Certificate::factory()->expired()->create();

    expect($certificate->isExpiringSoon())->toBeFalse();
});

it('scopes certificates expiring soon', function () {
    $expiringSoon = Certificate::factory()->create(['expires_at' => now()->addDays(10)]);
    Certificate::factory()->create(['expires_at' => now()->addDays(120)]);
    Certificate::factory()->expired()->create();

    $results = Certificate::query()->expiringSoon()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->is($expiringSoon))->toBeTrue();
});
