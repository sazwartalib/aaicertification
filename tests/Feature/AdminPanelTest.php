<?php

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

it('redirects guests from the admin console to login', function () {
    $this->get('/admin')->assertRedirect('/login');
});

it('renders the dashboard with programme stats', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Dashboard')
            ->has('stats', 4)
            ->has('recentEnrollments')
            ->has('expiringCertificates'));
});

it('lists each resource for authenticated users', function () {
    $this->actingAs(User::factory()->create());

    $course = Course::factory()->create();
    // Named to sort first: other factories below create categories of their own.
    $category = Category::factory()->create(['name' => 'AAA Quality Management']);
    $enrollment = Enrollment::factory()->for($course)->create();
    $certificate = Certificate::factory()->for($course)->create();

    $this->get('/admin/courses')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Courses/Index')
            ->where('courses.data.0.title', $course->title));

    $this->get('/admin/categories')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Categories/Index')
            ->where('categories.data.0.name', $category->name));

    $this->get('/admin/enrollments')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Enrollments/Index')
            ->where('enrollments.data.0.name', $enrollment->name));

    $this->get('/admin/certificates')->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Certificates/Index')
            ->where('certificates.data.0.certificateNumber', $certificate->certificate_number));
});

it('renders the resource create forms', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/courses/create')->assertOk();
    $this->get('/admin/categories/create')->assertOk();
    $this->get('/admin/enrollments/create')->assertOk();
    $this->get('/admin/certificates/create')->assertOk();
});

it('creates a course', function () {
    // Named to sort first: other factories below create categories of their own.
    $category = Category::factory()->create(['name' => 'AAA Quality Management']);

    $this->actingAs(User::factory()->create())
        ->post('/admin/courses', [
            'category_id' => $category->id,
            'title' => 'ISO 9001:2015 Lead Auditor',
            'slug' => '',
            'summary' => 'Five-day accredited lead auditor programme.',
            'description' => 'Full programme description.',
            'level' => 'advanced',
            'delivery_mode' => 'hybrid',
            'duration' => '5 days',
            'price' => '2500.00',
            'is_published' => true,
        ])
        ->assertRedirect('/admin/courses');

    expect(Course::where('slug', 'iso-90012015-lead-auditor')->exists())->toBeTrue();
});

it('updates a course', function () {
    $course = Course::factory()->create(['title' => 'Old title']);

    $this->actingAs(User::factory()->create())
        ->put("/admin/courses/{$course->slug}", [
            ...$course->only(['category_id', 'summary', 'description', 'duration', 'sort_order']),
            'title' => 'New title',
            'slug' => $course->slug,
            'level' => $course->level->value,
            'delivery_mode' => $course->delivery_mode->value,
            'is_published' => true,
        ])
        ->assertRedirect('/admin/courses');

    expect($course->refresh()->title)->toBe('New title');
});

it('rejects a course with a duplicate slug', function () {
    Course::factory()->create(['slug' => 'taken-slug']);

    $this->actingAs(User::factory()->create())
        ->post('/admin/courses', [
            'title' => 'Another course',
            'slug' => 'taken-slug',
            'summary' => 'Summary',
            'description' => 'Description',
            'level' => 'beginner',
            'delivery_mode' => 'online',
            'duration' => '2 days',
        ])
        ->assertSessionHasErrors('slug');
});

it('deletes an enrolment', function () {
    $enrollment = Enrollment::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete("/admin/enrollments/{$enrollment->id}")
        ->assertRedirect('/admin/enrollments');

    expect(Enrollment::find($enrollment->id))->toBeNull();
});

it('filters enrolments by status', function () {
    Enrollment::factory()->create(['status' => 'pending']);
    Enrollment::factory()->create(['status' => 'confirmed']);

    $this->actingAs(User::factory()->create())
        ->get('/admin/enrollments?status=confirmed')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->has('enrollments.data', 1));
});
