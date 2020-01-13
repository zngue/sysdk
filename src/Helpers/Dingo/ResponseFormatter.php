<?php

namespace ShangYou\Helpers\Dingo;


use Dingo\Api\Http\Response\Format\Json;

class ResponseFormatter extends Json
{
    /**
     * 响应内容格式化
     *
     * @param string $content
     *
     * @return string
     */
    protected function encode($content)
    {
        if (isset($content['status_code']) && isset($content['message'])) {
            return json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        return json_encode([
            'message'     => 'ok',
            'status_code' => 200,
            'data'        => $content
        ], JSON_UNESCAPED_UNICODE);
    }
}