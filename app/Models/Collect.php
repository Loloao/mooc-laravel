<?php
namespace App\Models;

use App\Models\BaseModel;

class Collect extends BaseModel
{
    protected $table = 'collect';

    protected $casts = [
        'deleted'  => 'boolean',
        'pic_list' => 'array',
    ];
}