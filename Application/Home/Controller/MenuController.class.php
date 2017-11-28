<?php
/**
 * Created by PhpStorm.
 * User: 365
 * Date: 2017/11/27
 * Time: 15:46
 */

namespace Home\Controller;



use Home\Model\MenuModel;
use Think\Cache\Driver\Redis;

class MenuController extends BaseController
{
    /**
     * 权限列表
     */
    public function admin() {
        $redis = new Redis();
        $menus = $redis->get('menus');
        if(empty($menus)) {
            $menuM = MenuModel::getInstance('menu');
            $where = array('isdel' => 0);
            $order = array('position desc');
            $menus = $menuM->orderData($where, $order);
        }
        $topMenus = array();
        foreach($menus as $key => $value) {
            if($value['kamu_parent_id'] != 0) {
                continue;
            }
            $topMenus[] = $value;
        }
        $menus = tree_categories($menus, 0, 1, 0, 'kamu_parent_id', 'kamu_id');
        $data = array(
            'menus' => $menus,
            'topMenus' => $topMenus,
        );
        $this->assign($data);
        $this->display();
    }

    /**
     * ajax
     */
    public function ajaxMenu() {
        if(IS_AJAX) {
            $kamuId = I('post.kamu_id');
            $where = array('kamu_id' => $kamuId);
            $menuM = MenuModel::getInstance('menu');
            $data = $menuM->findData($where);
            $this->ajaxReturn($data);
        }
    }

    /**
     *  添加或者编辑菜单权限
     */
    public function adminSave() {
        if(IS_POST) {
            $data = I('post.');
            $menuM = MenuModel::getInstance('menu');
            $where = array('isdel' => 0);
            $order = array('position desc');
            if($data['kamu_id'] == 0) {
                //添加权限菜单
                $data['create_time'] = time();
                $data['update_time'] = time();
                $data['isdel'] = 0;
                unset($data['kamu_id']);
                $menuM->addData($data);
            }else {
                //编辑权限菜单
                $condition = array('kamu_id' => $data['kamu_id']);
                $data['update_time'] = time();
                $menuM->saveData($data, $condition);
            }
            //添加或者编辑权限后刷新到redis缓存中
            $redis = new Redis();
            $value = $menuM->orderData($where, $order);
            $redis->set('menus', $value);
            $this->redirect('menu/admin');
        }
    }

    /**
     * 删除权限
     */
    public function adminDelete()
    {
        $id = I('post.kamu_id');
        $where = array('kamu_id' => $id);
        $menuM = MenuModel::getInstance('menu');
        $result = $menuM->deleteData($where);
        if($result) {
            $condition = array('isdel' => 0);
            $order = array('position desc');
            $menus = $menuM->orderData($condition, $order);
            $redis = new Redis();
            $redis->set('menus', $menus);
            $data = array('status' => 1, 'msg' => '删除成功');
        }else {
            $data = array('status' => 0, 'msg' => '操作失败');
        }
        $this->ajaxReturn($data);
    }


}