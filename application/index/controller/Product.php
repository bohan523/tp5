<?php

namespace app\index\controller;

use app\index\logic\ProductDevLogic;

class Product
{
    public function add(){
        $productData = [];
        $goodsMainData = [];
        $goodsData = [];
        $planData = [];
        $suppperData = [];
        $appendixData = [];
        return 23;
    }


    public function importProduct(){

        //获取解析excel data
        $data = [
            ['中文名称','品牌','价格','重量'],
            ['蓝牙耳机','apple','2000','10'],
            ['小米音响','小米','120','80'],
        ];
return $data;
        //导入商品
        (new ProductDevLogic($data))();
    }

}