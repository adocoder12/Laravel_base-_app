<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Estado para crear un Administrador
     */
    public function admin(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::Admin->value);
        });
    }

    /**
     * Estado para crear un Staff
     */
    public function staff(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::Staff->value);
        });
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
