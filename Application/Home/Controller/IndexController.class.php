<?php

namespace Home\Controller;

use Home\Model\AgentModel;
use Home\Model\MangerModel;
use Home\Model\CityModel;
use Home\Model\MenuModel;
use Think\Cache\Driver\Redis;
use Think\Controller;
use think\geetest\GeetestLib;

class IndexController extends Controller
{
    public function login()
    {
        $this->display();
    }

    /**
     * 极速验证码显示
     */
    public function showVerify()
    {
        $geeConfig = C('GEE_CONFIG');
        $gee = new GeetestLib($geeConfig);
        $user_id = "test";
        //预处理接口
        $status = $gee->pre_process($user_id);
        session_start();
        $_SESSION['geetest']=array(
            'gtserver'=>$status,
            'user_id'=>$user_id
        );
        //获取验证字符串接口
        echo $gee->get_response_str();exit;
    }

    /**
     * geetest 验证码验证
     */
    public function ajaxCheck() {
        $data = I('post.');
        $geeConfig = C('GEE_CONFIG');
        $gee = new GeetestLib($geeConfig);
        session_start();
        $user_id = $_SESSION['geetest']['user_id'];
        if ($_SESSION['geetest']['gtserver']==1) {
            //二次验证接口
            $result=$gee->success_validate($data['geetest_challenge'], $data['geetest_validate'], $data['geetest_seccode'], $user_id);
            if ($result) {
                $re = 1;
            } else{
                $re = 0;
            }
        }else{
            //本地二次验证接口
            if ($gee->fail_validate($data['geetest_challenge'],$data['geetest_validate'],$data['geetest_seccode'])) {
                $re = 1;
            }else{
                $re = 0;
            }
        }
        echo $re;
        exit;
    }

    public function checkLogin()
    {
        $data = I('post.');
        $condition = array(
            'kam_username' => $data['username'],
            'kam_password' => md5($data['password']),
        );
        $mangerM = MangerModel::getInstance('manger');
        $findData = $mangerM->findData($condition, 'kam_role');
        if(!empty($findData)) {
            session('userInfo', $findData);
            $result = array('status' => 1, 'msg' => 'ok');
        }else {
            $result = array('status' => 0, 'msg' => '用户名或者密码出错');
        }
        echo $this->ajaxReturn($result);
        exit;
    }

    public function index()
    {
        /**
         * @var $agentM AgentModel
         */
       /* $agentM = AgentModel::getInstance('agent');
        $data['ag_code'] = '123456';
        $data['ks_id'] = '123';
        $agentM->addData($data);
        exit;*/
        $this->display();
    }

    /**
     * 头部
     */
    public function header()
    {
        $cityM = cityModel::getInstance('city');
        $condition = array('is_open' => 1);
        $data = $cityM->selectData($condition);
        $userInfo = $this->userInfo;
        $cityInfo = $userInfo['kam_role']['city'];
        $city = $this->city;
        $keyArr = array();
        foreach($data as $key => &$value) {
            if(!in_array($value['city'], $cityInfo)) {
               unset($data[$key]);
               continue;
            }
            $keyFirst = substr(Ucfirst($value['city']), 0, 1);
            $value['key'] = $keyFirst;
            $value['city_name'] = $keyFirst.'  '.$value['city_name'];
        }
        foreach($data as $kt => $vt) {
            $keyArr[] = $vt['key'];
        }
        array_multisort($keyArr, SORT_ASC, SORT_STRING, $data);
        $result = array('data' => $data, 'city' => $city, 'userInfo' => $userInfo);
        $this->assign($result);
        $this->display();
    }

    /**
     * 菜单栏
     */
    public function menu()
    {
        $redis = new Redis();
        $menus = $redis->get('menus');
        if(empty($menus)) {
            $condition = array('isdel' => 0);
            $order = array('position desc');
            $menuM = MenuModel::getInstance('menu');
            $menus = $menuM->orderData($condition, $order);
        }
        $canMenus = $this->userInfo['kam_role']['menu'];
        $menus = tree_categories($menus,0,1,0,'kamu_parent_id','kamu_id');
        $result = array('menus' => $menus, 'canMenus' => $canMenus);
        $this->assign($result);
        $this->display();
    }

