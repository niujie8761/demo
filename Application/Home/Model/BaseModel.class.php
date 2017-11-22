<?php
namespace Home\Model;
/**
 * Created by PhpStorm.
 * User: 365
 * Date: 2017/11/22
 * Time: 10:58
 */

use Think\Model;

class BaseModel extends Model{

    public static $instance = array();

    public static function getInstance($pre) {
        $preObj = 'Home\Model\\'.ucfirst($pre).'Model';
        if(is_null(self::$instance[$pre])) {
            if(class_exists($preObj)) {
                self::$instance[$pre] = new $preObj();
            }else {
                self::$instance[$pre] = new self();
            }
        }
        return self::$instance[$pre];
    }

    /**
     * ����
     *
     * @param array $data
     *
     * @return boolean
     */
    public function addData($data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        return $this->add($data);
    }

    /**
     * ɾ��
     *
     * @param string $where
     *
     * @return boolean
     */
    public function deleteData($where = '') {
        if (empty($where)) {
            return FALSE;
        }
        return $this->where($where)->delete();
    }

    /**
     * �޸�
     *
     * @param array $data
     * @param string $where
     * @return bool
     */
    public function saveData($data = array(), $where = '') {
        if(empty($data)) {
            return false;
        }
        if(empty($where)) {
            return $this->save($data);
        }else {
            return $this->where($where)->save($data);
        }
    }

    /**
     * �������� ��1����
     *
     * @param string $where
     * @return bool|mixed
     */
    public function findData($where = '')
    {
        if($where == '') {
            return false;
        }else {
            return $this->where($where)->find();
        }
    }

    /**
     * ������������
     *
     * @param string $where
     * @return mixed
     */
    public function findCount($where = '') {
        if(empty($where)) {
            return $this->count();
        }else {
            return $this->where($where)->count();
        }
    }

    /**
     * ��������
     *
     * @param string $where
     * @return mixed
     */
    public function selectData($where = '') {
        if ($where == '') {
            return $this->select();
        } else {
            return $this->where($where)->select();
        }
    }
}