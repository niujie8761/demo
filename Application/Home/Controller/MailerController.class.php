<?php
/**
 * MailerController.class.php
 *
 * Copyright (c) 2018 南京码动通信科技有限公司 All rights reserved.{@see http://www.digirun.cn}
 *
 * @copyright Copyright (c) 2018 Digirun.cn All rights reserved.
 * @author Niu Jie<1033751979@qq.com>
 * @since 2018/2/9 11:19 created
 */


namespace Home\Controller;


class MailerController
{
    public function sendMail() {
        echo 123;
        $result = send_email('2318741485@qq.com', '邮件标题', '邮件内容');
        echo '<pre>';
        print_r($result);
    }
}