<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Admin>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '0812' . fake()->numerify('########'),
            'password' => Hash::make('HumoraAdmin2026!'),
            'role' => 'super_admin',
            'is_active' => true,
        ];
    }

    public function contentAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'content_admin',
        ]);
    }

    public function financeAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'finance_admin',
        ]);
    }
}
