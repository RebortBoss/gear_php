<?php

namespace src\plugins\infoJump;


use src\cores\Config;
use src\cores\Event;
use src\cores\Factory;
use src\plugins\factory\libs\Sender;
use src\traits\Plugin;

/** 带信息的 跳转 */
class Main extends Plugin
{

    protected function getConfigFilePath()
    {
        return false;
    }

    public function main()
    {
        Event::addListener(Factory::EVENT_NEED_RECIPE . 'infoJump', function () {
            $obj = new InfoJump();
            Factory::addRecipe('infoJump', function () use ($obj) {
                return $obj;
            });
        });
        Event::addListener(Sender::EVENT_INFO_SUCCESS, function (Event $event) {
            $jumper = maker()->infoJump();
            $jumper->success($event['msg'], $event['url_self'], $event['url_jump'], $event['count_down']);
            $event->stopSpread();
        });
        Event::addListener(Sender::EVENT_INFO_WARNING, function (Event $event) {
            $jumper = maker()->infoJump();
            $jumper->warning($event['msg'], $event['url_self'], $event['url_jump'], $event['count_down']);
            $event->stopSpread();
        });
        Event::addListener(Sender::EVENT_INFO_ERROR, function (Event $event) {
            if(config(Config::API_MODE)){
                $msg = is_array($event['msg']) ? implode('.', $event['msg']) : $event['msg'];
                switch (config(Config::API_FORMAT)) {
                    case 'json':
                        $rel = maker()->format()->arrayToJson(['msg' => $msg]);
                        break;
                    case 'xml':
                        $rel = maker()->format()->arrayToXml(['msg' => $msg]);
                        break;
                    default:
                        $rel = '';
                }
                echo $rel;
            }else{
                $jumper = maker()->infoJump();
                $jumper->error($event['msg'], $event['url_self'], $event['url_jump'], $event['count_down']);
                $event->stopSpread();
            }
        });
        Event::addListener(Sender::EVENT_INFO_ATTENTION, function (Event $event) {
            $jumper = maker()->infoJump();
            $jumper->attention($event['msg'], $event['url_self'], $event['url_jump'], $event['count_down']);
            $event->stopSpread();
        });
        Event::addListener(Sender::EVENT_INFO_NOT_FOUND, function (Event $event) {
            $jumper = maker()->infoJump();
            $jumper->notFound($event['msg'], $event['url_self'], $event['url_jump'], $event['count_down']);
            $event->stopSpread();
        });
        return true;
    }

    /** 从路由直接访问的方法 */
    public function direct()
    {
        //此处冻结infojump的事件，防止无限跳转
        Event::freeze(Sender::EVENT_INFO_SUCCESS);
        Event::freeze(Sender::EVENT_INFO_WARNING);
        Event::freeze(Sender::EVENT_INFO_ERROR);
        Event::freeze(Sender::EVENT_INFO_ATTENTION);

        if (request('type')) {
            include __DIR__ . "/type/universal.php";
        }
        config(Config::DEBUG, false);
    }
}