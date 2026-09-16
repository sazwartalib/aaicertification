<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CourseLevel;
use App\Enums\DeliveryMode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();

        return Inertia::render('Admin/Courses/Index', [
            'filters' => ['search' => $search, 'status' => $status],
            'courses' => Course::query()
                ->with('category:id,name')
                ->withCount('enrollments')
                ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
                ->when($status === 'published', fn ($query) => $query->where('is_published', true))
                ->when($status === 'draft', fn ($query) => $query->where('is_published', false))
                ->ordered()
                ->paginate(12)
                ->withQueryString()
                ->through(fn (Course $course): array => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'category' => $course->category?->name,
                    'level' => $course->level->label(),
                    'levelTone' => $course->level->tone(),
                    'deliveryMode' => $course->delivery_mode->label(),
                    'price' => $course->price,
                    'isPublished' => $course->is_published,
                    'isFeatured' => $course->is_featured,
                    'enrollmentsCount' => $course->enrollments_count,
                    'editUrl' => route('admin.courses.edit', $course),
                    'deleteUrl' => route('admin.courses.destroy', $course),
                    'publicUrl' => route('courses.show', $course),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Courses/Form', [
            'course' => null,
            ...$this->formOptions(),
        ]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        Course::create($request->validated());

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created.');
    }

    public function edit(Course $course): Response
    {
        return Inertia::render('Admin/Courses/Form', [
            'course' => [
                ...$course->only([
                    'id', 'category_id', 'title', 'slug', 'summary', 'description',
                    'duration', 'accreditation_body', 'image_path', 'sort_order',
                    'is_featured', 'is_published',
                ]),
                'level' => $course->level->value,
                'delivery_mode' => $course->delivery_mode->value,
                'price' => $course->price,
            ],
            ...$this->formOptions(),
        ]);
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted.');
    }

    /**
     * @return array{categories: array<int, array{value: int, label: string}>, levels: array<int, array{value: string, label: string}>, deliveryModes: array<int, array{value: string, label: string}>}
     */
    protected function formOptions(): array
    {
        return [
            'categories' => Category::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Category $category): array => ['value' => $category->id, 'label' => $category->name])
                ->all(),
            'levels' => CourseLevel::options(),
            'deliveryModes' => DeliveryMode::options(),
        ];
    }
}
