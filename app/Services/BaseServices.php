<?php

namespace App\Services;

use App\Exceptions\BusinessException;

class BaseServices
{
    private static $instance;

    private function __construct()
    {
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance instanceof static ) {
            return static::$instance;
        }
        static::$instance = new static();
        return static::$instance;
    }

    private function __clone()
    {
    }

    public function throwBusinessException(array $CodeResponse)
    {
        throw new BusinessException($CodeResponse);
    }
}