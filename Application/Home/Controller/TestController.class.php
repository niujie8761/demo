<?php
/**
 * TestController.class.php
 *
 * Copyright (c) 2018 南京码动通信科技有限公司 All rights reserved.{@see http://www.digirun.cn}
 *
 * @copyright Copyright (c) 2018 Digirun.cn All rights reserved.
 * @author Niu Jie<1033751979@qq.com>
 * @since 2018/1/13 12:34 created
 */

namespace Home\Controller;

use Home\Model\AcceptPackageModel;
use Home\Model\AgentModel;
use Home\Model\SendPackageModel;
use Think\Cache\Driver\Redis;

class TestController
{
    //发红包
    public function sendPacket() {
        $sendId = 1;//当前发红包的uid
        $groupId = 1;//当前群号
        $totalMoney = 100;//红包
        $num = 8;//红包数量
        $max = 30;//最大红包
        $min = 1;//最小红包
        $flag = 1;//标记
        $tmp = array();
        $tmp['send_id'] = $sendId;
        $tmp['group_id'] = $groupId;
        $tmp['money'] = $totalMoney;
        $tmp['num'] = $num;
        $tmp['create_time'] = time();
        $tmp['flag'] = $flag;
        $sendPackageM = SendPackageModel::getInstance('sendPackage');
        $model = M();
        $model->startTrans();
        //发送的红包插入数据库
        $id = $sendPackageM->addData($tmp);
        $agentM = AgentModel::getInstance('agent');
        $sql = "update keeper_agent set money = money - '".$totalMoney."' where ag_id = '1'";
        $res = $agentM->execute($sql);
        if($res && $id) {
            $model->commit();
        }else {
            $model->rollback();
        }
        //初始化装载小红包到队列
        $redis = new Redis();
        $result = $this->getResult($totalMoney, $num, $max, $min);
        foreach($result as $key => $value) {
            $redis->setLpush('packet', $id.'-'.$value);
            $redis->setExpire('packet', 300);
        }
        if($id) {
            $data = array(
                'result' => 1,
                'id' => $id,
                'msg' => "红包生成成功！！！"
            );
        }else {
            $data = array(
                'result' => 0,
                'msg' => '操作失败！！！'
            );
        }
        echo json_encode($data);
    }

    //抢红包
    public function assignPacket() {
        $redis = new Redis();
        //模拟用户请求过来了
        $uid = '266';
        $packet = $redis->setRPop('packet');
        $ttlTime = $redis->setTtl('packet');
        if($ttlTime == -2) {
            echo '红包已经过期！！！';
            exit;
        }else if(!empty($packet)) {
            $fenPeiResult = $redis->setSMembers('moneyFenPei');
            if(!empty($fenPeiResult)) {
                $uidArr = array();
                foreach ($fenPeiResult as $ks => $vs) {
                    $arr = explode("-", $vs);
                    $uidArr[] = $arr[2];
                }
                if(in_array($uid, $uidArr)) {
                    echo '你已经抢到过红包了！！！';
                    exit;
                }
            }
            $redis->setSAdd('moneyFenPei', $packet . '-' . $uid);
            $packetArr = explode("-", $packet);
            //修改数据库红包为已经分配过
            $model = M();
            $model->startTrans();
            $tag = $tags = false;
            $tmp['order_id'] = $packetArr[0];
            $tmp['accept_id'] = $uid;
            $tmp['accept_time'] = time();
            $tmp['flag'] = 1;
            $acceptPackageM = AcceptPackageModel::getInstance('acceptPackage');
            $res = $acceptPackageM->addData($tmp);
            if($res) {
                $tag = true;
            }
            //更新个人用户的账户余额
            $agentM = AgentModel::getInstance('agent');
            $sql = "update keeper_agent set money = 'money' + '".$packetArr[1]."' where ag_id = ".$uid;
            $res = $agentM->execute($sql);
            if($res) {
                $tags = true;
            }
            if($tag == true && $tags == true) {
                $model->commit();
                echo "恭喜用户".$uid."抢到红包".$packetArr[1].'元！！！';
            }else {
                $model->rollback();
            }

        }else {
            echo '钱包已经被抢完了！！！';
            exit;
        }
    }

    /**
     * @param $total 红包总额
     * @param $count 红包个数
     * @param $max 每个小红包的最大额
     * @param $min 每个小红包的最小额
     * @return array
     */
    public function generate($total, $count, $max, $min) {
        $result = array();
        $average = $total/$count;
        for($i = 0; $i < $count; $i ++) {
            if(mt_rand($min, $max) > $average) {
                $temp = $min + $this->xRandom($min, $average);
                $result[$i] = $temp;
                $total -= $temp;
            }else {
                $temp = $max - $this->xRandom($average, $max);
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
    public function xRandom($min, $max) {
        return  ceil(sqrt(mt_rand(0,(pow(($max - $min), 2)))));
    }

    public function getResult($total, $num, $max,  $min) {
        $result = $this->generate($total, $num, $max, $min);
        return $result;
    }
}