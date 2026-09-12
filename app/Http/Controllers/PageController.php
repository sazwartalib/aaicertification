<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'featuredCourses' => Course::query()
                ->published()
                ->featured()
                ->ordered()
                ->with('category')
                ->take(6)
                ->get(),
            'categories' => Category::query()
                ->withCount('publishedCourses')
                ->orderBy('name')
                ->get(),
            'stats' => [
                'courses' => Course::query()->published()->count(),
                'certificates' => Certificate::query()->count(),
                'categories' => Category::query()->count(),
            ],
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
