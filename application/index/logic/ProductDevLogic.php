<?php

namespace app\index\logic;

class ProductDevLogic
{
    public function __invote(){

        //初始化数据
        $this->load();
        //构建数据
        $this->buildData($data);
        //插入数据
        $this->updateData();


    }
}