<?php

use App\Models\Certificate;
use Illuminate\Support\Facades\Route;

it('renders a branded 404 page', function () {
    $this->get('/this-page-does-not-exist')
        ->assertNotFound()
        ->assertSee('Error 404', false)
        ->assertSee('Page not found')
        ->assertSee('Verify a certificate')
        ->assertSee('noindex, nofollow', false);
});

it('uses the branded 404 for an unknown certificate uuid', function () {
    $this->get('/c/'.fake()->uuid())
        ->assertNotFound()
        ->assertSee('Page not found');
});

it('renders a branded 403 page', function () {
    Route::get('/test-forbidden', fn () => abort(403))->middleware('web');

    $this->get('/test-forbidden')
        ->assertForbidden()
        ->assertSee('Error 403', false)
        ->assertSee('Access denied');
});

it('renders a branded 500 page', function () {
    Route::get('/test-server-error', fn () => abort(500))->middleware('web');

    $this->get('/test-server-error')
        ->assertServerError()
        ->assertSee('Something went wrong');
});

it('still returns json for api style requests', function () {
    $this->getJson('/api/nope')->assertNotFound()->assertJsonStructure(['message']);
});

it('does not leak the branded page into the digital certificate route', function () {
    $certificate = Certificate::factory()->create();

    $this->get("/c/{$certificate->uuid}")
        ->assertOk()
        ->assertDontSee('Page not found');
});
