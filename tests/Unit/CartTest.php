<?php
namespace Tests\Unit;

use App\Models\Goods\GoodsProduct;
use App\Models\Promotion\GrouponRules;
use App\Services\Order\CartServices;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetCartPriceCutSimple()
    {
        $product1 = GoodsProduct::factory()->create(['price' => 11.3]);
        $product2 = GoodsProduct::factory()->create(['price' => 20.56]);
        $product3 = GoodsProduct::factory()->create(['price' => 10.6]);
        CartServices::getInstance()->add($this->user->id, $product1->goods_id, $product1->id, 2);
        CartServices::getInstance()->add($this->user->id, $product2->goods_id, $product2->id, 1);
        CartServices::getInstance()->add($this->user->id, $product3->goods_id, $product3->id, 3);
        CartServices::getInstance()->updateChecked($this->user->id, [$product3->id], false);

        $checkedGoodsList  = CartServices::getInstance()->getCheckedCartList($this->user->id);
        $grouponPrice      = 0;
        $checkedGoodsPrice = CartServices::getInstance()->getCartPriceCutGroupon($checkedGoodsList, null, $grouponPrice);
        $this->assertEquals($checkedGoodsPrice, 43.16);
    }

    public function testGetCartPriceCutGroupon()
    {

        $product1 = GoodsProduct::factory()->create(['price' => 11.3]);
        $product2 = GoodsProduct::factory()->groupon()->create(['price' => 20.56]);
        $product3 = GoodsProduct::factory()->create(['price' => 10.6]);
        CartServices::getInstance()->add($this->user->id, $product1->goods_id, $product1->id, 2);
        CartServices::getInstance()->add($this->user->id, $product2->goods_id, $product2->id, 1);
        CartServices::getInstance()->add($this->user->id, $product3->goods_id, $product3->id, 3);
        CartServices::getInstance()->updateChecked($this->user->id, [$product3->id], false);

        $checkedGoodsList  = CartServices::getInstance()->getCheckedCartList($this->user->id);
        $grouponPrice      = 0;
        $rulesId           = GrouponRules::whereGoodsId($product2->goods_id)->first()->id ?? null;
        $checkedGoodsPrice = CartServices::getInstance()->getCartPriceCutGroupon($checkedGoodsList, $rulesId, grouponPrice: $grouponPrice);
        $this->assertEquals($checkedGoodsPrice, 33.16);
    }
}
