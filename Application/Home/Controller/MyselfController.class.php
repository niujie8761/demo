<?php
/**
 * Created by PhpStorm.
 * User: niuJie
 * Description: 365���ܼҺ�̨�ع�
 * Date: 2017/12/4
 * Time: 15:48
 */

namespace Home\Controller;


use Home\Model\MangerModel;

class MyselfController  extends BaseController
{
    /**
     * �޸ĺ�̨�˻�����
     */
    public function password()
    {
        if(IS_AJAX) {
            $currentPass = I('post.currentPass');
            $newPass = I('post.newPass');
            $kamPassword = $this->userInfo['kam_password'];
            if($kamPassword != md5($currentPass)) {
                $result = array(
                    'status' => 0
                );
            }else {
                $kamId = $this->userInfo['kam_id'];
                $mangerM = MangerModel::getInstance('manger');
                $condition = array('kam_id' => $kamId);
                $data['kam_password'] = md5($newPass);
                $mangerM->saveData($data, $condition);
                $result = array(
                    'status' => 1
                );
            }
            $this->ajaxReturn($result);
        }else {
            $this->display();
        }
    }
}