<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

/*
 * 秒杀 redis
 * */
class Redis extends Controller
{
    public function index(){
        echo "redis";

        //生产 - 步骤1
        $this->producer();
        //消费 - 步骤2
        $this->consumer();
    }

    //生产者
    public function producer(){
        //连接redis数据库
        $redis =new \Redis();
        $redis->connect('127.0.0.1',6379);
        $redis_name = 'secKill34';

        //模拟100人请求秒杀(高压力)
        for ($i = 0; $i < 100; $i++) {
            $uid = rand(10000000,99999999);
            //获取当前队列已经拥有的数量,如果人数少于十,则加入这个队列
            $num = 10;
            if ($redis->lLen($redis_name) < $num) {
                $redis->rPush($redis_name, $uid);

                echo $uid . "秒杀成功"."<br>";
            } else {
                //如果当前队列人数已经达到10人,则返回秒杀已完成
                echo "秒杀已结束<br>";
            }
        }
        //关闭redis连接
        $redis->close();
    }
    //消费者
    public function consumer(){

        //设置redis数据库连接及键名
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $key ='secKill34';
        //redis数据库key [注:默认redis数据库选择第0号数据库]
        //PDO连接mysql数据库
//        $dsn ="mysql:dbname=test;host=127.0.0.1";
//        $pdo =new PDO($dsn,'root','123456');

        //死循环
        //从队列最前头取出一个值,判断这个值是否存在,取出时间和uid,保存到数据库
        //数据库插入失败时,要有回滚机制
        //注: rpush 和lpop是一对
        while(1) {
            //从队列最前头取出一个值
            $uid = $redis->lPop($key);
            //判断值是否存在
            if(!$uid || $uid =='nil'){
                sleep(2);
                continue;

            }

            //生成订单号
            $orderNum = $this->build_order_no($uid);
            //生成订单时间
            $timeStamp = time();
            //构造插入数组
            $user_data =array(
                'uid'=>$uid,
                'time_stamp'=>$timeStamp,
                'order_num'=>$orderNum
            );

            //将数据保存到数据库
//            $sql ="insert into student (uid,time_stamp,order_num) values (:uid,:time_stamp,:order_num)";
//            $stmt = $pdo->prepare($sql);
//            $res = $stmt->execute($user_data);
            $res = Db::name('student')->insert($user_data);
            //数据库插入数据失败,回滚
            if(!$res){
                $redis->rPush($key,$uid);
            }
            var_dump('success');
        }

    }
    //生成唯一订单号
    function build_order_no($uid){
        return substr(implode(NULL, array_map('ord', str_split(substr(uniqid(),7,13),1))),0,8).$uid;

    }


}