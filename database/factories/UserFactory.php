<?php

declare(strict_types=1);

namespace Database\Factories;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;

        if ($faker === null && class_exists(FakerFactory::class)) {
            $faker = FakerFactory::create();
        }

        $name = $faker?->name() ?? 'Fleet Forge User';
        $email = $faker ? $faker->unique()->safeEmail() : (string) Str::uuid().'@example.com';
        $cash = $faker ? $faker->randomFloat(2, 0, 10000) : round(mt_rand(0, 1_000_000) / 100, 2);

        return [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'cash' => $cash,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
