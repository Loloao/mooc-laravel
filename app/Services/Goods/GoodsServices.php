<?php
namespace App\Services\Goods;

use App\Inputs\GoodsListInput;
use App\Models\Goods\FootPrint;
use App\Models\Goods\Goods;
use App\Models\Goods\GoodsAttribute;
use App\Models\Goods\GoodsProduct;
use App\Models\Goods\GoodsSpecification;
use App\Models\Goods\Issue;
use App\Services\BaseServices;
use Illuminate\Contracts\Database\Eloquent\Builder;

class GoodsServices extends BaseServices
{

    public function getGoodsListByIds(array $ids)
    {

        if (empty($ids)) {
            return collect();
        }
        return Goods::query()->whereIn('id', $ids)->get();
    }
    /**
     * 获取在售商品数量
     * @return int
     */
    public function countGoodsOnSale()
    {
        return Goods::query()->where('is_on_sale', 1)->count('id');
    }

    public function listGoods(GoodsListInput $input, $columns)
    {
        $query = $this->getQueryByGoodsFilter($input);
        if (! empty($categoryId)) {
            $query = $query->where('category_id', $categoryId);
        }

        return $query->orderby($input->sort, $input->order)->paginate($input->limit, $columns, 'page', $input->page);
    }

    public function listL2Category(GoodsListInput $input)
    {
        $query       = $this->getQueryByGoodsFilter($input);
        $categoryIds = $query->select(['category_id'])->pluck('category_id')->unique()->toArray();
        return CatalogServices::getInstance()->getL2ListByIds($categoryIds);
    }

    private function getQueryByGoodsFilter(GoodsListInput $input)
    {

        $query = Goods::query()->where('is_on_sale', 1);

        if (! empty($input->brandId)) {
            $query = $query->where('brand_id', $input->brandId);
        }

        if (! is_null($input->isNew)) {
            $query = $query->where('is_new', $input->isNew);
        }

        if (! is_null($input->isHot)) {
            $query = $query->where('is_hot', $input->isHot);
        }

        if (! empty($input->keyword)) {
            $query = $query->where(function (Builder $query) use ($input) {
                $query->where('keywords', 'like', "%$input->keyword%")->orWhere('name', 'like', "%$input->keyword%");
            });
        }

        return $query;
    }

    public function getGoods(int $id)
    {
        $goods = Goods::query()->find($id);
        return $goods;
    }

    public function getGoodsAttribute(int $goodsId)
    {
        return GoodsAttribute::query()->where('goods_id', $goodsId)->get();
    }

    public function getGoodsSpecification(int $goodsId)
    {
        $spec = GoodsSpecification::query()->where('goods_id', $goodsId)->get()->groupBy('specification');
        return $spec->map(function ($v, $k) {
            return ['name' => $k, 'valueList' => $v];
        });
    }

    public function getGoodsProduct(int $goodsId)
    {
        return GoodsProduct::query()->where('goods_id', $goodsId)->get();
    }

    public function getGoodsProductById(int $id)
    {
        return GoodsProduct::query()->find($id);
    }

    public function getGoodsIssue($page = 1, $limit = 4)
    {
        return Issue::query()->forPage($page, $limit)->get();
    }

    public function saveFootPrint($userId, $goodsId)
    {
        $footPrint = new FootPrint();
        $footPrint = $footPrint->fill(['user_id' => $userId, 'goods_id' => $goodsId]);
        return $footPrint->save();
    }
}