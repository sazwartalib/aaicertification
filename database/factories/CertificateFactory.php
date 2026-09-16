<?php

namespace Database\Factories;

use App\Enums\CertificateStatus;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issuedAt = Carbon::instance(fake()->dateTimeBetween('-2 years', 'now'));

        return [
            'uuid' => (string) Str::uuid(),
            'certificate_number' => 'AAI-'.$issuedAt->year.'-'.fake()->unique()->numerify('#####'),
            'recipient_name' => fake()->name(),
            'ic_number' => fake()->date('ymd', '-20 years').'-'.fake()->numerify('##').'-'.fake()->numerify('####'),
            'course_id' => Course::factory(),
            'course_title' => fn (array $attributes) => Course::find($attributes['course_id'])?->title ?? fake()->catchPhrase(),
            'issued_at' => $issuedAt->toDateString(),
            'expires_at' => fake()->boolean(70) ? $issuedAt->copy()->addYears(3)->toDateString() : null,
            'status' => CertificateStatus::Valid,
            'grade' => fake()->randomElement(['Pass', 'Merit', 'Distinction', null]),
        ];
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => CertificateStatus::Revoked]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes): array => [
            'issued_at' => now()->subYears(5)->toDateString(),
            'expires_at' => now()->subYear()->toDateString(),
        ]);
    }
}
