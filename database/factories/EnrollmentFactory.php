<?php

namespace Database\Factories;

use App\Enums\EnrollmentStatus;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('+60 1#-### ####'),
            'company' => fake()->optional()->company(),
            'message' => fake()->optional()->sentence(12),
            'status' => fake()->randomElement(EnrollmentStatus::cases()),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => EnrollmentStatus::Pending]);
    }
}
