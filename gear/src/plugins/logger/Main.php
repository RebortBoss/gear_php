<?php

namespace src\plugins\logger;


use src\cores\Config;
use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 日志管理 */
class Main extends Plugin
{
    protected $configs = [];

    protected function getConfigFilePath()
    {
        return __DIR__ . '/config.php';
    }

    public function main()
    {
        $self = $this;
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'logger', function () use ($self) {
            $obj = new Logger();
            $obj->set($self->configs)->initLogger();
            Factory::addRecipe('logger', function () use ($obj) {
                return $obj;
            });
        });
        Event::bindListener(\src\cores\Main::EVENT_ON_SHUTDOWN, function () {
            //在关闭前纪录日志
            if (config(Config::LOG_VISITOR_INFO)) {
                $url = url();
                maker()->logger()->info("visited $url");
            }
        });
        return true;
    }

    /** 从路由直接访问的方法 */
    public function direct()
    {
        Event::trigger(\src\plugins\admin\Main::EVENT_PLUGIN_ADMIN_ON_CHECK_ADMIN);
        $log = request('log');
        switch ($this->configs['handler']) {
            case 'file':
                config(Config::SHOW_DEBUG_BTN,false);
                if ($log) {
                    if ($log == 'today') {
                        $file = $this->configs['filePreFix'];
                    } else {
                        $file = dirname($this->configs['filePreFix']) . DS . $log;
                    }
                    $log_content = maker()->file()->readFile($file);
                    $lines = \Yuri2::explodeWithoutNull("\n", $log_content);
                    require __DIR__ . '/pages/display.php';
                }else {
                    $dir=dirname($this->configs['filePreFix']);
                    $logs=[];
                    maker()->file()->createDir($dir);
                    maker()->file()->ergodicDir($dir,function ($file) use (&$logs,$dir){
                        $url= url('plugin/logger',['log'=>$file]);
                        $full_file_name=$dir.DS.$file;
                        $size=filesize($full_file_name);
                        $logs[]=['url'=>$url,'file'=>$file,'size'=>$size];
                    });
                    require __DIR__ . '/pages/lists.php';
                }
                break;
            default:
                echo 'Error:unknown log handler.';
                break;
        }
    }
}