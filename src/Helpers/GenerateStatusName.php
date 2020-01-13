<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/4/9
 * Time: 18:14
 */

namespace ShangYou\Helpers;


class GenerateStatusName
{
    /**
     * 获取需求单状态名称
     *
     * @param int $status 状态编号
     *
     * @return string
     */
    public static function getDemandMaterial($status)
    {
        $status_name = '';
        switch($status) {
            case 0:
                $status_name = '等待反馈';
                break;
            case 1:
                $status_name = '反馈中';
                break;
            case 2:
                $status_name = '反馈完成';
                break;
        }

        return $status_name;
    }

    /**
     * 获取供应商反馈状态名称
     *
     * @param int $status 状态编号
     *
     * @return string
     */
    public static function getProviderFeedback($status)
    {
        $status_name = '';
        switch($status) {
            case 0:
                $status_name = '等待反馈';
                break;
            case 1:
                $status_name = '已保存';
                break;
            case 2:
                $status_name = '已反馈';
                break;
        }

        return $status_name;
    }

    /**
     * 获取订单状态名称
     *
     * @param int $status 状态编号
     *
     * @return string
     */
    public static function getOrder($status)
    {
        $status_name = '';
        switch($status) {
            case 0:
                $status_name = '等待卖家处理';
                break;
            case 1:
                $status_name = '等待买家确认';
                break;
            case 2:
                $status_name = '卖家处理被打回';
                break;
            case 3:
                $status_name = '等待卖家发货';
                break;
            case 4:
                $status_name = '等待买家签收';
                break;
            case 5:
                $status_name = '等待卖家确认签收';
                break;
            case 6:
                $status_name = '买家签收被打回';
                break;
            case 7:
                $status_name = '订单完成';
                break;
        }

        return $status_name;
    }

    /**
     * 获取帐单状态名称
     *
     * @param int $status 状态编号
     *
     * @return string
     */
    public static function getBill($status)
    {
        $status_name = '';
        switch($status) {
            case 0:
                $status_name = '等待卖家处理';
                break;
            case 1:
                $status_name = '等待买家确认';
                break;
            case 2:
                $status_name = '卖家处理被打回';
                break;
            case 3:
                $status_name = '等待买家付款';
                break;
            case 4:
                $status_name = '买家付款中';
                break;
            case 5:
                $status_name = '账单完成';
                break;
        }

        return $status_name;
    }

    /**
     * 获取发票状态名称
     *
     * @param int $status 状态编号
     *
     * @return string
     */
    public static function getInvoice($status)
    {
        $status_name = '';
        switch($status) {
            case 0:
                $status_name = '未签收';
                break;
            case 1:
                $status_name = '已签收';
                break;
        }

        return $status_name;
    }

    /**
     * 获取付款状态名称
     *
     * @param int $status 状态编号
     *
     * @return string
     */
    public static function getPayment($status)
    {
        $status_name = '';
        switch($status) {
            case 0:
                $status_name = '等待主管审核';
                break;
            case 1:
                $status_name = '审核通过待付款';
                break;
            case 2:
                $status_name = '审核被打回';
                break;
            case 3:
                $status_name = '系统结算中';
                break;
            case 4:
                $status_name = '等待卖家确认收款';
                break;
            case 5:
                $status_name = '付款成功';
                break;
        }

        return $status_name;
    }

    /**
     * 获取承兑汇票状态名称
     *
     * @param int $status 状态编号
     *
     * @return string
     */
    public static function getAcceptance($status)
    {
        $status_name = '';
        switch($status) {
            case 0:
                $status_name = '未使用';
                break;
            case 1:
                $status_name = '已使用';
                break;
        }

        return $status_name;
    }
}