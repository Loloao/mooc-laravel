<?php
namespace App\Services\Goods;

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
    /**
     * 获取在售商品数量
     * @return int
     */
    public function countGoodsOnSale()
    {
        return Goods::query()->where('is_on_sale', 1)->where('deleted', 0)->count('id');
    }

    public function listGoods($categoryId, $brandId, $isNew, $isHot, $keyword, $columns = ['*'], $sort = 'add_time', $order = 'desc', $page = 1, $limit = 10)
    {
        $query = $this->getQueryByGoodsFilter($brandId, $isNew, $isHot, $keyword);
        if (! empty($categoryId)) {
            $query = $query->where('category_id', $categoryId);
        }

        return $query->orderby($sort, $order)->paginate($limit, $columns, 'page', $page);
    }

    public function listL2Category($brandId, $isNew, $isHot, $keyword)
    {
        $query       = $this->getQueryByGoodsFilter($brandId, $isNew, $isHot, $keyword);
        $categoryIds = $query->select(['category_id'])->pluck('category_id')->unique()->toArray();
        return CatalogServices::getInstance()->getL2ListByIds($categoryIds);
    }

    private function getQueryByGoodsFilter($brandId, $isNew, $isHot, $keyword)
    {

        $query = Goods::query()->where('is_on_sale', 1)->where('deleted', 0);

        if (! empty($brandId)) {
            $query = $query->where('brand_id', $brandId);
        }

        if (! is_null($isNew)) {
            $query = $query->where('is_new', $isNew);
        }

        if (! is_null($isHot)) {
            $query = $query->where('is_hot', $isHot);
        }

        if (! empty($keyword)) {
            $query = $query->where(function (Builder $query) use ($keyword) {
                $query->where('keywords', 'like', "%$keyword%")->orWhere('name', 'like', "%$keyword%");
            });
        }

        return $query;
    }

    public function getGoods(int $id)
    {
        return Goods::query()->find($id);
    }

    public function getGoodsAttribute(int $goodsId)
    {
        return GoodsAttribute::query()->where('goods_id', $goodsId)->where('deleted', 0)->get();
    }

    public function getGoodsSpecification(int $goodsId)
    {
        $spec = GoodsSpecification::query()->where('goods_id', $goodsId)->where('deleted', 0)->get()->groupBy('specification');
        return $spec->map(function ($v, $k) {
            return ['name' => $k, 'valueList' => $v];
        });
    }

    public function getGoodsProduct(int $goodsId)
    {
        return GoodsProduct::query()->where('goods_id', $goodsId)->where('deleted', 0)->get();
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