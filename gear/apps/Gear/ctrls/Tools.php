<?php

namespace apps\Gear\ctrls;


use src\cores\Config;
use src\traits\Ctrl;

class Tools extends Ctrl
{

    public function appMap()
    {
        //网站导览
        //查找目前的模块
        $modules=[];
        \Yuri2::ergodicDir(PATH_APPS,function($file) use (&$modules){
            if (preg_match('/^[A-Za-z\d]+$/',$file) and is_dir(PATH_APPS.'/'.$file)){
                $module=['moduleName'=>$file,'ctrls'=>[]];
                \Yuri2::ergodicDir(PATH_APPS.DS.$file.'/ctrls',function($ctrl) use (&$module){
                    if(!(preg_match('/\.php$/',$ctrl))){
                        return;
                    }
                    $ctrlName=preg_replace('/\.php$/','',$ctrl);
                    $ctrl=[
                        'ctrlName'=>$ctrlName,
                        'infos'=>self::readCtrl($module['moduleName'],$ctrlName)
                    ];
                    $module['ctrls'][]=$ctrl;
                });
                $modules[]=$module;
            }
        });

        $this->assign('modules',$modules);
        $this->render();
    }

    /**
     * 返回一个控制器的结构信息
     * @param $moduleName string
     * @param $ctrlName string
     * @return array
     */
    static function readCtrl($moduleName,$ctrlName){
        $className="apps\\$moduleName\\ctrls\\$ctrlName";
        $classRef=new \ReflectionClass($className);
        $publicMethods=$classRef->getMethods(\ReflectionMethod::IS_PUBLIC);
        $infos=[];
        foreach ($publicMethods as $publicMethod){
            if ($publicMethod->isStatic()){continue;}
            $info=[];
            $info['name']=$publicMethod->getName();
            if(in_array($info['name'],[
                '__construct',
                'assign',
                'init',
                'render',
                'getAssigns',
                'set',
                'get',
            ])) {
                continue;
            }
            $info['doc']=$publicMethod->getDocComment();
            if (!$info['doc']){$info['doc']='';}
            $paramNames=[];
            $params=$publicMethod->getParameters();
            foreach ($params as $param){
                $paramNames[]=$param->getName();
            }
            $info['params']=implode(',',$paramNames);
            $info['url']=url("$moduleName/$ctrlName/".$info['name']);
            $infos[]=$info;
        }
        return $infos;
    }

    public function codeTest($action='frame')
    {
        $testFile=PATH_APPS.'/Gear/others/codeTest.php';
        $scratchFile=PATH_APPS.'/Gear/others/codeScratch.php';
        switch ($action){
            case 'frame':
                $code=maker()->file()->readFile($testFile);
                $scratch=maker()->file()->readFile($scratchFile);
                $this->assign('code',$code);
                $this->assign('scratch',$scratch);
                $this->render();
                break;
            case 'preview':
                if (!is_file($testFile)){
                    maker()->file()->writeFile($testFile,'');
                }
                config('debug',true);
                config(Config::SHOW_DEBUG_BTN, true);
                require $testFile;
                break;
            case 'save':
                $this->apiMode();
                $code=request('code');
                maker()->file()->writeFile($testFile,$code);
                return [
                    'state'=>'success'
                ];
                break;
            case 'scratch':
                $this->apiMode();
                $scratch=request('scratch');
                maker()->file()->writeFile($scratchFile,$scratch);
                return [
                    'state'=>'success'
                ];
                break;
            default:
                break;
        }

    }

    /**
     * webShell
     * @naples admin
     */
    public function webShell(){
        $this->render();
    }

}