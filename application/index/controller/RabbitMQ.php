<?php


namespace app\index\controller;


class RabbitMQ
{

    public function index(){

        $message = "hahahhahhhhahah";
        $ret =  $this->queueEvent($message);
        var_dump($ret);exit;
    }


    //创建生产者
    private function queueEvent($message)
    {

//        error_log("\n******" . date("His") . "********\n" . print_r($message, 1) . "\n*************\n", 3,'messag_event.log');dump($message);
        //设置你的连接
        $conn_args = array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest','vhost'=>'/');
        $content = $message;

        //创建连接和channel
        $conn = new \AMQPConnection($conn_args);

        if (!$conn->connect()) {
            die("Cannot connect to the broker!\n");
        }
        $channel = new \AMQPChannel($conn);

        //创建交换机
        $e_name = 'MQTT_device_event'; //交换机名
        $ex = new \AMQPExchange($channel);
//        return ($ex);
        $ex->setName($e_name);
//        $ex->setType(AMQP_EX_TYPE_TOPIC); //direct类型
        $ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
        $ex->setFlags(AMQP_DURABLE); //持久化
        $ex->declareExchange();
        return 2;
    }


    //  消费者
    public function summer()
    {

        //连接RabbitMQ
        $conn_args = array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest', 'vhost' => '/');

        $e_name = 'MQTT_device_event'; //交换机名
        $q_name = 'q_event'; //队列名
        $k_route = 'key_event'; //路由key

        //创建连接和channel
        $conn = new \AMQPConnection($conn_args);
        if (!$conn->connect()) {
            die("Cannot connect to the broker!\n");
        }
        $channel = new \AMQPChannel($conn);

        //创建交换机
        $ex = new \AMQPExchange($channel);
        $ex->setName($e_name);
        $ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
        $ex->setFlags(AMQP_DURABLE); //持久化
        $ex->declareExchange();

        //创建队列
        $q = new \AMQPQueue($channel);
        $q->setName($q_name);
        $q->setFlags(AMQP_DURABLE); //持久化
        $q->declareQueue();     //最好队列object在这里declare()下，否则如果是新的queue会报错
        //绑定交换机与队列，并指定路由键，可以多个路由键
        $q->bind($e_name, $k_route);
        //$q->bind($e_name, 'key_33');
        //阻塞模式接收消息
        echo "Message:\n";
        while(True){
            $q->consume(function($envelope, $queue) {
                $msg = $envelope->getBody();
                //处理数据
                echo $msg . PHP_EOL; //处理消息
                $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
            });

            //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答

        }

        $conn->disconnect();

        var_dump(111);
    }
}