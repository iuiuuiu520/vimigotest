<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $course = fake()->randomElement(['IT','Business']);

        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'address' => fake()->address(),
            'study course' => $course
        ];
    }
}
