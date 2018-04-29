<?php

/* * ****************don't use pool(不用连接池 最原始的方式)************************ */
$obj = new Redis();
$rs = $obj->connect("192.168.20.130");
$obj->select(5);
$obj->set("test", '1111');
var_dump($obj->get("test"));

$obj = new PDO('mysql:host=192.168.20.130;dbname=test1', "admin", "admin");
$rs = $obj->query("show tables");
var_dump($rs->fetchAll());

//*****************use pool（使用了连接池）*********************************/
$obj = new redisProxy();
$rs = $obj->connect("192.168.20.130");
$obj->select(5);
$obj->set("test", '1111');
var_dump($obj->get("test"));
$obj->release();

$obj1 = new pdoProxy('mysql:host=192.168.20.131;dbname=db1', "admin", "admin");
$rs = $obj1->query("show tables");
var_dump($rs->fetchAll());
$obj1->release();


/* * ****************异步 pdo和redis操作**********************************************
 * 依赖 swoole的event函数
 */
include './asyncClass.php';
$obj = new asyncRedisProxy();
$obj->connect("127.0.0.1", "6379");
$obj->set("a", 11111, function($obj, $ret) {
    $obj->get("a", function($obj, $data) {
        var_dump($data);
        $obj->release(); //release to con pool
    });
});


$obj2 = new asyncPdoProxy('mysql:host=192.168.1.19;dbname=mz_db', "public_user", "1qa2ws3ed");
$obj2->query("select 1 from mz_user where user_id=299", function($obj, $stmt) {
    $arr = $stmt->fetchAll();
    var_dump($arr);
    $obj->query("select 2 from mz_user where user_id=299", function($obj, $stmt) {
        $arr = $stmt->fetchAll();
        var_dump($arr);
        $obj->release(); //release to con pool
    });
});


$obj3 = new asyncPdoProxy('mysql:host=192.168.1.19;dbname=mz_db', "public_user", "1qa2ws3ed");
$obj3->exec("insert into t1(name) values('111111')", function($obj, $data) {
    var_dump($data);
    $obj->release(); ////release to con pool
});

$obj4 = new asyncPdoProxy('mysql:host=192.168.1.19;dbname=mz_db', "public_user", "1qa2ws3ed");
$stmt = $obj4->prepare("select * from mz_account where user_id=:user_id");
$stmt->bindParam(':user_id', "311");
$stmt->execute(function($stmt, $ret) {
    $data = $stmt->fetchAll();
    var_dump($data);
    $stmt->release();
});


//*******************use master slave(最新版本支持了读写分离和从库的负载均衡 用法如下)***********************/
$config = array(
    'master' => array(
        'data_source' => "mysql:host=192.168.1.19;dbname=db1",
        'username' => "public_user",
        'pwd' => "1qa2ws3ed",
        'options' => array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 3,
            PDO::ATTR_CASE => PDO::CASE_UPPER,
        ),
    ),
    'slave' => array(
        "0" => array(
            'data_source' => "mysql:host=192.168.1.20;dbname=db2",
            'username' => "public_user",
            'pwd' => "1qa2ws3ed",
            'options' => array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3,
                PDO::ATTR_CASE => PDO::CASE_UPPER,
            ),
        ),
        "1" => array(
            'data_source' => "mysql:host=192.168.1.21;dbname=db3",
            'username' => "public_user",
            'pwd' => "1qa2ws3ed",
            'options' => array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3,
                PDO::ATTR_CASE => PDO::CASE_LOWER,
            ),
        ),
    ),
);
/* * *************************"select"和"show"开头的语句 走随机从库********** */
$obj1 = new pdoProxy($config);
$rs = $obj1->query("select * from test limit 1");
var_dump($rs->fetchAll()); //走随机从库
$obj1->release();

/* * **************************读强行走主库*************************** */
$obj1->enable_slave = false;
$rs = $obj1->query("select * from test limit 1");
var_dump($rs->fetchAll()); //读主库
$obj1->release();

/* * *************************除了"select"和"show"开头的语句 都走主库********** */
$sql = "insert into `test` (tid) values (5)";
$rs = $obj1->exec($sql); //走主库
$obj1->release();

/* tips：
 * 1、The relase() method will release the connections to the pool that the process holds.
 * 2、after rshutdown/mshutdown will trigger the release() function.
 */


/* 说明：
 * 1、relase方法：通知中间件,可以将这个进程持有的链接放回连接池
 * 2、请求结束（rshutdown/mshutdown阶段）会调用自动调用release
 */
