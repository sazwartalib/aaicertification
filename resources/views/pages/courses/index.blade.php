<x-layouts.app title="Training Courses" description="Browse accredited certification and professional training courses.">
    <section class="relative overflow-hidden bg-navy-950 py-16">
        <div class="absolute inset-0 bg-grid"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="eyebrow border-navy-700 bg-navy-900/60 text-navy-200">
                <x-ui-icon name="academic-cap" class="h-3.5 w-3.5 text-gold-400" />
                Course catalogue
            </span>
            <h1 class="mt-5 text-4xl font-bold text-white">Training courses</h1>
            <p class="mt-4 max-w-2xl text-navy-200">
                Instructor-led certification programs. Filter by discipline, then request your place —
                our team confirms dates and invoicing within one business day.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('courses.index') }}"
               @class([
                   'chip px-4 py-2 text-sm',
                   'bg-navy-900 text-white' => ! $activeCategory,
                   'bg-navy-50 text-navy-700 hover:bg-navy-100' => $activeCategory,
               ])>All courses</a>
            @foreach ($categories as $category)
                <a href="{{ route('courses.index', ['category' => $category->slug]) }}"
                   @class([
                       'chip px-4 py-2 text-sm',
                       'bg-navy-900 text-white' => $activeCategory === $category->slug,
                       'bg-navy-50 text-navy-700 hover:bg-navy-100' => $activeCategory !== $category->slug,
                   ])>{{ $category->name }}</a>
            @endforeach
        </div>

        @if ($courses->isEmpty())
            <div class="mt-12 flex flex-col items-center rounded-xl border border-navy-100 bg-navy-50 px-6 py-14 text-center">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-navy-100 text-navy-500">
                    <x-ui-icon name="academic-cap" class="h-6 w-6" />
                </span>
                <p class="mt-4 text-navy-600">No courses match this filter yet.</p>
                <a href="{{ route('courses.index') }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-navy-800 hover:text-navy-950">
                    <x-ui-icon name="arrow-right" class="h-4 w-4" />
                    View all courses
                </a>
            </div>
        @else
            <p class="mt-8 text-sm text-navy-500">
                Showing <span class="font-semibold text-navy-800">{{ $courses->total() }}</span>
                {{ \Illuminate\Support\Str::plural('course', $courses->total()) }}.
            </p>

            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($courses as $i => $course)
                    <div data-reveal style="--reveal-delay: {{ ($i % 3) * 80 }}ms">
                        <x-course-card :course="$course" />
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $courses->links() }}
            </div>
        @endif
    </section>
</x-layouts.app>
