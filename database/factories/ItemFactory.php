<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $itemName = $this->faker->unique()->randomElement([
            'Arduino Uno R3',
            'ESP32 Dev Module',
            'Sensor Suhu DHT11',
            'Modul Relay 4 Channel',
            'Breadboard MB-102',
            'Kabel Jumper Male-Female',
            'Motor Servo SG90',
            'Sensor Kelembaban Tanah',
        ]);
        
        $totalQuantity = $this->faker->numberBetween(10, 50);
        $availableQuantity = $this->faker->numberBetween(5, $totalQuantity);

        return [
            'name' => $itemName,
            'code' => 'IOT-' . $this->faker->unique()->randomNumber(4) . '-' . strtoupper(substr($itemName, 0, 3)),
            'total_quantity' => $totalQuantity,
            'available_quantity' => $availableQuantity,
            'description' => $this->faker->paragraph(3),
            'condition' => $this->faker->randomElement(['Baik', 'Baik', 'Baik', 'Rusak Ringan', 'Rusak Berat']),
            'photo_path' => null,
        ];
    }

    public function broken(): static
    {
        return $this->state(fn () => [
            'condition' => 'Rusak Berat',
            'available_quantity' => 0,
        ]);
    }
}