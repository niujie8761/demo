<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Cache\Driver;

use Think\Cache;

/**
 * Redis缓存驱动
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 */
class Redis extends Cache
{
    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options = array())
    {
        if (!extension_loaded('redis')) {
            E(L('_NOT_SUPPORT_') . ':redis');
        }
        $options = array_merge(array(
            'host'       => C('REDIS_HOST') ?: '127.0.0.1',
            'port'       => C('REDIS_PORT') ?: 6379,
            'timeout'    => C('DATA_CACHE_TIMEOUT') ?: false,
            'persistent' => false,
        ), $options);

        $this->options           = $options;
        $this->options['expire'] = isset($options['expire']) ? $options['expire'] : C('DATA_CACHE_TIME');
        $this->options['prefix'] = isset($options['prefix']) ? $options['prefix'] : C('DATA_CACHE_PREFIX');
        $this->options['length'] = isset($options['length']) ? $options['length'] : 0;
        $func                    = $options['persistent'] ? 'pconnect' : 'connect';
        $this->handler           = new \Redis; false === $options['timeout'] ?
        $this->handler->$func($options['host'], $options['port']) :
        $this->handler->$func($options['host'], $options['port'], $options['timeout']);
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name)
    {
        N('cache_read', 1);
        $value    = $this->handler->get($this->options['prefix'] . $name);
        $jsonData = json_decode($value, true);
        return (null === $jsonData) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null)
    {
        N('cache_write', 1);
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        $name = $this->formatKey($name);
        //对数组/对象数据进行缓存处理，保证数据完整性
        $value = $this->formatValue($value);
        if (is_int($expire) && $expire) {
            $result = $this->handler->setex($name, $expire, $value);
        } else {
            $result = $this->handler->set($name, $value);
        }
        if ($result && $this->options['length'] > 0) {
            // 记录缓存队列
            $this->queue($name);
        }
        return $result;
    }

    /**
     * 获取队列中的元素
     *
     * @param $name
     * @param $start
     * @param $end
     * @param null $dbName
     * @return array
     */
    public function setLRange($name, $start, $end, $dbName = null) {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->lRange($this->formatKey($name), $start, $end);
    }

    /**
     * 从左边进入队列并根据名称自动切库
     *
     * @param $name
     * @param $value
     * @param null $dbName
     * @return int
     */
    public function setLPush($name, $value, $dbName = null)
    {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->lPush($this->formatKey($name), $this->formatValue($value));
    }

    /**
     * 从右边弹出队列数据
     *
     * @param $name
     * @param null $dbName
     * @return string
     */
    public function setRPop($name, $dbName = null)
    {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->rpop($this->formatKey($name));
    }

    /**
     * 从左边弹出队列数据
     *
     * @param $name
     * @param null $dbName
     * @return string
     */
    public function setRPop($name,  $dbName = null)
    {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->rPop($this->formatKey($name));
    }

    /**
     * 哈希赋值
     *
     * @param $name
     * @param $value
     * @param null $dbName
     * @return bool
     */
    public function setHMSet($name, $value, $dbName = null)
    {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->hMset($this->formatKey($name), $value);
    }

    public function getHMGet($name, $hashKey, $dbName = null)
    {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->hMGet($this->formatKey($name), $hashKey);
    }

    /**
     * 判断key存不存在
     *
     * @param $name
     * @param null $dbName
     * @return bool
     */
    public function exists($name, $dbName = null)
    {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->exists($this->formatKey($name));
    }


    /**
     * 自增
     *
     * @param $name
     * @param null $dbName
     * @return int
     */
    public function setInc($name, $dbName = null)
    {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->incr($this->formatKey($name));
    }

    /**
     * 设置过期时间
     *
     * @param $name
     * @param $expiration
     * @return bool
     */
    public function setExpire($name, $expiration) {
        return $this->handler->expire($this->formatKey($name), $expiration);
    }

    /**
     * 获取key的到期时间
     *
     * @param $name
     * @return int
     */
    public function setTtl($name) {
       return $this->handler->ttl($this->formatKey($name));
    }

    /**
     * 集合中添加元素
     *
     * @param $name
     * @param $value
     * @param null $dbName
     * @return int
     */
    public function setSAdd($name, $value, $dbName = null) {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->sAdd($this->formatKey($name), $this->formatValue($value));
    }


    /**
     * 返回集合中的所有成员
     *
     * @param $name
     * @param $dbName
     * @return array
     */
    public function setSMembers($name, $dbName = null) {
        if(!is_null($dbName)) {
            $this->switchDB($dbName);
        }
        return $this->handler->sMembers($this->formatKey($name));
    }

    /**
     * 格式化key
     *
     * @param $key
     * @return string
     */
    public function formatKey($key)
    {
        return $this->options['prefix'].$key;
    }

    /**
     * 格式化value
     *
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return (is_array($value) || is_object($value)) ? json_encode($value) : $value;
    }

    /**
     * 根据名称自动切库
     *
     * @param $dbName
     */
    public function switchDB($dbName)
    {
        $redisName = C('REDIS_DB_NAME');
        foreach($redisName as $key => $value) {
            if($key != $dbName) {
                continue;
            }
            $redisDB = $value;
        }
        $this->handler->select($redisDB);
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name)
    {
        return $this->handler->delete( $this->formatKey($name));
    }

    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear()
    {
        return $this->handler->flushDB();
    }

}
