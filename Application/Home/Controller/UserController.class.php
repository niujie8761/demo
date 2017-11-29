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

    /**
     * 列表
     */
    public function role()
    {
        if(isset($_GET['type']) && $_GET['type'] >= 0) {
            $type = I('get.type');
            $where['type'] = $type;
        }
        if(isset($_GET['status']) && $_GET['status'] >= 0) {
            $status = I('get.status');
            $where['status'] = $status;
        }
        $roleM = RoleModel::getInstance('role');
        $pageSize = isset($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : 10;
        $where['isdel'] = 0;
        $count = $roleM->where($where)->count();
        $page = getPage($count, $pageSize);
        $order = array('create_time desc');
        $role = $roleM->pageData($where, $page->firstRow, $page->listRows, $order);
        $data = [
            'type' => $type,
            'status' => $status,
            'role' => $role,
            'page' => $page->show(),
        ];
        $this->assign($data);
        $this->display();
    }

    /**
     * 编辑
     */
    public function roleSave()
    {
        if(IS_POST) {
            $data = I('post.');
            $roleM = RoleModel::getInstance('role');
            if($data['id'] > 0) {
                $data['update_time'] = time();
                $where['id'] = $data['id'];
                $roleM->saveData($data, $where);
            }else {
                $data['create_time'] = time();
                $data['update_time'] = time();
                $data['isdel'] = 0;
                unset($data['id']);
                $roleM->addData($data);
            }
            $this->redirect('user/role');
        }
        $id = I('get.id');
        if($id) {
            $roleM = RoleModel::getInstance('role');
            $where['id'] = $id;
            $role = $roleM->findData($where, 'rights');
        }
        $roleType = array('管理后台', '分站管理');
        $data = array(
            'roleType' => $roleType,
            'role' => $role
        );
        $this->assign($data);
        $this->display();
    }

    /**
     * 删除角色
     */
    public function roleDelete()
    {
        if(IS_AJAX) {
            $roleId = I('post.roleId');
            if($roleId) {
                $roleM = RoleModel::getInstance('role');
                $where['id'] = $roleId;
                $roleM->deleteData($where);
                $data = array('status => 1', 'msg' => '删除成功');
                $this->ajaxReturn($data);
            }
        }
    }

    /**
     * 权限
     */
    public function roleRight()
    {
        if(IS_POST) {
           $data = I('post.');
           $roleM = RoleModel::getInstance('role');
           $where['id'] = $data['id'];
           $roleM->saveData($data, $where, 'rights');
           $this->redirect('user/role');
        }
        $redis = new Redis();
        $menus = $redis->get('menus');
        if(empty($menus)) {
            $condition = array('isdel' => 0);
            $order = array('position desc');
            $menuM = MenuModel::getInstance('menu');
            $menus = $menuM->orderData($condition, $order);
        }

        $roleId = I('get.id');
        $roleM = RoleModel::getInstance('role');
        $where['id'] = $roleId;
        $role = $roleM->findData($where, 'rights');
        switch($role['type']) {
            case 0 :
                $menus = tree_categories($menus, 0, 1, 1, 'kamu_parent_id', 'kamu_id');
            case 1 :
                $menus = tree_categories($menus, 0, 1, 1, 'kkmu_parent_id', 'kamu_id');
            case 2 :
                $menus = tree_categories($menus, 0, 1, 1, 'kjmu_parent_id', 'kamu_id');
            default:
                break;
        }
        $data = array(
            'menus' => $menus,
            'role' => $role
        );
        $this->assign($data);
        $this->display();
    }
}