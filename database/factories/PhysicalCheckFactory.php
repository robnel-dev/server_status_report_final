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
            'amber_alert' => $this->faker->boolean(10), 
            'remarks' => $this->faker->randomElement([
                'Checked and operating normally.',
                'Minor dust buildup, scheduled for cleaning.',
                'Unit not cooling properly – may require refrigerant check.',
                'Power supply issue detected. Maintenance team informed.',
                'Noise coming from compressor. Needs further inspection.',
                'No issues found during routine inspection.',
                'Sensor error flagged, under observation.',
            ]),
        ];
    }
}
