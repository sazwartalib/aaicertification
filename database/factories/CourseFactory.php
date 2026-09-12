<?php

namespace Database\Factories;

use App\Enums\CourseLevel;
use App\Enums\DeliveryMode;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title(fake()->unique()->catchPhrase());

        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'summary' => fake()->sentence(14),
            'description' => collect(fake()->paragraphs(4))
                ->map(fn (string $p): string => "<p>{$p}</p>")
                ->implode(''),
            'level' => fake()->randomElement(CourseLevel::cases()),
            'delivery_mode' => fake()->randomElement(DeliveryMode::cases()),
            'duration' => fake()->randomElement(['1 day', '2 days', '3 days', '5 days', '4 weeks']),
            'price' => fake()->randomElement([950, 1250, 1800, 2400, 3200]),
            'accreditation_body' => fake()->randomElement(['ISO', 'PECB', 'CompTIA', 'PMI', null]),
            'is_featured' => fake()->boolean(30),
            'is_published' => true,
            'image_path' => null,
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes): array => ['is_published' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => ['is_featured' => true]);
    }
}
