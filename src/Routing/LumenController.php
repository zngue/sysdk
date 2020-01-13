<?php

namespace ShangYou\Routing;


class LumenController extends \Laravel\Lumen\Routing\Controller
{
    use ValidationTrait;

    /**
     * 成功
     */
    const RESPONSE_OK = 200;

    /**
     * 成功接受请求,但是不进行处理
     */
    const RESPONSE_ACCEPT = 202;

    /**
     * 重复请求
     */
    const RESPONSE_REPEATED = 208;

    /**
     * API响应结果
     *
     * 用于内部服务接口返回响应消息
     *
     * @param        $statusCode
     * @param string $message
     * @param array  $errors
     * @param array  $data
     *
     * @return array
     */
    public function apiResponse($statusCode, $message = "操作成功", array $errors = [], array $data = [])
    {
        $resp = [
            'status_code' => intval($statusCode),
            'message'     => $message,
            'errors'      => $errors
        ];

        if (!empty($data)) {
            $resp['data'] = $data;
        }

        return $resp;
    }
}