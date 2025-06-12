<?php
namespace Database\Factories\Goods;

use App\Models\Goods\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class GoodsSpecificationFactory extends Factory
{

    public function definition()
    {

        $goods = Goods::factory()->create();
        return [
            'goods_id'      => $goods->id,
            'specification' => '规格',
            'value'         => '标准',
        ];
    }
}