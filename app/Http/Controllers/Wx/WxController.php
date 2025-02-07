<?php
namespace App\Http\Controllers\Wx;

use App\CodeResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class WxController extends Controller
{

    protected function successPaginate($page)
    {
        return $this->success($this->paginate($page));
    }
    protected function paginate($page)
    {
        if ($page instanceof LengthAwarePaginator) {
            return [
                'total' => $page->total(),
                'page'  => $page->currentPage(),
                'limit' => $page->perPage(),
                'pages' => $page->lastPage(),
                "list"  => $page->items(),
            ];
        }
        if ($page instanceof Collection) {
            $page = $page->toArray();
        }

        if (! is_array($page)) {
            return $page;
        }

        $total = count($page);
        return [
            'total' => $total,
            'page'  => 1,
            'limit' => $total,
            'pages' => 1,
            'list'  => $page,
        ];
    }

    protected function success($data = null)
    {
        return $this->codeReturn(CodeResponse::SUCCESS, $data);
    }

    protected function codeReturn(array $codeResponse, $data = null, $info = ''): JsonResponse
    {
        list($errno, $errmsg) = $codeResponse;
        $res                  = ['errno' => $errno, 'errmsg' => $info ?: $errmsg];
        if (! is_null($data)) {
            if (is_array($data)) {
                $data = array_filter($data, function ($item) {
                    return $item !== null;
                });
            }
            $res['data'] = $data;
        }
        return response()->json($res);
    }

    protected function fail(array $codeResponse = CodeResponse::FAIL, $info = ''): JsonResponse
    {
        return $this->codeReturn($codeResponse, null, $info);
    }

    protected function failOrSuccess($isSuccess, array $codeResponse = CodeResponse::FAIL, $data = null, $info = ''): JsonResponse
    {
        if ($isSuccess) {
            return $this->success($data);
        }
        return $this->fail($codeResponse, $info);
    }

    public function user()
    {
        return Auth::guard('wx')->user();
    }

    public function isLogin()
    {
        return ! is_null($this->user());
    }

    public function userId()
    {
        return $this->user()->getAuthIdentifier();
    }
}
