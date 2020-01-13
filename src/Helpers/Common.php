<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/4/9
 * Time: 18:05
 */

namespace ShangYou\Helpers;


class Common
{
    /**
     * 价格格式化
     */
    public static function priceFormat($price)
    {
        return sprintf('%.2f', round($price) / 100);
    }

    /**
     * 价格转换为整形（price*100）
     */
    public static function price2Int($price)
    {
        return round($price * 100);
    }

    /**
     * 去掉数字右侧多余的0
     *
     * @param $number
     *
     * @return string
     */
    public static function trimRZero($number)
    {
        if (is_numeric($number) && strpos($number, '.') !== false) {
            return rtrim(rtrim($number, '0'), '.');
        }

        return $number;
    }

    /**
     * 付款周期名称
     *
     * @param $payment_period
     *
     * @return string
     */
    public static function getPaymentPeriodName($payment_period)
    {
        $payment_period_name = '';
        switch($payment_period) {
            case 1:
                $payment_period_name = '货到付款';
                break;
            case 2:
                $payment_period_name = '先付款';
                break;
            case 3:
                $payment_period_name = '分期付款';
                break;
        }

        return $payment_period_name;
    }

    /**
     * 支付方式名称
     *
     * @param $pay_mode
     *
     * @return string
     */
    public static function getPayModeName($pay_mode)
    {
        $pay_mode_name = '';
        switch ($pay_mode) {
            case 1:
                $pay_mode_name = '工业E+';
                break;
            case 2:
                $pay_mode_name = '银行承兑汇票';
                break;
            case 3:
                $pay_mode_name = '银行转账';
                break;
            case 4:
                $pay_mode_name = '易极付';
                break;
        }

        return $pay_mode_name;
    }

    /**
     * 获取商品单位列表
     *
     * @return array
     */
    public static function getCommodityUnits() {
        return ['件', '片', '吨', '个', '只', '支', '颗', '盒', '根', '千克'];
    }
}