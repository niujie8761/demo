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
     * @param string $column
     *
     * @return boolean
     */
    public function addData($data = array(), $column = '') {
        if (empty($data)) {
            return false;
        }
        $data = $this->input($data, $column);
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
        return $this->where($where)->save(array('isdel' => 1));
    }

    /**
     * 修改
     *
     * @param array $data
     * @param string $where
     * @param string $column
     * @return bool
     */
    public function saveData($data = array(), $where = '', $column = '') {
        if(empty($data)) {
            return false;
        }
        $data = $this->inPut($data, $column);
        if(empty($where)) {
            return $this->save($data);
        }else {
            return $this->where($where)->save($data);
        }
    }

    /**
     * 查找数据（1条）
     *
     * @param string $where
     * @param string $column
     * @return array|bool
     */
    public function findData($where = '', $column = '') {
        if($where == '') {
            return false;
        }else {
            $data = $this->where($where)->find();
            return $this->outPut($data, $column, 1);
        }
    }

    /**
     * 查找数据（多条）
     *
     * @param string $where
     * @param string $column
     * @return array|string
     */
    public function selectData($where = '', $column = '') {
        if ($where == '') {
            $data = $this->select();
            return gbk_to_utf8($data);
        } else {
            $data = $this->where($where)->select();
            return $this->outPut($data, $column, 2);
        }
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

    public function pageData($where = "", $firstRow = "", $listRows="", $order="") {
        $data = $this->where($where)->limit($firstRow, $listRows)->order($order)->select();
        return gbk_to_utf8($data);
    }

    /**
     * 输入格式化
     *
     * @param $data
     * @param $keys
     * @return array
     */
    public function inPut($data, $keys = "") {
        $arr = array();
        foreach(utf8_to_gbk($data) as $key => $value) {
            if($key == $keys) {
                $arr[$key] = serialize($value);
            }else {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    /**
     * @param $data
     * @param string $keyParam 需要反序列化的字段
     * @param string $dimension
     * @return array
     */
    public function outPut($data, $keyParam = "", $dimension = "") {
        $arr = array();
        if($dimension == 1) {
            foreach(gbk_to_utf8($data) as $key => $value) {
                if($key == $keyParam) {
                    $arr[$key] = unserialize($value);
                }else {
                    $arr[$key] = $value;
                }
            }
        }else if($dimension == 2){
            foreach(gbk_to_utf8($data) as $key => $value) {
                foreach($value as $ks => $vs) {
                    if($ks == $keyParam) {
                        $arr[$key][$ks] = unserialize($vs);
                    }else {
                        $arr[$key][$ks] = $vs;
                    }
                }
            }
        }
        return $arr;
    }
}