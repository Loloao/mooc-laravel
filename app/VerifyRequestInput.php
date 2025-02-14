<?php
namespace App;

use App\Exceptions\BusinessException;

trait VerifyRequestInput
{

    /**
     * 验证 id
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     * @throws BusinessException
     */
    public function verifyId($key, $default = null)
    {
        return $this->verifyData($key, $default, 'integer|digits_between:1,20');
    }
    /**
     * 验证字符串
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     * @throws BusinessException
     */
    public function verifyString($key, $default = null)
    {
        return $this->verifyData($key, $default, 'string');
    }

    /**
     * 验证布尔值
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     * @throws BusinessException
     */
    public function verifyBoolean($key, $default = null)
    {
        return $this->verifyData($key, $default, 'boolean');
    }

    /**
     * 验证整数
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     * @throws BusinessException
     */
    public function verifyInteger($key, $default = null)
    {
        return $this->verifyData($key, $default, 'integer');
    }

    public function verifyEnums($key, $default = null, $enum)
    {
        return $this->verifyData($key, $default, Rule::in($enum));
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @param mixed $rule
     * @throws \App\Exceptions\BusinessException
     */
    public function verifyData($key, $default, $rule)
    {
        $value     = request()->input($key, $default);
        $validator = Validator::make([$key => $value], [$key => $rule]);

        // 当返回值为 null 时，不进行检验
        if (is_null($default) && is_null($value)) {
            return $value;
        }
        if ($validator->fails()) {
            throw new BusinessException(CodeResponse::PARAM_ILLEGAL);
        }
        return $value;
    }
}