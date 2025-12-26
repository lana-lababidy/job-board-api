<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jobb>
 */
class JobbFactory extends Factory
{
        protected $model = \App\Models\Jobb::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'title' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'location' => $this->faker->city,
            'type' => $this->faker->randomElement(['Full-Time','Part-Time']),
            'company_id' => \App\Models\Company::factory(), // يربط الوظيفة بشركة جديدة تلقائيًا
        
        ];
    }
}
