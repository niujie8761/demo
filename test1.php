<?php
/**
 * test1.php
 *
 * Copyright (c) 2017 南京码动通信科技有限公司 All rights reserved.{@see http://www.digirun.cn}
 *
 * @copyright Copyright (c) 2017 Digirun.cn All rights reserved.
 * @author Niu Jie<1033751979@qq.com>
 * @since 2017/12/7 21:19 created
 */

$str = "https%3A%2F%2Fchong.qq.com%2Fphp%2Findex.php%3Fd%3D%26c%3DwxAdapter%26m%3DmobileDeal%26showwxpaytitle%3D1%26vb2ctag%3D4_2030_5_1194_60";
$strs = urldecode($str);
echo $strs;
exit;
/**
 * 冒泡排序
 *
 * @param array
 * @return string
 */
function maoPaoSort($arr) {
    for($i = 1; $i < count($arr); $i++) {
        for($j = 0; $j < count($arr) - $i; $j++) {
            if($arr[$j] > $arr[$j+1]) {
                $tmp = $arr[$j+1];
                $arr[$j+1] = $arr[$j];
                $arr[$j] = $tmp;
            }
        }
    }
    $reStr = implode(",", $arr);
    return $reStr;
}

function quickSort($arr) {
    if(!is_array($arr)) return false;
    if(count($arr) <= 1) {
        return $arr;
    }
    $point = $arr[0];
    $leftArr = array();
    $rightArr = array();
    for($i = 1; $i < count($arr); $i++) {
        if($arr[$i] < $point) {
            $leftArr[] = $arr[$i];
        }else {
            $rightArr[] = $arr[$i];
        }
    }
    var_dump($leftArr);
    echo '<hr/>';
    $c = quickSort($leftArr);
    echo '<pre>';
    print_r($c);
    //echo '<hr/>';

    // $d = quickSort($rightArr);
    //return array_merge($c, array($arr[0]), $d);
}

$str = "12,34,28,1,34,98,23,13,3,123,13,54";
$arr = explode(",", $str);
//echo maoPaoSort($arr);
quickSort($arr);
/*$conn_args = array(
    'host' => '127.0.0.1',
    'port' => '5672',
    'login' => 'guest',
    'password' => 'guest',
    'vhost'=>'/'
);

//创建连接和channel
$conn = new \AMQPConnection($conn_args);
if (!$conn->connect()) {
    die("Cannot connect to the broker!\n");
}
$channel = new \AMQPChannel($conn);

//创建交换机
$e_name = 'e_linvo'; //交换机名
$ex = new \AMQPExchange($channel);
$ex->setName($e_name);
$ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
$ex->setFlags(AMQP_DURABLE); //持久化
echo "Exchange Status:".$ex->declare()."\n";


echo "Send Message:".$ex->publish("TEST MESSAGE,key_1 by xust" . date('H:i:s', time()), 'key_1')."\n";
echo "Send Message:".$ex->publish("TEST MESSAGE,key_2 by xust" . date('H:i:s', time()), 'key_2')."\n";*/