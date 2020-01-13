<?php

namespace ShangYou\Routing;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use ShangYou\Exceptions\WrapedValidationException;

class ViewController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ValidationTrait;

    /**
     * 抛出一个验证异常
     *
     * @param WrapedValidationException $e
     * @param Request                   $request
     *
     * @throws ValidationException
     */
    protected function throwWrapedValidationException(WrapedValidationException $e, Request $request)
    {
        throw new ValidationException(null, $this->buildFailedValidationResponse($request, $e->getErrors()));
    }

    protected function ajaxReturn($message, $code, $errors = [], $data = [])
    {
        $resp = [
            'data'        => $data,
            'message'     => $message,
            'status_code' => $code
        ];

        if (!empty($errors)) {
            $resp['errors'] = $errors;
        }

        return response()->json($resp);
    }

    protected function ajaxSuccess($data = [], $message = '操作成功', $code = 200)
    {
        return $this->ajaxReturn($message, $code, [], $data);
    }

    protected function ajaxError($message = 'Failed', $code = 500, $errors = [], $data = [])
    {
        return $this->ajaxReturn($message, $code, $errors, $data);
    }
}