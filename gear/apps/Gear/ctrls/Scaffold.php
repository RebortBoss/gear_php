<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/5
 * Time: 16:30
 */

namespace apps\Gear\ctrls;


use apps\Gear\others\CurdBuilder;
use src\traits\Ctrl;

class Scaffold extends Ctrl
{

    public function module($action=''){
        switch ($action){
            case 'create':
                $module_name=request('module_name');
                if (preg_match('/^[A-Z]\w*$/',$module_name)){
                    $dir_module=PATH_APPS.DS.$module_name;
                    \Yuri2::createDir($dir_module);
                    \Yuri2::createDir($dir_module.'/configs');
                    \Yuri2::createDir($dir_module.'/ctrls');
                    \Yuri2::createDir($dir_module.'/views');
                    \Yuri2::createDir($dir_module.'/models');
                    \Yuri2::createDir($dir_module.'/data');
                    \Yuri2::createDir($dir_module.'/others');
                    $file_autorun=$dir_module.'/configs/autorun.php';
                    $file_config=$dir_module.'/configs/global.php';
                    $file_plugin=$dir_module.'/configs/plugin.php';
                    $dir_public=PATH_PUBLIC.DS.'apps'.DS.$module_name;
                    \Yuri2::createDir($dir_public.'/js');
                    \Yuri2::createDir($dir_public.'/img');
                    \Yuri2::createDir($dir_public.'/css');
                    \Yuri2::createDir($dir_public.'/upload');

                    if (!is_file($file_autorun)){file_put_contents($file_autorun,'<?php '.RN);}
                    if (!is_file($file_config)){file_put_contents($file_config,'<?php '.RN.'return [];');}
                    if (!is_file($file_plugin)){file_put_contents($file_plugin,'<?php '.RN.'return [];');}
                    maker()->sender()->success(['Job done!',"Module $module_name has been successfully established."],'back',2);
                }else{
                    maker()->sender()->error(['Create failed',"Module name $module_name is illegal."],'back',3);
                }
                break;
            default:
                $this->render();
                break;
        }

    }
    public function ctrl($action=''){
        switch ($action){
            case 'create':
                $ctrl_name=request('ctrl_name');
                $module_name=request('module_name');
                $action_names=request('action_names');
                if (preg_match('/^[A-Z]\w*$/',$ctrl_name)){
                    $dir_module=PATH_APPS.DS.$module_name;
                    $dir_check=
                        is_dir($dir_module.'/ctrls') and
                        is_dir($dir_module.'/views');
                    if (!$dir_check){
                        maker()->sender()->warning(['Ops~',"You need create module $module_name first."],'back',3);
                    }else{
                        $actions=\Yuri2::explodeWithoutNull(',',$action_names);
                        $action_content='';
                        foreach ($actions as $action){
                            $action_content.=RN."    public function $action()\r\n    {\r\n        //\$this->render();    \r\n    }\r\n ";
                            $view_file=$dir_module.'/views/'.$ctrl_name.'/'.$action.'.php';
                            if (!is_file($view_file)){
                                $view_content="<gear-extend>tpl/base_element</gear-extend>
<gear-block-title>$action</gear-block-title>
<gear-block-head> </gear-block-head>
<gear-block-body> </gear-block-body>";
                                maker()->file()->writeFile($view_file,$view_content);
                            }
                        }
                        $ctrl_content=<<<EOT
<?php

namespace apps\\$module_name\\ctrls;

use src\\traits\\Ctrl;

class $ctrl_name extends Ctrl
{

$action_content

}
EOT;

                        $ctrl_file=$dir_module.'/ctrls/'.$ctrl_name.'.php';
                        if (!is_file($ctrl_file)){
                            maker()->file()->writeFile($ctrl_file,$ctrl_content);
                        }
                    }
                    maker()->sender()->success(['Job done!',"Controller $ctrl_name has been successfully established."],'back',2);
                }else{
                    maker()->sender()->error(['Create failed',"Controller name $ctrl_name is illegal."],'back',3);
                }
                break;
            default:
                $modules=[];
                maker()->file()->ergodicDir(PATH_APPS,function ($name) use (&$modules){
                    $modules[]=$name;
                });
                $this->assign('modules',$modules);
                $this->render();
                break;
        }

    }
    public function curd($action=''){
        switch ($action){
            case 'create':
                $module_name=request('module_name');
                $ctrl_name=request('ctrl_name');
                $dir_module=PATH_APPS.DS.$module_name;
                $dir_check=
                    is_dir($dir_module.'/ctrls') and
                    is_dir($dir_module.'/views') and
                    is_dir($dir_module.'/modules') ;
                if (!$dir_check){
                    maker()->sender()->warning(['Ops~',"You need create module $module_name first."],'back',3);
                }
                if (preg_match('/^[A-Z]\w*$/',$ctrl_name)) {
                    $builder=new CurdBuilder(request());
                    $file_state=$builder->main();
                    $this->assign('file_state',$file_state);
                    $this->render('curd_finished');
                }else{
                    maker()->sender()->error(['Create failed',"Controller name $ctrl_name is illegal."],'back',3);
                }
                break;
            default:
                $modules=[];
                maker()->file()->ergodicDir(PATH_APPS,function ($name) use (&$modules){
                    $modules[]=$name;
                });
                $this->assign('modules',$modules);
                $this->render();
                break;
        }

    }
}