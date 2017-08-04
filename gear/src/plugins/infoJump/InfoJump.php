<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\infoJump;


class InfoJump
{
    public function success($msg = '',$url_self, $url_jump = '', $count_down = 0)
    {
        $type = 'success';
        if (is_array($msg)) {
            $title = isset($msg[0]) ? $msg[0] : '成功 Success';
            $info = $msg[1] ? $msg[1] : '';
        } else {
            $title = '成功 Success';
            $info = $msg;
        }
        maker()->sender()->redirect(url('plugin/infoJump', compact('type', 'title', 'info','url_self', 'url_jump', 'count_down')));
    }

    public function warning($msg = '',$url_self, $url_jump = '', $count_down = 0)
    {
        $type = 'warning';
        if (is_array($msg)) {
            $title = isset($msg[0]) ? $msg[0] : '警告 Warning';
            $info = $msg[1] ? $msg[1] : '';
        } else {
            $title = '警告 Warning';
            $info = $msg;
        }
        maker()->sender()->redirect(url('plugin/infoJump', compact('type', 'title', 'info','url_self', 'url_jump', 'count_down')));
    }

    public function error($msg = '',$url_self, $url_jump = '', $count_down = 0)
    {
        $type = 'error';
        if (is_array($msg)) {
            $title = isset($msg[0]) ? $msg[0] : '错误 Error';
            $info = $msg[1] ? $msg[1] : '';
        } else {
            $title = '错误 Error';
            $info = $msg;
        }
        maker()->sender()->redirect(url('plugin/infoJump', compact('type', 'title', 'info','url_self', 'url_jump', 'count_down')));
    }

    public function attention($msg = '',$url_self, $url_jump = '', $count_down = 0)
    {
        $type = 'attention';
        if (is_array($msg)) {
            $title = isset($msg[0]) ? $msg[0] : '注意 Attention';
            $info = $msg[1] ? $msg[1] : '';
        } else {
            $title = '注意 Attention';
            $info = $msg;
        }
        maker()->sender()->redirect(url('plugin/infoJump', compact('type', 'title', 'info','url_self', 'url_jump', 'count_down')));
    }

    public function notFound($msg = '',$url_self, $url_jump = '', $count_down = 0)
    {
        $type = 'notFound';
        $title = '找不到页面 404 NOT FOUND ';
        $info = $msg;
        maker()->sender()->redirect(url('plugin/infoJump', compact('type', 'title', 'info','url_self', 'url_jump', 'count_down')));

    }
}