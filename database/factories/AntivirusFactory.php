<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Antivirus;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Antivirus>
 */
class AntivirusFactory extends Factory
{
    protected $model = Antivirus::class;

    public function definition()
    {
        return [
            'svrip' => $this->faker->ipv4(),
            'last_update' => now()->subDays($this->faker->numberBetween(1, 7)),
            'datecrt' => now(),
            'timecrt' => $this->faker->time(),
        ];
    }
}
