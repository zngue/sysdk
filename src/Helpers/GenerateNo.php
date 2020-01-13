<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/4/9
 * Time: 18:05
 */

namespace ShangYou\Helpers;


class GenerateNo
{
    const DEMAND_TYPE  = 1;
    const ORDER_TYPE   = 2;
    const BILL_TYPE    = 3;
    const PAYMENT_TYPE = 4;

    /**
     * 版本号1开始时间
     */
    const DATE_VER_1   = '2016-04-01 00:00:01';

    /**
     * 商品ID和平台ID编码
     *
     * 该功能仅用于屏蔽商品ID,避免用户随意改变商品ID而导致一个平台可以展示所有
     * 商品信息,也可用于验证当前平台是否可以访问该商品(非强制性)
     *
     * @param int $commodityId
     * @param int $platformId
     *
     * @return string
     */
    public static function encodeCommodityNo($commodityId, $platformId)
    {
        return base64_encode($commodityId . 'x' . $platformId);
    }

    /**
     * 商品号码解码
     *
     * @param string $commodityNo 商品号
     *
     * @return array [商品ID,平台ID]
     */
    public static function decodeCommodityNo($commodityNo)
    {
        $commodityNo = base64_decode($commodityNo);
        return explode('x', $commodityNo);
    }

    private static function _getVersion($date)
    {
        $ver = 1;
        if (strtotime($date) > strtotime(self::DATE_VER_1)) {
            $ver = 1;
        }

        return $ver;
    }

    /**
     * 获取需求单编号
     *
     * @param string $date
     * @param int $enterpriseId
     * @param int $index
     *
     * @return string
     */
    public static function getDemandNo($date, $enterpriseId, $index)
    {
        return date('Ymd', strtotime($date)) .
        '-' . ($enterpriseId) .
        '-' . self::DEMAND_TYPE . (self::_getVersion($date)) .
        '-' . ($index);
    }

    /**
     * 获取订单编号
     *
     * @param string $date
     * @param int $enterpriseId
     * @param int $index
     *
     * @return string
     */
    public static function getOrderNo($date, $enterpriseId, $index)
    {
        return date('Ymd', strtotime($date)) .
        '-' . ($enterpriseId) .
        '-' . self::ORDER_TYPE . (self::_getVersion($date)) .
        '-' . ($index);
    }

    /**
     * 获取帐单编号
     *
     * @param string $date
     * @param int $enterpriseId
     * @param int $index
     *
     * @return string
     */
    public static function getBillNo($date, $enterpriseId, $index)
    {
        return date('Ym', strtotime($date)) .
        '-' . ($enterpriseId) .
        '-' . self::BILL_TYPE . (self::_getVersion($date)) .
        '-' . ($index);
    }

    /**
     * 获取支付编号
     *
     * @param string $date
     * @param int $enterpriseId
     * @param int $providerId
     * @param int $index
     *
     * @return string
     */
    public static function getPayNo($date, $enterpriseId, $providerId, $index)
    {
        return date('Ymd', strtotime($date)) .
        '-' . ($enterpriseId) .
        '-' . ($providerId) .
        '-' . self::PAYMENT_TYPE . (self::_getVersion($date)) .
        '-' . ($index);
    }

    /**
     * 获取需求单ID
     *
     * @param string $demandNo
     *
     * @return string
     */
    public static function getDemandId($demandNo)
    {
        $arr = explode('-', $demandNo);

        return isset($arr[3]) ? $arr[3] : false;
    }

    /**
     * 获取订单ID
     *
     * @param string $orderNo
     *
     * @return string
     */
    public static function getOrderId($orderNo)
    {
        $arr = explode('-', $orderNo);

        return isset($arr[3]) ? $arr[3] : false;
    }

    /**
     * 获取账单ID
     *
     * @param string $billNo
     *
     * @return string
     */
    public static function getBillId($billNo)
    {
        $arr = explode('-', $billNo);

        return isset($arr[3]) ? $arr[3] : false;
    }

    /**
     * 获取支付ID
     *
     * @param string $payNo
     *
     * @return string
     */
    public static function getPayId($payNo)
    {
        $arr = explode('-', $payNo);

        return isset($arr[4]) ? $arr[4] : false;
    }

    /**
     * 生成磁卡号
     * @param $identity
     */
    public static function genCardNo($identity){
        //卡号生成规则
        return date("ydmHsi").$identity.rand(10000,99999);
    }
}