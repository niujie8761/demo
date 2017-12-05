<?php
/**
 * Created by PhpStorm.
 * User: niuJie
 * Description: 365房管家后台重构
 * Date: 2017/12/4
 * Time: 15:28
 */

namespace Home\Controller;

use Home\Model\BaseModel;
use Home\Model\CityModel;
use Home\Model\DefaultModel;

class SystemmanageController extends BaseController
{
    /**
     * 城市配置列表
     */
    public function index()
    {
        $data = I('get.');
        $condition = array();
        if(isset($data['city_name']) && !empty($data['city_name'])) {
            $data['city_name'] = iconv('utf-8', 'gbk', $data['city_name']);
            $condition['city_name'] = array('like', '%'.$data['city_name'].'%');
        }
        if(isset($data['is_open'])) {
            $condition['is_open'] = $data['is_open'];
        }
        if(isset($data['consign']) && $data['consign'] != 0) {
            if($data['consign'] == 2) {
                $condition['consign'] = 0;
            }else {
                $condition['consign'] = $data['consign'];
            }
        }
        if(isset($data['unagent']) && $data['unagent'] != 0) {
            if($data['unagent'] == 2) {
                $condition['unagent'] = 0;
            }else {
                $condition['unagent'] = $data['unagent'];
            }
        }
        if(isset($data['is_msg'])) {
            $condition['is_msg'] = $data['is_msg'];
        }
        $cityM = CityModel::getInstance('city');
        $pageSize = isset($data['pageSize']) ? $data['pageSize'] : 10;
        $count = $cityM->findCount($condition);
        $page = getPage($count, $pageSize);
        $order = array('id desc');
        $cityList = $cityM->pageData($condition, $page->firstRow, $page->listRows, $order);
        //获取400电话
        $defaultM = DefaultModel::getInstance('default');

        foreach($cityList as $k => &$v) {
            $where = array('city' => $v['city']);
            $sInfo = $defaultM->findData($where);
            $v['serviceNum'] = $sInfo['tel'];
        }
        $data = array(
            'cityList' => $cityList,
            'page' => $page->show()
        );
        $this->assign($data);
        $this->display();
    }

    /**
     * 添加城市
     */
    public function add()
    {
        if(IS_POST) {
            $data = I('post.');
            $cityM = CityModel::getInstance('city');
            $condition = array('city' => $data['city']);
            $cityInfo = $cityM->findData($condition);
            if(!empty($cityInfo)) {
                $result = array(
                    'code' => 201,
                    'msg' => '城市拼音缩写有重复',
                );
                $result = gbk_to_utf8($result);
                $this->ajaxReturn($result);
            }
            $condition = array('city_name' => $data['city_name']);
            $cityInfo = $cityM->findData($condition);
            if(!empty($cityInfo)) {
                $result = array(
                    'code' => 202,
                    'msg' => '城市名称有重复',
                );
                $result = gbk_to_utf8($result);
                $this->ajaxReturn($result);
            }
            $tel = $data['s400'];
            unset($data['s400']);
            $data['addtime'] = time();
            $data['updatetime'] = time();
            $cityM->addData($data);
            $params = array(
                'city' => $data['city'],
                'tel' => $tel,
                'operator_id' => $this->userInfo['kam_id'],
                'operator_name' => $this->userInfo['kam_username'],
                'operator_time' => time()
            );
            $defaultM = BaseModel::getInstance('default');
            $defaultM->addData($params);
            $result = array(
                'code' => 200,
                'msg' => 'ok'
            );
            $this->ajaxReturn($result);
        }else {
            $this->display();
        }
    }

    /**
     * 编辑城市
     */
    public function edit()
    {
        if(IS_AJAX) {
            $data = I('post.');
            $id = $data['id'];
            $tel = $data['s400'];
            $ocity = $data['ocity'];
            $condition = array('city' => $data['city']);
            $cityM = CityModel::getInstance('city');
            $cityInfo = $cityM->findData($condition);
            if(!empty($cityInfo)) {
                $result = array('code' => 201, 'msg' => '城市拼音缩写有重复');
                $result = gbk_to_utf8($result);
                $this->ajaxReturn($result);
            }
            unset($data['id']);
            unset($data['s400']);
            unset($data['ocity']);
            $params = array(
                'city' => $data['city'],
                'tel' => $tel,
                'operator_id' => $this->userInfo['kam_id'],
                'operator_name' => $this->userInfo['kam_username'],
                'operator_time' => time()
            );

            //修改城市配置
            $condition = array('id' => $id);
            $cityM->saveData($data, $condition);
            //修改400电话
            $condition = array('city' => $ocity);
            $defaultM = DefaultModel::getInstance('default');
            $defaultM->saveData($params, $condition);
            $result = array('code' => 200, 'msg' => 'ok');
            $this->ajaxReturn($result);
        }
        $id = I('get.id');
        $cityM = CityModel::getInstance('city');
        $condition = array('id' => $id);
        $city = $cityM->findData($condition);
        $defaultM = BaseModel::getInstance('default');
        $condition = array('city' => $city['city']);
        $s400 = $defaultM->findData($condition);
        $city['400'] = $s400['tel'];
        $data = array('city' => $city);
        $this->assign($data);
        $this->display();
    }
}