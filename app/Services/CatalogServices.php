<?php

namespace App\Services;

use App\Models\Goods\Category;
use Illuminate\Database\Eloquent\Collection;

class CatalogServices extends BaseServices
{

    /**
     * 获取一级类目
     * @return Category[] | Collection
     */
    public function getL1List()
    {
        return Category::query()->where('level', 'L1')->where('deleted', 0)->get();
    }

    /**
     * 根据一级类目 id 获取二级类目
     * @param int $pid
     * @return Category[] | Collection
     */
    public function getL2ListByPid(int $pid)
    {
        return Category::query()->where('level', 'L2')->where('pid', $pid)->where('deleted', 0)->get();
    }

    /**
     * 根据  id 获取一级类目
     * @param int $id
     * @return Category|null|\Illuminate\Database\Eloquent\Model
     */
    public function getL1ListById(int $id)
    {
        return Category::query()->where('level', 'L1')->where('id', $id)->where('deleted', 0)->first();
    }
}
