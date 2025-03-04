<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\StaticQRCode;
use App\Models\User;

class StaticQRCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StaticQRCode::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'filename' => fake()->word(),
            'content_type' => fake()->randomElement(["text","email","phone","sms","whatsapp","skype","location","vcard","event","bookmark","wifi","paypal","bitcoin","2fa"]),
            'content' => fake()->paragraphs(3, true),
            'foreground_color' => fake()->word(),
            'background_color' => fake()->word(),
            'precision' => fake()->randomElement(["L","M","Q","H"]),
            'size' => fake()->numberBetween(-10000, 10000),
            'format' => fake()->randomElement(["png","svg","eps"]),
        ];
    }
}
