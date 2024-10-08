<?php

namespace App\Exceptions;

use App\CodeResponse;
use Exception;
use Illuminate\Http\Request;


class BusinessException extends Exception
{
    public function __construct(array $codeResponse = CodeResponse::FAIL, $info = '')
    {
        list($message, $code) = $codeResponse;
        parent::__construct($info ?: $message, $code);
    }

    public function report(): bool
    {
        return false;
    }

    public function render(Request $request)
    {
        return response()->json(['errno' => $this->getCode(), 'errmsg' => $this->getMessage()]);
    }
}
