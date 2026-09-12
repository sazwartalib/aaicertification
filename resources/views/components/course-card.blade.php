@props(['course'])

@php
    use App\Enums\CourseLevel;

    $levelStyles = match ($course->level) {
        CourseLevel::Beginner => 'bg-green-100 text-green-800',
        CourseLevel::Intermediate => 'bg-amber-100 text-amber-800',
        CourseLevel::Advanced => 'bg-rose-100 text-rose-800',
    };
    $levelDot = match ($course->level) {
        CourseLevel::Beginner => 'bg-green-500',
        CourseLevel::Intermediate => 'bg-amber-500',
        CourseLevel::Advanced => 'bg-rose-500',
    };
@endphp

<article class="group card-interactive relative flex h-full flex-col overflow-hidden">
    {{-- Accent bar reveals on hover --}}
    <span class="absolute inset-x-0 top-0 h-1 origin-left scale-x-0 bg-gold-500 transition-transform duration-300 group-hover:scale-x-100"></span>

    <div class="flex items-center justify-between gap-3">
        @if ($course->category)
            <span class="chip bg-navy-50 text-navy-700">
                {{ $course->category->name }}
            </span>
        @endif
        <span class="badge {{ $levelStyles }}">
            <span class="h-1.5 w-1.5 rounded-full {{ $levelDot }}"></span>
            {{ $course->level->getLabel() }}
        </span>
    </div>

    <h3 class="mt-4 text-lg font-semibold text-navy-900">
        <a href="{{ route('courses.show', $course) }}" class="after:absolute after:inset-0">
            <span class="bg-gradient-to-r from-navy-900 to-navy-900 bg-[length:0%_2px] bg-left-bottom bg-no-repeat transition-[background-size] duration-300 group-hover:bg-[length:100%_2px]">
                {{ $course->title }}
            </span>
        </a>
    </h3>

    <p class="mt-2 flex-1 text-sm leading-relaxed text-navy-600">
        {{ \Illuminate\Support\Str::limit($course->summary, 140) }}
    </p>

    <dl class="mt-5 grid grid-cols-2 gap-3 border-t border-navy-100 pt-4 text-sm">
        <div class="flex items-center gap-2">
            <x-ui-icon name="clock" class="h-4 w-4 shrink-0 text-navy-400" />
            <div>
                <dt class="text-xs uppercase tracking-wide text-navy-400">Duration</dt>
                <dd class="font-medium text-navy-800">{{ $course->duration }}</dd>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-ui-icon name="video-camera" class="h-4 w-4 shrink-0 text-navy-400" />
            <div>
                <dt class="text-xs uppercase tracking-wide text-navy-400">Delivery</dt>
                <dd class="font-medium text-navy-800">{{ $course->delivery_mode->getLabel() }}</dd>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-ui-icon name="banknotes" class="h-4 w-4 shrink-0 text-navy-400" />
            <div>
                <dt class="text-xs uppercase tracking-wide text-navy-400">Fee</dt>
                <dd class="font-medium text-navy-800">
                    {{ $course->price ? 'RM '.number_format((float) $course->price) : 'On request' }}
                </dd>
            </div>
        </div>
        @if ($course->accreditation_body)
            <div class="flex items-center gap-2">
                <x-ui-icon name="shield-check" class="h-4 w-4 shrink-0 text-navy-400" />
                <div>
                    <dt class="text-xs uppercase tracking-wide text-navy-400">Accreditation</dt>
                    <dd class="font-medium text-navy-800">{{ $course->accreditation_body }}</dd>
                </div>
            </div>
        @endif
    </dl>

    <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-navy-700 transition-colors group-hover:text-navy-900">
        View course
        <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" />
    </span>
</article>
