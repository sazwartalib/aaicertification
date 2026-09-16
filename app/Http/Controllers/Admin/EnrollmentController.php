<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EnrollmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EnrollmentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EnrollmentController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();

        return Inertia::render('Admin/Enrollments/Index', [
            'filters' => ['search' => $search, 'status' => $status],
            'statuses' => EnrollmentStatus::options(),
            'enrollments' => Enrollment::query()
                ->with('course:id,title')
                ->when($search !== '', fn ($query) => $query->where(fn ($inner) => $inner
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")))
                ->when($status !== '', fn ($query) => $query->where('status', $status))
                ->latest()
                ->paginate(15)
                ->withQueryString()
                ->through(fn (Enrollment $enrollment): array => [
                    'id' => $enrollment->id,
                    'name' => $enrollment->name,
                    'email' => $enrollment->email,
                    'phone' => $enrollment->phone,
                    'company' => $enrollment->company,
                    'course' => $enrollment->course?->title,
                    'status' => $enrollment->status->value,
                    'statusLabel' => $enrollment->status->label(),
                    'statusTone' => $enrollment->status->tone(),
                    'createdAt' => $enrollment->created_at->toDayDateTimeString(),
                    'editUrl' => route('admin.enrollments.edit', $enrollment),
                    'deleteUrl' => route('admin.enrollments.destroy', $enrollment),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Enrollments/Form', [
            'enrollment' => null,
            ...$this->formOptions(),
        ]);
    }

    public function store(EnrollmentRequest $request): RedirectResponse
    {
        Enrollment::create($request->validated());

        return redirect()
            ->route('admin.enrollments.index')
            ->with('success', 'Enrolment created.');
    }

    public function edit(Enrollment $enrollment): Response
    {
        return Inertia::render('Admin/Enrollments/Form', [
            'enrollment' => [
                ...$enrollment->only(['id', 'course_id', 'name', 'email', 'phone', 'company', 'message']),
                'status' => $enrollment->status->value,
            ],
            ...$this->formOptions(),
        ]);
    }

    public function update(EnrollmentRequest $request, Enrollment $enrollment): RedirectResponse
    {
        $enrollment->update($request->validated());

        return redirect()
            ->route('admin.enrollments.index')
            ->with('success', 'Enrolment updated.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()
            ->route('admin.enrollments.index')
            ->with('success', 'Enrolment deleted.');
    }

    /**
     * @return array{courses: array<int, array{value: int, label: string}>, statuses: array<int, array{value: string, label: string}>}
     */
    protected function formOptions(): array
    {
        return [
            'courses' => Course::query()
                ->ordered()
                ->get(['id', 'title'])
                ->map(fn (Course $course): array => ['value' => $course->id, 'label' => $course->title])
                ->all(),
            'statuses' => EnrollmentStatus::options(),
        ];
    }
}
