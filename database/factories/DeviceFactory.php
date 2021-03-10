<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'model' => $this->faker->colorName,
            'brand' => $this->faker->company,
            'image' => $this->faker->imageUrl(),
            'base_price' => $this->faker->randomNumber(4),
            'excellent_factor' => $this->faker->randomNumber(2),
            'good_factor' => $this->faker->randomNumber(2),
            'acceptable_factor' => $this->faker->randomNumber(2),
            'broken_factor' => $this->faker->randomNumber(2),
        ];
    }
}
