<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\email;


class Email
{

    private $host = '';
    private $port = 0;
    private $ssl = false;
    private $username = '';
    private $password = '';
    private $nickname = '';

    private $mailer=null;

    public function __construct($config)
    {
        foreach ($config as $k=>$v){
            $this->$k=$v;
        }
        $transport =\Swift_SmtpTransport::newInstance($this->host, $this->port,$this->ssl?'ssl':null)
            ->setUsername($this->username)
            ->setPassword($this->password);
        $this->mailer =\Swift_Mailer::newInstance($transport);
    }

    /**
     * @param $subject string Title 标题
     * @param $body string Main content 内容
     * @param $receiver string|array 接收人（字符串或字符串数组）
     * @param $withSysInfo bool 是否自动排版
     * @return $this
     */
    public function send($subject,$body,$receiver,$withSysInfo=true){
        if (is_string($receiver)){$receiver=[$receiver];}
        if($withSysInfo){
            $body=<<<EOT
            $body
            <div style="
            position: absolute;
            font-size: 10px;
            color: grey;
            margin-top: 30px;
            ">
                (该邮件由
                <a style="text-decoration: none" target="_blank" href="https://github.com/yuri2peter/gear_php">GearPHP</a>
                系统发送,请勿直接回复)
            </div>
EOT;

        }
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)//创建邮件信息的主题，即发送标题
            ->setFrom(array($this->username =>$this->nickname))//谁发送的   设置发送人及昵称
            ->setTo($receiver)//发给谁        设置接收邮件人的列表
            ->setBody($body, 'text/html','utf-8');//邮件发送的内容
        $this->mailer->send($message);
        return $this;
    }

}