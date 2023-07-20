<?php
namespace app\index\controller;

use app\common\DataHelp;
use app\common\service\EnumUser;
use app\common\service\OrderService;
use app\common\UserHelp;
use app\index\model\Product;
use think\Db;

class Index
{
    /**
     * Notes:
     * User: bohan
     * Date: 2022/1/4
     * Time: 20:59
     */
    public function getWenPingName(){
        $name = '徐文平';
        $pagesize = EnumUser::PAGESIZE;
        $myName = EnumUser::getMyName();
//        renderApiOk($myName);
        $list = OrderService::getOrderList();
        renderApiOk($list);
    }
    /**
     * Notes: 获取用户信息
     * User: bohan
     * Date: 2022/1/4
     * Time: 20:24
     */
    public function getUserInfo(){
        $userId = 1;
        $info = UserHelp::getInfo();
        renderApiOk($info);

    }
    /**
     * Notes: index文件
     * User: bohan
     * Date: 2021/12/31
     * Time: 15:46
     */
    public function index()
    {
        $list = Db::table('product')->select();
        $planList = Db::table('product_plan')->where('product_id', 'in', [1, 2])->select();
//        renderApiOk($planList);
        $newList = [];
        foreach ($planList as $item) {
            $newList[$item['product_id']][] = [
                'plan_type' => $item['plan_type'],
                'id_name' => $item['manager_id'] . '-' . $item['manager_name']
            ];
        }
        foreach ($list as &$value){
            if(isset($newList[$value['id']])){
                $value['planList'] = $newList[$value['id']];
            }
        }

        renderApiOk($list);
    }

    /**
     * Notes: test
     * User: bohan
     * Date: 2021/12/31
     * Time: 15:59
     */
    public function test(){
        $arr = ['i','am','test'];
        renderApiOk($arr);
    }

    public function banner(){
        $image = [
            'img1',
            'img2',
            'img3',
        ];
        renderApiOk($image);
    }

    // 冒泡排序
    function bubble_sort()
    {
        $arr = [5,2,4,7,9,4,2,6,8,3];
        $len = count($arr);
        for ($i = 0; $i < $len -1; $i++) {//循环对比的轮数
            for ($j = 0; $j < $len - $i - 1; $j++) {//当前轮相邻元素循环对比
                if ($arr[$j] > $arr[$j + 1]) {//如果前边的大于后边的
                    $tmp = $arr[$j];//交换数据
                    $arr[$j] = $arr[$j + 1];
                    $arr[$j + 1] = $tmp;
                }
            }
        }
        return json($arr);
    }



}
