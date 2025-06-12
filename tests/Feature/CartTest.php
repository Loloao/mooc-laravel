<?php
namespace Tests\Feature;

use App\Models\Goods\GoodsProduct;
use App\Models\User\User;
use App\Services\Goods\GoodsServices;
use App\Services\Order\CartServices;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseTransactions;


    /**
     * @var GoodsProduct $goodsProduct
     */
    private $product;
    private $authHeader;

    public function setUp(): void
    {
        parent::setUp();
        $this->product    = GoodsProduct::factory()->create();
        $this->authHeader = $this->getAuthHeader($this->user->username, 'password');
    }

    public function testFastAdd()
    {

        $this->post('wx/cart/add', [
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 2,
        ], $this->authHeader);

        $res = $this->post('wx/cart/fastAdd', [
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 5,
        ]);
        $res->assertJson(['errno' => 0, 'errmsg' => '成功']);

        $cart = CartServices::getInstance()->getCartProduct($this->user->id, $this->product->goods_id, $this->product->id);
        $this->assertEquals(5, $cart->number);

        $res->assertJson(['errno' => 0, 'errmsg' => '成功', 'data' => $cart->id]);
    }

    public function testAdd()
    {
        $res = $this->post('wx/cart/add', [
            'goodsId'   => 0,
            'productId' => 0,
            'number'    => 2,
        ], $this->authHeader);
        $res->assertJson(['errno' => 401]);
        $res = $this->post('wx/cart/add', [
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 2,
        ], $this->authHeader);

        $res->assertJson(['errno' => 0, 'errmsg' => '成功', 'data' => '2']);

        $res = $this->post('wx/cart/add', [
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 3,
        ], $this->authHeader);
        $res->assertJson([
            'errno'  => 0,
            'errmsg' => '成功',
            'data'   => '5',
        ]);

    }

    public function testUpdate()
    {
        $res = $this->post('wx/cart/add', [
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 2,
        ], $this->authHeader);
        $res->assertJson(['errno' => 0, 'errmsg' => '成功', 'data' => '2']);

        $cart = CartServices::getInstance()->getCartProduct($this->user->id, $this->product->goods_id, $this->product->id);
        $res  = $this->post('wx/cart/update', [
            'id'        => $cart->id,
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 6,
        ]);
        $res->assertJson(['errno' => 0, 'errmsg' => '成功']);

        $res = $this->post('wx/cart/update', [
            'id'        => $cart->id,
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 101,
        ]);
        $res->assertJson(['errno' => 711, 'errmsg' => '库存不足']);

        $res = $this->post('wx/cart/update', [
            'id'        => $cart->id,
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 0,
        ]);
        $res->assertJson(['errno' => 401]);
    }

    public function testDelete()
    {
        $res = $this->post('wx/cart/add', [
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 2,
        ], $this->authHeader);
        $res->assertJson(['errno' => 0, 'errmsg' => '成功', 'data' => '2']);

        $cart = CartServices::getInstance()->getCartProduct($this->user->id, $this->product->goods_id, $this->product->id);
        $this->assertNotNull($cart);

        $this->post('wx/cart/delete', [
            'productIds' => [$this->product->id],
        ], $this->authHeader);

        $cart = CartServices::getInstance()->getCartProduct($this->user->id, $this->product->goods_id, $this->product->id);
        $this->assertNull($cart);

        $res = $this->post('wx/cart/delete', [
            'productIds' => [],
        ], $this->authHeader);
        $res->assertJson(['errno' => 401]);
    }

    public function testChecked()
    {
        $res = $this->post('wx/cart/add', [
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 2,
        ], $this->authHeader);
        $res->assertJson(['errno' => 0, 'errmsg' => '成功', 'data' => '2']);

        $cart = CartServices::getInstance()->getCartProduct($this->user->id, $this->product->goods_id, $this->product->id);
        $this->assertTrue($cart->checked);

        $this->post('wx/cart/checked', [
            'productIds' => [$this->product->id],
            'isChecked'  => 0,
        ], $this->authHeader);

        $cart = CartServices::getInstance()->getCartProduct($this->user->id, $this->product->goods_id, $this->product->id);
        $this->assertFalse($cart->checked);

        $this->post('wx/cart/checked', [
            'productIds' => [$this->product->id],
            'isChecked'  => 1,
        ], $this->authHeader);

        $cart = CartServices::getInstance()->getCartProduct($this->user->id, $this->product->goods_id, $this->product->id);
        $this->assertTrue($cart->checked);
    }

    public function testIndex()
    {
        $res = $this->post('wx/cart/add', [
            'goodsId'   => $this->product->goods_id,
            'productId' => $this->product->id,
            'number'    => 2,
        ], $this->authHeader);
        $res = $this->get('wx/cart/index', []);
        $res->assertJson([
            "errno" => 0, "errmsg" => '成功', 'data' => [
                'cartList'  => [
                    ['goodsId' => $this->product->goods_id, 'productId' => $this->product->id],
                ],
                'cartTotal' => [
                    'goodsCount'         => 2,
                    'goodsAmount'        => '1998.00',
                    'checkedGoodsCount'  => 2,
                    'checkedGoodsAmount' => '1998.00',
                ],
            ],
        ]);

        $goods             = GoodsServices::getInstance()->getGoods($this->product->goods_id);
        $goods->is_on_sale = false;
        $goods->save();

        $res = $this->get('wx/cart/index');

        $res->assertJson([
            "errno"  => 0,
            "errmsg" => '成功',
            'data'   => [
                'cartList'  => [],
                'cartTotal' => [
                    'goodsCount'         => 0,
                    'goodsAmount'        => '0',
                    'checkedGoodsCount'  => 0,
                    'checkedGoodsAmount' => 0,
                ],
            ],
        ]);

        $cart = CartServices::getInstance()->getCartProduct($this->user->id, $this->product->goods_id, $this->product->id);
        $this->assertNull($cart);
    }

    public function testCheckout()
    {
        $this->get('wx/cart/checkout');

    }
}