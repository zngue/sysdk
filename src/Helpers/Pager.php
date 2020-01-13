<?php
namespace ShangYou\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * 分页助手
 *
 * @package ShangYou\Helpers
 */
class Pager
{
    /**
     * 创建分页器
     *
     * @param       $result
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public static function createPager($result, $options = [])
    {
//        if (isset($options['path'])) {
//            // 如果设置了path，则需要根据当前环境是否是聚合平台进行https处理
//            if (!starts_with($options['path'], ['https://', 'http://'])) {
//                $request = app('request');
//                if ($request->getHost() == config('ShangYou.https_host')) {
//                    $options['path'] = 'https://' . $request->getHost() . '/'
//                                       . ltrim($options['path'], '/');
//                }
//            }
//        }

        return new LengthAwarePaginator(
            $result['data'],
            $result['total'],
            $result['per_page'],
            $result['current_page'],
            $options
        );
    }
}
