<?php
/**
 * CompanyController.class.php
 *
 * Copyright (c) 2017 南京码动通信科技有限公司 All rights reserved.{@see http://www.digirun.cn}
 *
 * @copyright Copyright (c) 2017 Digirun.cn All rights reserved.
 * @author Niu Jie<1033751979@qq.com>
 * @since 2017/12/6 22:00 created
 */


namespace Home\Controller;


class CompanyController
{
    public function index()
    {
        $conn_config = C('AMQP');
        set_time_limit(0);
        //创建连接和channel
        $conn = new \AMQPConnection($conn_config);
        if (!$conn->connect()) {
            die("Cannot connect to the broker!\n");
        }
        $channel = new \AMQPChannel($conn);
        //创建交换机
        $ex = new \AMQPExchange($channel);
        $ex->setName('first');
        $ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
        //创建队列
        $q = new \AMQPQueue($channel);
        $q->setName('firstqueue');
        $q->bind('first', 'queue_route');
        while(true) {
            $q->consume(function(\AMQPEnvelope $envelope, \AMQPQueue $queue){
                $msg = $envelope->getBody();
                file_put_contents('12.log', var_export(array('msg' => $msg),true), FILE_APPEND);
                $queue->ack($envelope->getDeliveryTag());
            });
        }
    }
}