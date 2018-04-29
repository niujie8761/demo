<?php
/**
 * test2.php
 *
 * Copyright (c) 2017 南京码动通信科技有限公司 All rights reserved.{@see http://www.digirun.cn}
 *
 * @copyright Copyright (c) 2017 Digirun.cn All rights reserved.
 * @author Niu Jie<1033751979@qq.com>
 * @since 2017/12/7 21:20 created
 */
echo phpinfo();
exit;
$conn_args = array(
    'host' => '127.0.0.1',
    'port' => '5672',
    'login' => 'guest',
    'password' => 'guest',
    'vhost'=>'/'
);

$e_name = 'e_linvo'; //交换机名
$q_name = 'q_linvo'; //队列名
$k_route = 'key_2'; //路由key

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
echo "Exchange Status:".$ex->declare()."\n";

//创建队列
$q = new \AMQPQueue($channel);
$q->setName($q_name);
$q->setFlags(AMQP_DURABLE); //持久化
echo "Message Total:".$q->declare()."\n";

//绑定交换机与队列，并指定路由键
echo 'Queue Bind: '.$q->bind($e_name, $k_route);

//阻塞模式接收消息
echo "Message:\n";
$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答

$conn->disconnect();

function processMessage($envelope, $queue) {
    //var_dump($envelope->getRoutingKey);
    $msg = $envelope->getBody();
    echo $msg."\n"; //处理消息
    $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
}