<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use common\components\UtilHelper;
use yii\web\Response;

function dd($data){
    var_dump(json_encode($data));
}

function returnJson($data){
    echo json_encode($data);exit;
}

/**
 * Notes:
 * User: bohan
 * Date: 2021/12/31
 * Time: 15:46
 * @param null $data
 * @param string $msg
 */
function renderApiOk($data = null, $msg = 'Success')
{
    $renderJsonData = [
        'state' => 0,
        'serverTime' => time(),
        'msg' => $msg,
        'data' => $data,
    ];
    die(json_encode($renderJsonData)) ;
}