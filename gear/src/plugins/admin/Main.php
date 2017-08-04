<?php

namespace src\plugins\admin;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** gear 管理员 */
class Main extends Plugin
{
    const EVENT_PLUGIN_ADMIN_ON_CHECK_ADMIN='EVENT_PLUGIN_ADMIN_ON_CHECK_ADMIN';
    protected $configs=[];

    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {
        Event::addListener(self::EVENT_PLUGIN_ADMIN_ON_CHECK_ADMIN,function (){
            $isAdmin=(true===(maker()->session()->setPrefix('plugin.admin')->offsetGet('isAdmin'))
                    or true===(maker()->cookie()->setPrefix('plugin_admin')->getCookie('isAdmin')));
            if ($isAdmin!==true){
                maker()->sender()->redirect(url('plugin/admin'),['action'=>'login','jump'=>url()]);
            }
        });
        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
        $cookie=maker()->cookie()->setPrefix('plugin_admin');
        $session=maker()->session()->setPrefix('plugin.admin');
        $action=request('action');
        $page=__DIR__.DS.'pages'.DS.$action.'.php';
        if (is_file($page)){
            require $page;
        }else{
            maker()->sender()->error('Can not access this action.','back');
        }
        Event::freezeAll();
    }

    /** 返回管理员密码的md5,默认md5(gear)=fae005cf393a8ec646f3fe040339fd9d */
    public static function getPsw()
    {
        return file_get_contents(__DIR__ . '/password.php');
    }

    /**
     * 是否是默认密码（不安全）
     * @return bool
     */
    public static function isDefaultPsw()
    {
        return self::getPsw() === 'fae005cf393a8ec646f3fe040339fd9d';
    }
}