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
            $menus = $menuM->selectData($where);
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

    public function ajaxMenu() {
        if(IS_AJAX) {
            $kamuId = I('post.kamu_id');
            $where = array('kamu_id' => $kamuId);
            $menuM = MenuModel::getInstance('menu');
            $data = $menuM->findData($where);
            $this->ajaxReturn($data);
        }
    }

    public function adminSave() {
        if(IS_POST) {
            $data = I('post.');
            if($data['kamu_id'] == 0) {
                $data['create_time'] = time();
                $data['update_time'] = time();
                $data['isdel'] = 0;
                unset($data['kamu_id']);
                $menuM = MenuModel::getInstance('menu');
                $insertId = $menuM->addData($data);
                if($insertId) {
                    $where = array('isdel' => 0);
                    $order = array('position desc');
                    $value = $menuM->orderData($where, $order);
                    $redis = new Redis();
                    $redis->set('menus', $value);
                    $this->redirect('menu/admin');
                }
            }else {

            }

        }
    }



}