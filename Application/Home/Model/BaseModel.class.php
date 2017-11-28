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
     * 增加
     *
     * @param array $data
     *
     * @return boolean
     */
    public function addData($data = array()) {
        if (empty($data)) {
            return FALSE;
        }
        $data = utf8_to_gbk($data);
        return $this->add($data);
    }

    /**
     * 删除
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
     * 修改
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
     * 查找数据 （1条）
     *
     * @param string $where
     * @return bool|mixed
     */
    public function findData($where = '')
    {
        if($where == '') {
            return false;
        }else {
            $data = $this->where($where)->find();
            return $this->outPut($data, 'kam_role');
        }
    }

    /**
     * 输出格式化
     *
     * @param $data
     * @param $keys
     * @return array
     */
    public function outPut($data, $keys) {
        $arr = array();
        foreach(gbk_to_utf8($data) as $key => $value)
        {
            if($key == $keys) {
                $arr[$key] = unserialize($value);
            }else {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    /**
     * 查找数据数量
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
     * 查找数据
     *
     * @param string $where
     * @return mixed
     */
    public function selectData($where = '') {
        if ($where == '') {
            $data = $this->select();
            return gbk_to_utf8($data);
        } else {
            $data = $this->where($where)->select();
            return gbk_to_utf8($data);
        }
    }

    /**
     *
     * 查找数据按顺序排列
     *
     * @param string $where
     * @param string $order
     * @return array|string
     */
    public function orderData($where = "", $order = "") {
        $data = $this->where($where)->order($order)->select();
        return gbk_to_utf8($data);
    }
}