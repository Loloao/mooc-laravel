<?php
namespace Database\Factories\User;

use App\Models\User\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->name(),
            'password' => static::$password ??= Hash::make('password'),
            'gender'   => fake()->randomKey([0, 1, 2]),
            'mobile'   => fake()->phoneNumber(),
            'avatar'   => fake()->imageUrl(),
        ];
    }

    public function defaultAddress()
    {
        $this->state(function () {
            return [];
        })->afterCreating(function ($user) {
            Address::factory()->create([
                'user_id'    => $user->id,
                'is_default' => 1,
            ]);
        });
    }

}