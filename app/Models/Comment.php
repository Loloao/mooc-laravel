<?php
namespace App\Models;

use App\Models\BaseModel;

class Comment extends BaseModel
{
    protected $table = 'comment';

    protected $casts = [
        'deleted'  => 'boolean',
        'pic_list' => 'array',
    ];
}