    public function main()
    {
        $this->display();
    }

    /**
     * 切换城市
     */
    public function changeCity()
    {
        $city = I('get.city');
        $canCity = $this->userInfo['kam_role']['city'];
        if(in_array($city, $canCity)) {
            session('city', $city);
        }
        $this->redirect('Home/Index/index');
    }

    //注册（1，判断redis/数据库中有没有该用户，没有的话的已hash形式写入redis，有的话提示）
    public function register()
    {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $redis = new Redis();
        $key = 'user:queue';
        $keyName = 'username:'.$username;
        $res = $this->checkUserExist($keyName);
        if($res['result'] == 1) {
            echo '你已经注册过了';
            exit;
        }
        //先写入redis缓存
        $redis->setLPush($key, $keyName);
        $uid = $redis->setInc($key.'id');
        $userArr = array('username' => $username, 'password' => $password, 'uid' => $uid);
        $redis->setHMSet($keyName, $userArr);

    }
    //登录
    public function doLogin()
    {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $keyName = 'username:'.$username;
        $re = $this->checkUserExist($keyName);
        if(!$re['result']) {
            echo $re['msg'];
            exit;
        }
        if($re['data']['password'] == $password) {
            echo '登录成功';
        }else {
            echo '密码错误';
        }
        exit;
    }

    function checkUserExist($keyName)
    {
        $redis = new Redis();
        $res = $redis->exists($keyName);
        if(!$res) {
            $sql = "select * from user where ";
            $result = exec($sql);
            if(!$result) {
                return array(
                    'result' => 0,
                    'msg' => '该用户不存在',
                    'data' => array()
                );
            }else {
                return array(
                    'result' => 1,
                    'msg' => '用户存在',
                    'data' => $result
                );
            }
        }else {
            $hashKey = array('username', 'password', 'uid');
            $data = $redis->getHMGet($keyName, $hashKey);
            return array(
                'result' => 1,
                'msg' => '用户存在',
                'data' => $data
            );
        }
    }
    //同步redis到数据库
    public function addUserToMysql()
    {
        $redis = new Redis();
        $key = "user:queue";
        while($value = $redis->setRPop($key)) {
            $hashKey = array('username', 'password', 'uid');
            $data = $redis->getHMGet($value, $hashKey);
            $username = $data['username'];
            $password = $data['password'];
            $dsn = "mysql:host=192.168.105.146;dbname=jjrnew;charset=utf8";
            $pdo = new \PDO($dsn, 'root', 'idontcare');

            $sql = "insert into keeper_agent (ag_phone, password) value (:name, :pass)";
            $smp = $pdo->prepare($sql);

            echo $smp->execute([
                'name' => $username,
                'pass' => $password
            ]);
        }
    }


    /**
     * 退出
     */
    public function logout()
    {
        session('userInfo', null);
        session('city', null);
        $this->redirect('Home/Index/login');
    }

    /**
     * geetest生成验证码
     */
    public function geetest_show_verify(){
        $geetest_id=C('GEETEST_ID');
        $geetest_key=C('GEETEST_KEY');
        $geetest=new \Org\Xb\Geetest($geetest_id,$geetest_key);
        $user_id = "test";
        $status = $geetest->pre_process($user_id);
        $_SESSION['geetest']=array(
            'gtserver'=>$status,
            'user_id'=>$user_id
        );
        echo $geetest->get_response_str();
    }

    public function geetest_ajax_check()
    {
        $data = I('post.');
        echo geetest_chcek_verify($data);
    }

    /**
     * 发送邮件
     */
    public function send_email(){
        $email=I('get.email');
        if ($email=='baijunyao@baijunyao.com') {
            die('请修改邮箱地址已接收测试邮件');
        }
        $result=send_email($email,'365集团的正式邮件','好好干');
        if ($result['error']==1) {
            p($result);die;
        }
        //$this->success('发送完成',U('Home/index/index'));
    }

    public function ajax_upload()
    {
        $dir_path =  ajax_upload('/Upload/image');
    }

    public function webuploader() {
        $data = $_POST;
        if(upload_success($data)) {
            echo 123;
        }else {
            echo 456;
        }
    }
}