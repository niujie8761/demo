<?php
/**
 * Created by PhpStorm.
 * User: niuJie
 * Description: 365房管家后台重构
 * Date: 2017/12/8
 * Time: 11:00
 */

namespace Home\Controller;


use Home\Model\LouPanModel;
use Home\Model\MangerModel;
use Home\Model\ProjectManagerModel;

class ProjectController extends BaseController
{
    public function index()
    {
        $pageSize = isset($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : 10;
        $city = $this->city;
        //获取城市的区属
        $url = 'http://api.house365.com/xf/newhouse/get_config.php?type=channel&ctype=dist&city='.$city;
        $result = curl_get_contents($url);
        $dist = gbk_to_utf8(unserialize($result));

        //城市的楼盘
        $where = array('city' => $city);
        $fields = "lp_id, lp_name, lp_code, city, status, sort, sale_type, who_id, who_name, cooper_start, 
        cooper_end, create_time, lp_dist, charge_type, charge_price, charge_tag";
        $louPanM = LouPanModel::getInstance('louPan');
        $total = $louPanM->findCount($where);
        $page = getPage($total, $pageSize);
        $order = array('sort' => 'desc', 'lp_id' => 'desc');
        $louPanList = $louPanM->pageData($where, $page->firstRow, $page->listRows, $order, $fields);
        //楼盘所属的区属
        foreach($dist['dist'] as $key => $value) {
            foreach($louPanList as $k => $v) {
                if($key == $v['lp_dist']) {
                    $louPanList[$k]['lp_dist_name'] = $value;
                }
            }
        }
        //楼盘绑定的驻场
        foreach ($louPanList as $kt => $vt) {
            $projectManagerM = ProjectManagerModel::getInstance('projectManager');
            $condition = array('lp_id' => $vt['lp_id'], 'ismajor' => 1);
            $projectMInfo = $projectManagerM->findData($condition);
            if(!empty($projectMInfo)) {
                $condition = array('kam_id' => $projectMInfo['kam_id']);
                $mangerM = MangerModel::getInstance('manger');
                $mangerInfo = $mangerM->findData($condition);
                $louPanList[$kt]['kam_nickname'] = $mangerInfo['kam_nickname'];
            }
        }
        $data = array(
            'dist' => $dist['dist'],
            'louPan' => $louPanList,
            'page' => $page->show()
        );
        $this->assign($data);
        $this->display();
    }
}