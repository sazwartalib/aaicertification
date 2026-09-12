<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $activeCategory = $request->string('category')->toString() ?: null;

        $courses = Course::query()
            ->published()
            ->with('category')
            ->when($activeCategory, function ($query) use ($activeCategory) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $activeCategory));
            })
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('pages.courses.index', [
            'courses' => $courses,
            'categories' => Category::query()
                ->withCount('publishedCourses')
                ->orderBy('name')
                ->get(),
            'activeCategory' => $activeCategory,
        ]);
    }

    public function show(Course $course): View
    {
        abort_unless($course->is_published, Response::HTTP_NOT_FOUND);

        $course->load('category');

        return view('pages.courses.show', [
            'course' => $course,
            'relatedCourses' => Course::query()
                ->published()
                ->where('id', '!=', $course->id)
                ->when($course->category_id, fn ($query) => $query->where('category_id', $course->category_id))
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }
}
