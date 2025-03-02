<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Storage>
 */
class StorageFactory extends Factory
{
    protected $model = Storage::class;
    public function definition()
    {
        $uom = $this->faker->randomElement(['GB', 'MB']);
        $totalSize = $this->faker->numberBetween(100, 1000);
        $freeSpace = $this->faker->numberBetween(10, $totalSize);
    
        // Convert MB to GB for threshold calculations (if needed)
        $totalSizeGB = $uom === 'MB' ? $totalSize / 1024 : $totalSize;
        $freeSpaceGB = $uom === 'MB' ? $freeSpace / 1024 : $freeSpace;
    
        return [
            'svrip' => $this->faker->ipv4(),
            'server_name' => $this->faker->randomElement(['Web Server', 'DB Server']),
            'drvletter' => $this->faker->randomElement(['C', 'D', 'E']),
            'drvsizetotal' => $totalSize,
            'drvsize_free' => $freeSpace,
            'uom' => $uom, // Store unit (GB/MB)
            'datecrt' => now(),
            'timecrt' => now()->format('H:i:s'),
        ];
}
}