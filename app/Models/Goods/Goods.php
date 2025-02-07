<?php
namespace App\Models\Goods;

use App\Models\BaseModel;

class Goods extends BaseModel
{
    protected $casts = [
        'deleted'       => 'boolean',
        'counter_price' => 'float',
        'retail_price'  => 'float',
        'is_new'        => 'boolean',
        'is_hot'        => 'boolean',

    ];
}
