<?php
namespace Database\Factories\Goods;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class GoodsFactory extends Factory
{
    public function definition()
    {
        return [
            'goods_sn'      => fake()->word,
            'name'          => '测试商品' . fake()->word,
            'category_id'   => 1008009,
            'brand_id'      => 0,
            'gallery'       => "",
            'keywords'      => "",
            'brief'         => '测试',
            'is_on_sale'    => 1,
            'sort_order'    => fake()->numberBetween(1, 999),
            'pic_url'       => fake()->imageUrl(),
            'share_url'     => fake()->url,
            'is_new'        => fake()->boolean,
            'is_hot'        => fake()->boolean,
            'unit'          => '件',
            'counter_price' => 919,
            'retail_price'  => 899,
            'detail'        => fake()->text,

        ];
    }
}