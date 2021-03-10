<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'device_id' => Device::all()->random()->id,
            'condition' => collect(["EXCELLENT", "GOOD", "ACCEPTABLE", "BROKEN"])->random(),
            'price' => $this->faker->randomNumber(4),
        ];
    }
}
