<?php

namespace ShangYou\Helpers;


/**
 * 短信
 *
 * @package ShangYou\Helpers
 */
class Sms
{
    const GATEWAY = 'http://sms.orion-tech.cn/';
    /**
     * Send Message
     *
     * @param string|array $receivers
     * @param string       $message
     *
     * @return mixed
     */
    static function send($receivers, $message)
    {
        if (!is_array($receivers)) {
            $receivers = [$receivers];
        }

        $result = [];
        foreach ($receivers as $receiver) {
            $respRaw = \Unirest\Request::get(self::GATEWAY, [], [
                'body'     => $message,
                'telphone' => $receiver
            ]);

            if ($respRaw->code != 200) {
                // 发送失败
                \Log::info('REQUEST_FAILED',[
                    'receiver' => $receiver,
                    'message'  => $message,
                    'code'     => $respRaw->code
                ]);
                continue;
            }

            \Log::info('RESPONSE_RAW',[
                'receiver' => $receiver,
                'message'  => $message,
                'xml'      => $respRaw
            ]);

            $response = new \SimpleXMLElement(trim($respRaw->body));
            $error    = (string)$response->error;
            $errMsg   = (string)$response->message;

            \Log::info(($error == 0) ? 'SEND_SUCCESS' : 'SEND_FAILED',[
                'error'    => $error,
                'errMsg'   => $errMsg,
                'receiver' => $receiver,
                'message'  => $message
            ]);

            $result[$receiver] = ($error == 0) ? true : false;
        }

        return $result;
    }
}