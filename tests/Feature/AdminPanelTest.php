<?php

use App\Models\Course;
use App\Models\User;

it('redirects guests from the admin panel to login', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

it('lets an authenticated user reach the admin dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertOk();
});

it('exposes the resource index pages to authenticated users', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/courses')->assertOk();
    $this->get('/admin/categories')->assertOk();
    $this->get('/admin/enrollments')->assertOk();
    $this->get('/admin/certificates')->assertOk();
});

it('renders the resource create forms without errors', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/courses/create')->assertOk();
    $this->get('/admin/categories/create')->assertOk();
    $this->get('/admin/enrollments/create')->assertOk();
    $this->get('/admin/certificates/create')->assertOk();
});

it('renders a resource edit form', function () {
    $this->actingAs(User::factory()->create());

    $course = Course::factory()->create();

    $this->get("/admin/courses/{$course->getKey()}/edit")->assertOk();
});
