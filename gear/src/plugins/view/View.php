<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\view;


use src\plugins\file\File;
use src\traits\Base;

class View extends Base
{
    const LANG_ERROR_VIEW_FILE_NOT_FOUND = 'Can not find view file';
    const LANG_ERROR_TOO_MUCH_REPLACE = 'Too much replace';

    protected $assigns = [];
    protected $res = '';
    private $viewFileCompiled = '';
    protected $configs = [];
    /** @var  File */
    private $file;

    public function init()
    {
        $this->file = maker()->file();
    }

    public function main()
    {
        $resArr = \Yuri2::explodeWithoutNull('/', $this->res);
        $this->viewFileCompiled = PATH_RUNTIME . DS . 'views' . DS . $resArr[0] . DS . $resArr[1] . DS . $resArr[2] . '.php';
        $this->compile();
        $this->render();
    }

    /** 根据res获取模板文件的内容
     * @param $res
     * @return bool|string
     * @throws \Exception
     */
    private function getViewFileContent($res)
    {
        if (preg_match('/^\/?tpl\/(\w+)\/?$/', $res, $matches)) {
            $viewFile = PATH_PLUGINS . '/view/tpls/' . $matches[1] . '.php';
        } else {
            $res = url_info($res, [], true);
            $resArr = \Yuri2::explodeWithoutNull('/', $res);
            $viewFile = PATH_APPS . DS . $resArr[0] . DS . 'views' . DS . $resArr[1] . DS . $resArr[2] . '.php';
        }
        if (!is_file($viewFile)) {
            throw new \Exception(self::LANG_ERROR_VIEW_FILE_NOT_FOUND . ':' . $viewFile);
        }
        return $this->file->readFile($viewFile);
    }

    /** 编译模板 */
    private function compile()
    {
        //非debug模式下，只有找不到编译后文件或者文件修改时间超时，才进行编译
        $is_need_compile =(
            config('debug') or !is_file($this->viewFileCompiled) or (
                is_file($this->viewFileCompiled)
                and (time() - filemtime($this->viewFileCompiled) > $this->configs['update_time'])
        ));
        if (!$is_need_compile) { return; }

        $page_content = $this->getViewFileContent($this->res); //获取本页的内容
        $page_content = $this->extend($page_content);
        $page_content = $this->inc($page_content);
        foreach ($this->configs['replace'] as $key => $value) {
            $page_content = str_replace($key, $value, $page_content);
        }
        $this->file->writeFile($this->viewFileCompiled, $page_content);
    }

    /** 执行模板继承
     * @param $page_content
     * @return mixed
     * @throws \Exception
     */
    private function extend($page_content)
    {
        $counter = 100;
        $preg_extend = '/^\[extend ([\w\\/]+)\]/'; //寻找extend标记的正则
        while (preg_match($preg_extend, $page_content, $matches)) {
            if ($counter-- < 0) {
                throw new \Exception(self::LANG_ERROR_TOO_MUCH_REPLACE);
            }
            $extend_res = $matches[1];//父级模板的res
            $extend_content = $this->getViewFileContent($extend_res);
            $preg_block = '/<block_(\w+)>/';
            while (preg_match($preg_block, $extend_content, $matches)) {
                if ($counter-- < 0) {
                    throw new \Exception(self::LANG_ERROR_TOO_MUCH_REPLACE);
                }
                $p_block_name = $matches[1];
                $preg_block_target = "/<block_$p_block_name>([\\s\\S]*)<\\/block_$p_block_name>/";
                preg_match($preg_block_target, $extend_content, $matches);
                $p_block = $matches[0];
                $p_block_inner = $matches[1];

                //从子模板寻找对应的block
                if (preg_match($preg_block_target, $page_content, $matches)) {
                    $c_block_inner = $matches[1];
                    $extend_content = str_replace($p_block, "<_block_$p_block_name>" . $c_block_inner . "</_block_$p_block_name>", $extend_content);
                } else {
                    $extend_content = str_replace($p_block, "<_block_$p_block_name>" . $p_block_inner . "</_block_$p_block_name>", $extend_content);
                }
            }
            //将临时的_block 变为正常的block
            $extend_content = str_replace("<_block_", '<block_', $extend_content);
            $extend_content = str_replace("</_block_", '</block_', $extend_content);
            $page_content = $extend_content;
        }

        //去掉所有的block外部标签
        $page_content = preg_replace('/<\\/?block_\w+>/', '', $page_content);
        return $page_content;

    }

    /** 执行模板引用
     * @param $page_content
     * @return mixed
     */
    private function inc($page_content)
    {
        $preg_inc = '/\[include ([\w\\/]+)\]/'; //寻找include标记的正则
        while (preg_match($preg_inc, $page_content, $matches)) {
            $inc_self = $matches[0];//待引用模板的res
            $inc_res = $matches[1];//待引用模板的res
            $inc_content = $this->getViewFileContent($inc_res);
            $page_content = str_replace($inc_self, $inc_content, $page_content);
        }
        return $page_content;
    }

    /** 引用渲染后的文件 */
    private function render()
    {
        extract($this->assigns);
        require $this->viewFileCompiled;
    }


}