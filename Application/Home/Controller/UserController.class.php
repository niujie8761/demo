<?php
/**
 * Created by PhpStorm.
 * User: 365
 * Date: 2017/11/28
 * Time: 13:17
 */

namespace Home\Controller;


use Home\Model\MenuModel;
use Home\Model\RoleModel;
use Think\Cache\Driver\Redis;

class UserController extends BaseController
{
    public function role()
    {
        $roleM = RoleModel::getInstance('role');
        $pageSize = isset($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : 10;
        $where['isdel'] = 0;
        $count = $roleM->where($where)->count();
        $page = getPage($count, $pageSize);
        $order = array('create_time desc');
        $role = $roleM->pageData($where, $page->firstRow, $page->listRows, $order);
        $data = [
            'role' => $role,
            'page' => $page->show(),
        ];
        $this->assign($data);
        $this->display();
    }

    public function roleRight()
    {
        $redis = new Redis();
        $menus = $redis->get('menus');
        if(empty($menus)) {
            $condition = array('isdel' => 0);
            $order = array('position desc');
            $menuM = MenuModel::getInstance('menu');
            $menus = $menuM->orderData($condition, $order);
        }
        $menus = tree_categories($menus, 0, 1, 0, 'kamu_parent_id', 'kamu_id');

        echo "<pre>";
        print_r($menus);exit;
        $this->display();
    }
}