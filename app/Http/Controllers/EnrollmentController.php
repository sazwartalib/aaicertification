<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class EnrollmentController extends Controller
{
    public function store(StoreEnrollmentRequest $request, Course $course): RedirectResponse
    {
        abort_unless($course->is_published, Response::HTTP_NOT_FOUND);

        $course->enrollments()->create($request->validated());

        return redirect()
            ->route('courses.show', $course)
            ->with('status', 'Thank you — your enrolment request for "'.$course->title.'" has been received. Our training team will contact you shortly.');
    }
}
