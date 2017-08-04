<?php

namespace src\plugins\qrCode;


use src\cores\Event;
use src\cores\Factory;
use src\traits\Plugin;

/** 插件模板 */
class Main extends Plugin
{
    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {
        Event::addListener(Factory::EVENT_NEED_RECIPE . 'qrCode', function () {
            require __DIR__ . '/qrCodeBase.php';
            $obj = new QrCode();
            Factory::addRecipe('qrCode', function () use ($obj) {
                return $obj;
            });
        });
        return true;
    }

    /** 从路由直接访问的方法 */
    public function direct()
    {
        $qr_content = request('content');
        $water = request('water') ? request('water') : 'gear';
        $size = request('size') ? request('size') : 3;
        $margin = request('margin') ? request('margin') : 1;
        $is_cache = request('is_cache');
        $token=request('token');
        if(check_token($token)){
            $qr = maker()->qrCode();
            $file = $qr->setQrContent($qr_content)
                    ->setWater($water)
                    ->setSize($size)
                    ->setMargin($margin)
                    ->getCodeFile();
            $img_qr=maker()->image($file);
            Event::freezeAll();
            $img_qr->display();
            if ($is_cache=='yes'){
                unlink($file);
            }
        }else{
            Event::freezeAll();
        }
        exit();
    }
}