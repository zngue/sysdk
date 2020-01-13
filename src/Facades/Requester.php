<?php
namespace ShangYou\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Requester
 *
 * @see ShangYou\Http\Requester
 *
 * @package ShangYou\Facades
 */
class Requester extends Facade
{

    /**
     * 用户中心服务
     */
    const SERVICE_UCENTER = 'ucenter';

    /**
     * 订单服务
     */
    const SERVICE_ORDER = 'scm:order';

    /**
     * 购物车服务
     */
    const SERVICE_CART = 'scm:cart';

    protected static function getFacadeAccessor()
    {
        return 'ShangYou.api.requester';
    }

}