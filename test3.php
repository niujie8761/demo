<?php
/**
 * test3.php
 *
 * Copyright (c) 2018 南京码动通信科技有限公司 All rights reserved.{@see http://www.digirun.cn}
 *
 * @copyright Copyright (c) 2018 Digirun.cn All rights reserved.
 * @author Niu Jie<1033751979@qq.com>
 * @since 2018/1/8 20:26 created
 */
error_reporting( E_ALL&~E_NOTICE );

/**
 * @param $total 红包总额
 * @param $count 红包个数
 * @param $max 每个小红包的最大额
 * @param $min 每个小红包的最小额
 * @return array
 */
function generate($total, $count, $max, $min) {

    $result = array();

    $average = $total/$count;
    $a = $average - $min;
    $b = $max - $min;

    $range1 = pow(($average - $min), 2);
    $range2 = pow(($max - $average), 2);

    for($i = 0; $i < $count; $i ++) {
        if(mt_rand($min, $max) > $average) {
            $temp = $min + xRandom($min, $average);
            $result[$i] = $temp;
            $total -= $temp;
        }else {
            $temp = $max - xRandom($average, $max);
            $result[$i] = $temp;
            $total -= $temp;
        }
    }

    while($total > 0) {
        for($i = 0; $i < count($result); $i++) {
            if($total > 0 && $result[$i] < $max) {
                $result[$i]++;
                $total--;
            }
        }
    }

    while($total < 0) {
        for($i = 0; $i < count($result); $i++) {
            if($total < 0 && $result[$i] > $min) {
                $result[$i]--;
                $total++;
            }
        }
    }
    return $result;
}

/**
 * 产生min 与 max之间的随机数，但概率不是平均，从Min到max方向概率渐渐变大
 * 先平方，然后产生一个平方值范围数内的随机数，再开方，产生膨胀收缩的效果
 *
 * @param $min
 * @param $max
 * @return float
 */
function xRandom($min, $max) {
    return  ceil(sqrt(mt_rand(0,(pow(($max - $min), 2)))));
}

$max = 200;
$min = 1;
$result = generate(1000000, 10000, $max, $min);

$total = 0;
for ($i = 0; $i < count($result); $i++) {

    $total += $result[$i];
}
//检查生成的红包的总额是否正确
//print_r("total:" + $total);

//统计每个钱数的红包数量，检查是否接近正态分布
$count = array();
for($i = 0 ; $i < count($result); $i++) {

    $count[$result[$i]] += 1;
}

for($i = 0 ; $i < count($result); $i++) {

    print_r($i.":" .$count[$i]);
    echo '<hr/>';
}

interface useStrategy{
    function showFab();
    function showPlay();
}

class man implements useStrategy {
    public function showFab()
    {
        echo "打游戏";
        // TODO: Implement showFab() method.
    }
    public function showPlay()
    {
        echo "玩";
        // TODO: Implement showPlay() method.
    }
}
class woman implements useStrategy {
    public function showFab()
    {
        echo "购物";
        // TODO: Implement showFab() method.
    }
    public function showPlay()
    {
        echo "逛街";
        // TODO: Implement showPlay() method.
    }
}


class action{
    private $strategy;
    public function doAction() {
        $this->strategy->showFab();
        $this->strategy->showPlay();
    }
    public function doIndex($obj) {
        $this->strategy = $obj;
    }
}

class act{
    public function doAct() {
        $act = $_REQUEST['act'];
        $action = new action();
        if($act == "man") {
            $obj = new man();
            $action->doIndex($obj);
            $action->doAction();
        }else if($act == "woman") {
            $obj = new woman();
            $action->doIndex($obj);
            $action->doAction();
        }
    }
}