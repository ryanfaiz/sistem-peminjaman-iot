<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'identifier' => 'M' . fake()->unique()->numerify('##########'),
            'role' => 'mahasiswa',
            'whatsapp_number' => '628' . fake()->numerify('##########'),
            'id_card_photo_path' => 'id_cards/admin-default.jpg',
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'name' => 'Admin Utama',
            'email' => 'admin@iot.id',
            'identifier' => 'A' . fake()->unique()->numerify('####'),
            'role' => 'admin',
        ]);
    }

    public function dosen(): static
    {
        return $this->state(fn () => [
            'name' => fake()->name('male'),
            'identifier' => 'D' . fake()->unique()->numerify('######'),
            'role' => 'dosen',
        ]);
    }

}