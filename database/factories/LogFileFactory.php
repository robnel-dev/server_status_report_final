<?php

namespace Database\Factories;

use App\Models\LogFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogFile>
 */
class LogFileFactory extends Factory
{
    protected $model = LogFile::class;

    public function definition()
    {
        return [
            'svrip' => $this->faker->randomElement(['192.168.1.239', $this->faker->ipv4()]),
            'filename' => $this->faker->regexify('[A-Za-z0-9]{10}') . '.log',
            'filesize' => $this->faker->numberBetween(1024, 1048576),
            'datecrt' => now(),
            'timecrt' => $this->faker->time(),
        ];
    }
}
