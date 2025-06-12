<?php
namespace App\Services;

use App\CodeResponse;
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

    /**
     * @param array $CodeResponse
     * @param mixed $info
     * @throws \App\Exceptions\BusinessException
     */
    public function throwBusinessException(array $CodeResponse, $info = '')
    {
        throw new BusinessException($CodeResponse, $info);
    }

    public function throwBadArgumentValue()
    {
        $this->throwBusinessException(CodeResponse::PARAM_VALUE_ILLEGAL);
    }
}