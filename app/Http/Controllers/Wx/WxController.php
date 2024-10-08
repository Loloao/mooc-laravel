<?php

namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class WxController extends Controller
{


    protected function success($data = null)
    {
        return $this->codeReturn(CodeResponse::SUCCESS, $data);
    }

    protected function codeReturn(array $codeResponse, $data = null, $info = ''): JsonResponse
    {
        list($errno, $errmsg) = $codeResponse;
        $res = ['errno' => $errno, 'errmsg' => $info ?: $errmsg];
        if (!is_null($data)) {
            $res['data'] = $data;
        }
        return response()->json($res);
    }

    protected function fail(array $codeResponse = CodeResponse::FAIL, $info = ''): JsonResponse
    {
        return $this->codeReturn($codeResponse, null, $info);
    }
}
