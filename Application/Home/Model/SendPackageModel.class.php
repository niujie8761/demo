<?php
/**
 * SendPackageModel.class.php
 *
 * Copyright (c) 2018 南京码动通信科技有限公司 All rights reserved.{@see http://www.digirun.cn}
 *
 * @copyright Copyright (c) 2018 Digirun.cn All rights reserved.
 * @author Niu Jie<1033751979@qq.com>
 * @since 2018/1/13 15:55 created
 */


namespace Home\Model;

use Home\Model\BaseModel;

class SendPackageModel   extends  BaseModel
{
    private static $instance = array();
    public static function getInstance($name) {
        if(self::$instance[$name] == null) {
            $className = $name."Model";
            if(class_exists($className)) {
                self::$instance[$name] = new $className();
            }else {
                self::$instance[$name] = new self();
            }
        }
        return self::$instance[$name];
    }
}