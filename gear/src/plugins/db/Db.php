<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\db;


use src\cores\PluginManager;

class Db
{
    private $pdo;
    private $convention = null;
    private $orm = null;
    private $debug = false;

    public function __construct($config_name = 'local', $convention = [])
    {
        $configs = PluginManager::getPlugin('db')->get('configs');
        $dsn = $configs[$config_name]['dsn'];
        $usn = $configs[$config_name]['usn'];
        $psw = $configs[$config_name]['psw'];
        $this->debug = isset($configs[$config_name]['debug']) ? $configs[$config_name]['debug'] : false;
        $this->pdo = new \PDO($dsn, $usn, $psw);
        switch (count($convention)) {
            case 1:
                $this->convention = new \NotORM_Structure_Convention($convention[0]);
                break;
            case 2:
                $this->convention = new \NotORM_Structure_Convention($convention[0],$convention[1]);
                break;
            case 3:
                $this->convention = new \NotORM_Structure_Convention($convention[0],$convention[1],$convention[2]);
                break;
            case 4:
                $this->convention = new \NotORM_Structure_Convention($convention[0],$convention[1],$convention[2],$convention[3]);
                break;
            default:
                $this->convention = empty($configs[$config_name]['convention']) ?
                    null :
                    new \NotORM_Structure_Convention(
                        $configs[$config_name]['convention']['primary'],
                        $configs[$config_name]['convention']['foreign'],
                        $configs[$config_name]['convention']['table'],
                        $configs[$config_name]['convention']['prefix']
                    );
                break;
        }

    }

    /**
     * 获取notorm
     * @return \NotORM
     */
    public function getOrm()
    {
        if (is_null($this->orm)) {
            $this->orm = is_null($this->convention) ?
                new \NotORM($this->pdo) :
                new \NotORM($this->pdo, $this->convention);
            $this->orm->debug = $this->debug;
        }
        return $this->orm;
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * 开始事务
     */
    public function beginTransaction()
    {
        $this->getOrm()->transaction = 'BEGIN';
    }

    /**
     * 提交事务
     */
    public function commitTransaction()
    {
        $this->getOrm()->transaction = 'COMMIT';
    }

    /**
     * 回滚事务
     */
    public function rollbackTransaction()
    {
        $this->getOrm()->transaction = 'ROLLBACK';
    }
}