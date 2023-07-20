<?php

namespace app\common\service;

class OrderService
{
    public static function getOrderList(){
        $list = [
            'id' => 1,
            'orderNo'=>'YT234325',
            'title'=>'goodsName'
        ];
        return $list;
    }
}