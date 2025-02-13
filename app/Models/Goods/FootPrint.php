<?php
namespace App\Models\Goods;

use App\Models\BaseModel;

class FootPrint extends BaseModel
{
    protected $table = 'foot_print';

    protected $fillable = [
        'user_id',
        'goods_id',
    ];
    protected $casts = [
        'deleted' => 'boolean',
    ];

}