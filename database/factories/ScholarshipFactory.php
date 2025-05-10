<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scholarship>
 */
class ScholarshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(4),
            'organization' => $this->faker->title(),
            'eligibility' => $this->faker->text(),
            'category' => $this->faker->randomElement(['merit-based', 'need-based', 'field-specific', 'athletic', 'identity-based', 'goverment-funded', 'others']),
            'program' => $this->faker->randomElement(['Exchange', 'Diploma', 'Bachalor', 'Master',  'Phd', 'all']),
            // 'field' => $this->faker->randomElement([
            //     'Computer Science',
            //     'Engineering',
            //     'Business',
            //     'Medicine',
            //     'Law',
            //     'Education'
            // ]),
            'field' => $this->faker->text(),
            'country' => $this->faker->country(),
            'deadline' => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'apply_link' => $this->faker->url(),
            'photo_link' => $this->faker->url(),
        ];
    }
}
