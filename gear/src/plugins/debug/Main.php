<?php

namespace src\plugins\debug;


use Pinq\Traversable;
use src\cores\Config;
use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 插件模板 */
class Main extends Plugin
{

    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        $self = $this;
        Event::bindListener(Factory::EVENT_NEED_RECIPE . 'debug', function () use ($self) {
            $obj = new Debug();
            $obj->set($self->configs);
            Factory::addRecipe('debug', function () use ($obj) {
                return $obj;
            });
        });
        Event::bindListener(\src\cores\Main::EVENT_ON_SHUTDOWN, function () {
            if (config(Config::DEBUG) and !IS_CLI) {
                maker()->debug()->saveReport();
            }
        });
        return true;
    }

    /** 从路由直接访问的方法 */
    public function direct()
    {
        //先创建文件夹
        maker()->file()->createDir(PATH_RUNTIME . "/reports");

        Event::trigger(\src\plugins\admin\Main::EVENT_PLUGIN_ADMIN_ON_CHECK_ADMIN);
        $id = request('id');
        $path = PATH_RUNTIME . "/reports/{$id}.php";
        if ($id == '') {
            //list
            $reports = [];
            maker()->file()->ergodicDir(PATH_RUNTIME . "/reports", function ($file) use (&$reports) {
//                echo $file . '<br>';
                $repots_path = PATH_RUNTIME . "/reports/" . $file;
                $report = unserialize(maker()->file()->readFile($repots_path));
                $reports[] = $report;
            });
            $reports = maker()->pinq($reports);
            $rows = $reports
                ->orderByDescending(function ($row) {
                    return $row['basic']['timestamp'];
                })
                ->select(function ($row) {
                    return [
                        'ID' => $row['basic']['ID'],
                        'date' => date('Y-m-d H:i:s', $row['basic']['timestamp']),
                        'errors' => isset($row['errors']) ? count($row['errors']) : 0,
                        'url' => $row['basic']['url'],
                        'link' => url('plugin/debug', ['id' => $row['basic']['ID']]),
                    ];
                });
            require __DIR__ . '/reportsListRender.php';
        } elseif ($id and is_file($path)) {
            $report = unserialize(maker()->file()->readFile($path));
            require __DIR__ . '/reportsRender.php';
        } else {
            maker()->sender()->notFound('Can not find this report:<br>' . $id);
        }
        Event::freezeAll();

    }
}