<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
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
        return [
            'name' => fake()->firstName() . ' ' . fake()->lastName(),
            'parent_name' => fake()->firstName() . ' ' . fake()->lastName(),
            'phone' => '(' . fake()->numerify('##') . ') ' . fake()->numerify('9####-####'),
            'birth_date' => fake()->dateTimeBetween('-15 years', '-7 years'),
            'fee' => fake()->randomElement([150.00, 200.00, 250.00, 300.00]),
            'due_day' => fake()->numberBetween(1, 28),
            'active' => true,
            'team_id' => Team::factory()->create()->id,
            'responsavel_id' => User::factory()->create(['role' => 'responsavel'])->id,
            'notes' => null,
            'class_start_time' => null,
            'class_end_time' => null,
        ];
    }
}
