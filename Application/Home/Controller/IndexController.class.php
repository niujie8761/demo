<?php
namespace Home\Controller;


use Home\Model\AgentModel;
use Home\Model\MangerModel;
use Home\Model\CityModel;
use Think\Cache\Driver\Redis;
use think\geetest\GeetestLib;

class IndexController extends BaseController
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
        $findData = $mangerM->findData($condition);
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

    public function menu()
    {
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
        $email=I('post.email');
        if ($email=='baijunyao@baijunyao.com') {
            die('请修改邮箱地址已接收测试邮件');
        }
        $result=send_email($email,'365集团的正式邮件','好好干');
        if ($result['error']==1) {
            p($result);die;
        }
        $this->success('发送完成',U('Home/index/index'));
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