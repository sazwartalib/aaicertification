<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\User;

it('renders the home page with featured courses', function () {
    $featured = Course::factory()->featured()->create();
    Course::factory()->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($featured->title)
        ->assertSee('Verify a certificate');
});

it('renders the about and contact pages', function () {
    $this->get(route('about'))->assertOk()->assertSee('About');
    $this->get(route('contact'))->assertOk()->assertSee('Contact us');
});

it('lists published courses and hides unpublished ones', function () {
    $published = Course::factory()->create();
    $draft = Course::factory()->unpublished()->create();

    $this->get(route('courses.index'))
        ->assertOk()
        ->assertSee($published->title)
        ->assertDontSee($draft->title);
});

it('filters courses by category', function () {
    $security = Category::factory()->create(['name' => 'Information Security', 'slug' => 'information-security']);
    $quality = Category::factory()->create(['name' => 'Quality', 'slug' => 'quality']);

    $match = Course::factory()->for($security, 'category')->create();
    $other = Course::factory()->for($quality, 'category')->create();

    $this->get(route('courses.index', ['category' => 'information-security']))
        ->assertOk()
        ->assertSee($match->title)
        ->assertDontSee($other->title);
});

it('shows a published course detail page', function () {
    $course = Course::factory()->create();

    $this->get(route('courses.show', $course))
        ->assertOk()
        ->assertSee($course->title)
        ->assertSee('Request your place');
});

it('returns 404 for an unpublished course detail page', function () {
    $course = Course::factory()->unpublished()->create();

    $this->get(route('courses.show', $course))->assertNotFound();
});

it('offers a staff login link in the footer to guests', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Staff login')
        ->assertSee(route('login'));
});

it('points the footer link at the admin console once signed in', function () {
    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertOk()
        ->assertSee('Admin console')
        ->assertSee(route('admin.dashboard'));
});
