<?php

use App\Enums\EnrollmentStatus;
use App\Models\Course;

it('stores an enrolment request for a published course', function () {
    $course = Course::factory()->create();

    $response = $this->from(route('courses.show', $course))->post(route('enrollments.store', $course), [
        'name' => 'Aminah Yusof',
        'email' => 'aminah@example.com',
        'phone' => '+60 12-345 6789',
        'company' => 'Acme Sdn Bhd',
        'message' => 'Please send the next available dates.',
    ]);

    $response->assertRedirect(route('courses.show', $course));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('enrollments', [
        'course_id' => $course->id,
        'email' => 'aminah@example.com',
        'status' => EnrollmentStatus::Pending->value,
    ]);
});

it('validates the enrolment form', function () {
    $course = Course::factory()->create();

    $this->post(route('enrollments.store', $course), [])
        ->assertSessionHasErrors(['name', 'email', 'phone']);

    expect($course->enrollments()->count())->toBe(0);
});

it('does not accept enrolments for an unpublished course', function () {
    $course = Course::factory()->unpublished()->create();

    $this->post(route('enrollments.store', $course), [
        'name' => 'Test',
        'email' => 'test@example.com',
        'phone' => '123',
    ])->assertNotFound();
});
