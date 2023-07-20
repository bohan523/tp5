<?php


namespace app\index\controller;


class Api
{
    public function test(){
        $ret = [
            'code'=>0,
            'message'=>'success',
            'data'=>(object)[],
        ];

        $data = 'here is api test,is for doing some work!';

        return json_encode($ret);
    }
}