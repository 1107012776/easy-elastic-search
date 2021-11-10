<?php

namespace PhpEasyData\Components;
/**
 * Created by PhpStorm.
 * User: 11070
 * Date: 2021/10/24
 * Time: 0:42
 */
class Common
{
    public static function getCid()
    {
        if (!class_exists('\Swoole\Coroutine')) {
            return -1;
        }
        return \Swoole\Coroutine::getCid();
    }

    /**
     * 获取orderBy字段
     * @param string $order_str = 'id asc,create_time desc';
     * @return $order =>
     * [
     *    [
     *        'id','asc',
     *    ],
     *    [
     *       'create_time','desc'
     *    ]
     * ]
     */
    public static function getOrderField($order_str = '')
    {
        $order = $order_str;
        if (strstr($order, ',')) {
            $order = explode(',', $order);
        }
        if (is_array($order)) {  //多个order by
            foreach ($order as &$v) {
                $v = trim($v);
                $v = explode(' ', $v);
                $v = array_filter($v);  //去空值
            }
        } else {
            $order = trim($order);
            $order = explode(' ', $order);
            $order = array_filter($order); //去空值
            $order = [$order];
        }
        return $order;
    }
}