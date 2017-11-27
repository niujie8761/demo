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
}