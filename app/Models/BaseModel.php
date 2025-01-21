<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class BaseModel extends Model
{

    public function toArray()
    {
        $items = parent::toArray();
        $keys = array_keys($items);
        $keys = array_map(function ($keys) {
            return lcfirst(Str::studly($keys));
        }, $keys);

        $values = array_values($items);
        return array_combine($keys, $values);
    }
}