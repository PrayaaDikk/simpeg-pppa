<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),

            'password' => Hash::make('password'),

            'role' => $this->faker->randomElement([
                'admin',
                'pegawai'
            ]),

            'pegawai_id' => Pegawai::factory(),
        ];
    }
}
