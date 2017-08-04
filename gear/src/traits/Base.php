<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/20
 * Time: 15:02
 */

namespace src\traits;


abstract class Base
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->initConfigs();
        $this->init();
    }

    /**
     * 实例化时加载配置
     */
    private function initConfigs()
    {
        if ($this->getConfigFilePath() and is_file($this->getConfigFilePath())) {
            $configs = require $this->getConfigFilePath();
            $this->set($configs);
        }
    }

    /**
     * 获取config文件的路径
     */
    protected function getConfigFilePath(){
        return false;
    }

    /**
     * 为对象成员赋值
     * @param $name string|array
     * @param $value
     * @return $this
     */
    public function set($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->$k = $v;
            }
        } else {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * 实例化时自动运行
     */
    protected function init()
    {

    }

    /**
     * 获取成员对象
     * @param $key string
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }
}