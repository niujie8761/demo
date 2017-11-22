<?php
/**
 * Created by PhpStorm.
 * User: 365
 * Date: 2017/11/22
 * Time: 15:38
 */
return array(
    'DATA_CACHE_PREFIX'      => 'Redis_',      // 缓存前缀
    'DATA_CACHE_TYPE'        => 'Redis', // 数据缓存类型,
    'REDIS_HOST'             => '127.0.0.1', // 服务器ip
    'REDIS_PORT'             => '6379',//端口号
    'DATA_CACHE_TIMEOUT'     => '300',//超時時間
    'DATA_CACHE_TIME'        => '10800',
    'REDIS_PERSISTENT'       =>  false,//是否长连接 false=短连接
    'REDIS_AUTH'             =>  '',//AUTH认证密码

    'REDIS_DB_NAME' => array(
        'name1' => 0,
        'name2' => 1,
        'name3' => 2,
        'name4' => 3,
        'name5' => 4,
        'name6' => 5,
        'name7' => 6,
        'name8' => 7,
        'name9' => 8,
        'name10' => 9,
        'name11' => 10,
        'name12' => 11,
        'name13' => 12,
        'name14' => 13,
        'name15' => 14,
        'name16' => 15,
    ),
);