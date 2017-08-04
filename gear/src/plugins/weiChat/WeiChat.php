<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\weiChat;


use src\plugins\weiChat\drivers\NPqyWeichat;
use src\plugins\weiChat\drivers\NPweiChat;

class WeiChat
{
    private $configs=[];

    public function __construct($configs)
    {
        $this->configs=$configs;
    }

    /**
     * 获得公众号辅助对象
     * @param 配置名
     * @return NPweiChat
     */
    public function getOfficialAccount($name){
        $rel= new NPweiChat();
        $rel->init($this->configs['official_account'][$name]);
        return $rel;
    }

    /**
     * 获得企业号辅助对象
     * @param 配置名
     * @return NPqyWeichat
     */
    public function getEnterpriseAccount($name){
        $rel= new NPqyWeichat();
        $rel->init($this->configs['enterprise_account'][$name]);
        return $rel;
    }
}