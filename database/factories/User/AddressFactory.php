<?php
namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class AddressFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username'       => fake()->name(),
            'user_id'        => 0,
            'province'       => '浙江省',
            'city'           => '杭州市',
            'country'        => '西湖区',
            'address_detail' => fake()->streetAddress(),
            'area_code'      => '',
            'postal_code'    => fake()->postcode(),
            'tel'            => fake()->phoneNumber(),
            'is_default'     => 0,
        ];
    }

}
