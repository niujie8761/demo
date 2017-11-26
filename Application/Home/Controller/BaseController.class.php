<?php
/**
 * BaseController.class.php
 *
 * Copyright (c) 2017 南京码动通信科技有限公司 All rights reserved.{@see http://www.digirun.cn}
 *
 * @copyright Copyright (c) 2017 Digirun.cn All rights reserved.
 * @author Niu Jie<1033751979@qq.com>
 * @since 2017/11/26 10:11 created
 */


namespace Home\Controller;


use Think\Controller;

class BaseController extends Controller
{
    protected $userInfo;
    protected $city = 'nj';

    public function __construct() {
        parent::__construct();
        if(!empty(session('userInfo'))) {
            $this->userInfo = session('userInfo');
        }
        if(!empty(session('city'))) {
            $this->city = session('city');
        }else {
            $this->city = $this->userInfo['kam_city'];
        }
    }
}