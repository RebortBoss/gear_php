<?php

namespace src\plugins\email;


use src\cores\Event;
use src\cores\Factory;
use src\plugins\errorCatch\ErrorCatch;
use src\traits\Plugin;

/** 发送邮件 */
class Main extends Plugin
{
    const cache_name='gear_plugin_email_error_attention_cool_down';
    protected $configs=[];
    protected function getConfigFilePath(){return __DIR__ . '/config.php';}

    public function main()
    {
        $self=$this;
        Event::bindListener(Factory::EVENT_NEED_RECIPE.'email',function () use ($self) {
            Factory::addRecipe('email', function ($account='default') use ($self) {
                $obj = new Email(is_string($account)?$self->configs[$account]:$account);
                return $obj;
            });
        });

        //注册开发者错误提示邮箱
        Event::bindListener(\src\cores\Main::EVENT_ON_SHUTDOWN,function () use ($self){
            $err_num=count(ErrorCatch::getErrors());
            if($self->configs['enable_error_attention'] && $err_num>0){
                //检查是否位于发送邮件的冷却期

                $time_now=time();
                if(!cache()->has(self::cache_name) or cache()->get(self::cache_name)<$time_now){
                    //未处于冷却期,发送邮件
                    $url=url();
                    $ip=\Yuri2::getIp();
                    $cool_down=$time_now+3600*12;
                    $cool_down_date=date('Y/m/d H:i:s',$cool_down);
                    $restore_cool_down_url=url('plugin/email',['action'=>'restore_cool_down','token'=>order_token()]);
                    maker()->email($self->configs['error_attention'])->send(
                        lang('您的GearPHP项目中产生了错误!','Some Errors Has Occurred in GearPHP Projects!'),
                        "
<p>[ID] ".ID."</p>
<p>[ErrNum] $err_num</p>
<p>[URL] $url</p>
<p>[IP] $ip</p>
<p>".lang('直到','You will not receive these emails again until')." [$cool_down_date] ".lang('之前您将不会再接收到类似的邮件(除非','(unless')." <a target='_blank' href='$restore_cool_down_url'>".lang("点击这里","Click Here")."</a>)</p>
",
                        $self->error_attention['receiver']);
                    cache()->set(self::cache_name,$time_now+3600*12);
                }
            }
        });

        return true;
    }
    /** 从路由直接访问的方法 */
    public function direct()
    {
        if(request('action')==='restore_cool_down' && check_token(request('token'))){
            cache()->set(self::cache_name,0);
            maker()->sender()->echoTag(lang('错误提示邮件发送已恢复。','The error message sent has been restored.'));
        }
    }
}