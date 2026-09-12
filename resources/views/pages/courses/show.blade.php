<x-layouts.app :title="$course->title" :description="$course->summary">
    <section class="relative overflow-hidden bg-navy-950 py-16">
        <div class="absolute inset-0 bg-grid"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-navy-300 transition hover:text-white">
                <x-ui-icon name="arrow-right" class="h-4 w-4 rotate-180" />
                All courses
            </a>
            <div class="mt-4 flex flex-wrap items-center gap-3">
                @if ($course->category)
                    <span class="badge bg-navy-800 text-navy-100">{{ $course->category->name }}</span>
                @endif
                <span class="badge bg-gold-500 text-navy-950">{{ $course->level->getLabel() }}</span>
            </div>
            <h1 class="mt-4 max-w-3xl text-4xl font-bold text-white">{{ $course->title }}</h1>
            <p class="mt-4 max-w-3xl text-lg text-navy-200">{{ $course->summary }}</p>

            <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm text-navy-300">
                <span class="flex items-center gap-2"><x-ui-icon name="clock" class="h-4 w-4 text-gold-400" /> {{ $course->duration }}</span>
                <span class="flex items-center gap-2"><x-ui-icon name="video-camera" class="h-4 w-4 text-gold-400" /> {{ $course->delivery_mode->getLabel() }}</span>
                <span class="flex items-center gap-2"><x-ui-icon name="banknotes" class="h-4 w-4 text-gold-400" /> {{ $course->price ? 'RM '.number_format((float) $course->price) : 'Fee on request' }}</span>
            </div>

            <a href="#enrol" class="btn-primary mt-8 lg:hidden">
                Request your place
                <x-ui-icon name="arrow-right" class="btn-arrow h-4 w-4" />
            </a>
        </div>
    </section>

    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div class="lg:col-span-2">
            <div class="prose-navy max-w-none">
                {!! $course->description !!}
            </div>

            @if ($relatedCourses->isNotEmpty())
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-navy-900">Related courses</h2>
                    <div class="mt-6 grid gap-6 sm:grid-cols-2">
                        @foreach ($relatedCourses as $related)
                            <x-course-card :course="$related" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <aside class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <dl class="rounded-xl border border-navy-100 bg-navy-50/50 p-6 text-sm">
                    @foreach ([
                        ['icon' => 'clock', 'label' => 'Duration', 'value' => $course->duration],
                        ['icon' => 'video-camera', 'label' => 'Delivery', 'value' => $course->delivery_mode->getLabel()],
                        ['icon' => 'academic-cap', 'label' => 'Level', 'value' => $course->level->getLabel()],
                    ] as $row)
                        <div class="flex items-center justify-between border-b border-navy-100 py-2.5">
                            <dt class="flex items-center gap-2 text-navy-500"><x-ui-icon :name="$row['icon']" class="h-4 w-4" /> {{ $row['label'] }}</dt>
                            <dd class="font-semibold text-navy-900">{{ $row['value'] }}</dd>
                        </div>
                    @endforeach
                    @if ($course->accreditation_body)
                        <div class="flex items-center justify-between border-b border-navy-100 py-2.5">
                            <dt class="flex items-center gap-2 text-navy-500"><x-ui-icon name="shield-check" class="h-4 w-4" /> Accreditation</dt>
                            <dd class="font-semibold text-navy-900">{{ $course->accreditation_body }}</dd>
                        </div>
                    @endif
                    <div class="flex items-center justify-between py-2.5">
                        <dt class="flex items-center gap-2 text-navy-500"><x-ui-icon name="banknotes" class="h-4 w-4" /> Fee</dt>
                        <dd class="font-semibold text-navy-900">{{ $course->price ? 'RM '.number_format((float) $course->price) : 'On request' }}</dd>
                    </div>
                </dl>

                <div id="enrol" class="card">
                    <h2 class="text-lg font-semibold text-navy-900">Request your place</h2>
                    <p class="mt-1 text-sm text-navy-600">No payment now — we confirm dates and send an invoice.</p>

                    <div class="mt-4">
                        <x-flash />
                    </div>

                    @if ($errors->any())
                        <div class="mt-4 flex items-start gap-2 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            <x-ui-icon name="exclamation-triangle" class="mt-0.5 h-4 w-4 shrink-0" />
                            <span>Please check the highlighted fields below.</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('enrollments.store', $course) }}" class="mt-5 space-y-4" data-loading-submit>
                        @csrf
                        <x-form-field name="name" label="Full name" icon="users" :value="old('name')" required />
                        <x-form-field name="email" label="Email" type="email" icon="envelope" :value="old('email')" required />
                        <x-form-field name="phone" label="Phone" icon="phone" :value="old('phone')" required />
                        <x-form-field name="company" label="Company (optional)" icon="presentation-chart" :value="old('company')" />

                        <div>
                            <label for="message" class="block text-sm font-medium text-navy-800">Anything we should know? (optional)</label>
                            <textarea id="message" name="message" rows="3"
                                class="field-input mt-1.5 @error('message') field-input-invalid @enderror">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-600"><x-ui-icon name="exclamation-triangle" class="h-3.5 w-3.5" /> {{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-navy btn-block" data-loading-label="Submitting…">
                            Submit enrolment request
                        </button>
                        <p class="flex items-center justify-center gap-1.5 text-xs text-navy-400">
                            <x-ui-icon name="lock-closed" class="h-3.5 w-3.5" />
                            Your details are only used to arrange this course.
                        </p>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</x-layouts.app>
