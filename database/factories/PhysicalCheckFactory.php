<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PhysicalCheck;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhysicalCheck>
 */
class PhysicalCheckFactory extends Factory
{
    protected $model = PhysicalCheck::class;

    public function definition()
    {
        return [
            'in_charge' => $this->faker->name(),
            'aircon_status' => $this->faker->randomElement(['Normal', 'Faulty']),
            'amber_alert' => $this->faker->boolean(20), // 20% chance of true
            'remarks' => $this->faker->sentence(),
        ];
    }
}
