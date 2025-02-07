<?php
namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Str;

class BaseModel extends Model
{

    public const CREATED_AT = 'add_time';
    public const UPDATED_AT = 'update_time';

    public function toArray()
    {
        $items = parent::toArray();
        $keys  = array_keys($items);
        $keys  = array_map(function ($keys) {
            return lcfirst(Str::studly($keys));
        }, $keys);

        $values = array_values($items);
        return array_combine($keys, $values);
    }

    public function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->toDateTimeString();
    }
}
