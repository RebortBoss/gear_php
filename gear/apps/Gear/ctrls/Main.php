<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/5
 * Time: 14:30
 */

namespace apps\Gear\ctrls;

use src\cores\Config;
use src\traits\Ctrl;

class Main extends Ctrl
{
    public function index(){
        config(Config::SHOW_DEBUG_BTN,false);
        $md=maker()->file()->readFile(PATH_ROOT.'/README.md');
        $readme=maker()->format()->mdToHtml($md);
        $this->assign('readme',$readme);
        $cache_key = 'gear_bing_img_fetch';
        if (cache()->has($cache_key)) {
            $bing = cache()->get($cache_key);
        } else {
            $bing = \Yuri2::bingImgFetch(); //获取bing的壁纸和小故事
            if ($bing) {
                cache()->set($cache_key, $bing, 3600);
            }
        }
        $this->assign('bing', $bing);
        $this->render();
    }

    public function readme(){
        $readme=maker()->file()->readFile(PATH_ROOT.'/README.md');
        if(!$readme){$readme='';}
        $html=maker()->format()->mdToHtml($readme);
        echo $html;
    }

    public function getDirSize(){
        $this->apiMode();
        $cache_name='gear_get_dir_size';
        if($data=cache()->get($cache_name)){
            return $data;
        }else{
            $data= [
                'size_gear'=>maker()->format()->byteSize(maker()->file()->getDirSize(PATH_GEAR)),
                'size_public'=>maker()->format()->byteSize(maker()->file()->getDirSize(PATH_PUBLIC)),
                'size_runtime'=>maker()->format()->byteSize(maker()->file()->getDirSize(PATH_RUNTIME)),
            ];
            cache()->set($cache_name,$data,60);
            return $data;
        }

    }

}