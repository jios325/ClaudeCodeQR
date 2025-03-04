<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\QrCode;
use App\Models\ScanLog;

class ScanLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScanLog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'qr_code_id' => QrCode::factory(),
            'qr_code_type' => fake()->randomElement(["dynamic","static"]),
            'ip_address' => fake()->word(),
            'user_agent' => fake()->word(),
            'timestamp' => fake()->dateTime(),
        ];
    }
}
