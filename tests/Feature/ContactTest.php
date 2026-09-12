<?php

it('stores a general enquiry with no course attached', function () {
    $response = $this->post(route('contact.store'), [
        'name' => 'Priya Nair',
        'email' => 'priya@example.com',
        'phone' => '+60 3-1234 5678',
        'message' => 'Do you run in-house cohorts?',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('enrollments', [
        'course_id' => null,
        'email' => 'priya@example.com',
        'status' => 'pending',
    ]);
});

it('requires a message on the contact form', function () {
    $this->post(route('contact.store'), [
        'name' => 'Priya Nair',
        'email' => 'priya@example.com',
        'phone' => '123',
    ])->assertSessionHasErrors('message');
});
