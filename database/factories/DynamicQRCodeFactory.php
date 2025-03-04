<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DynamicQRCode;
use App\Models\User;

class DynamicQRCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DynamicQRCode::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'filename' => fake()->word(),
            'redirect_identifier' => fake()->word(),
            'url' => fake()->url(),
            'foreground_color' => fake()->word(),
            'background_color' => fake()->word(),
            'precision' => fake()->randomElement(["L","M","Q","H"]),
            'size' => fake()->numberBetween(-10000, 10000),
            'scan_count' => fake()->numberBetween(-10000, 10000),
            'status' => fake()->boolean(),
        ];
    }
}
