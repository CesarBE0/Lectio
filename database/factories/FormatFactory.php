<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Format>
 */
class FormatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => \App\Models\Book::factory(),
            'type' => 'Tapa dura',
            'price' => 20.00,
            'stock' => 10,
        ];
    }
}
