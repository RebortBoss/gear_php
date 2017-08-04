<?php

namespace apps\Gear\ctrls;


use src\plugins\arrayDb\ArrayDb;
use src\traits\Ctrl;

class Service extends Ctrl
{
    const INTERVAL=5;

    /** @var  ArrayDb $db */
    private $db;

    public function init()
    {
        $this->db = maker()->arrayDb('gear/service');
        if (!$this->db->data) {
            //初始化数据
            $this->db->data = [
                'enable' => false,
                'list' => [
                    'running_attention' => [
                        'target' => '/apps/Gear/others/service_running_attention.php',
                        'enable' => true,
                    ]
                ]
            ];
        }
    }

    public function index()
    {
        $this->assign('data', $this->db->data);
        $this->render();
    }

    private function start(){
        $this->db->data['enable'] = true;
        $this->db->save();
        unset($this->db);
        maker()->logger()->info(lang('Gear常驻服务开启','Gear service is enable.'));
        //无限循环
        ignore_user_abort(true);
        set_time_limit(0);
        session_abort();
        $this->mainService();
    }

    private function stop(){
        $this->db->data['enable'] = false;
        $this->db->save();
        unset($this->db);
        maker()->logger()->info(lang('Gear常驻服务关闭','Gear service has closed.'));
    }

    public function startStop()
    {
        $this->apiMode();
        if ($this->db->data['enable']) {
            $this->stop();
        } else {
            $this->start();
        }

    }

    public function startScript(){
        $name=request('name');
        $this->apiMode();
        $this->db->data['list'][$name]['enable']=true;
        maker()->logger()->info(lang("脚本 '$name' 已启用。","The script '$name' is enabled."));
    }

    public function stopScript(){
        $name=request('name');
        $this->apiMode();
        $this->db->data['list'][$name]['enable']=false;
        maker()->logger()->info(lang("脚本 '$name' 已禁用。","The script '$name' is disabled."));
    }

    public function delScript(){
        $name=request('name');
        $this->stopScript();
        unset($this->db->data['list'][$name]);
        maker()->logger()->info(lang("脚本 '$name' 已被从列表中删除。","The script '$name' has been deleted from list."));
    }

    public function addScript(){
        $this->apiMode();
        $name=request('name');
        $target=request('target');
        $full_path=PATH_GEAR.$target;
        if (is_file($full_path) and ((maker()->file()->getExtension($full_path))=='php')){
            $this->db->data['list'][$name]=[
                'target' => $target,
                'enable' => false,
            ];
            return [
                'state'=>'success',
                'msg'=>lang('已完成','Done')
            ];
        }elseif(preg_match('/^https?:/',$target)){
            $this->db->data['list'][$name]=[
                'target' => $target,
                'enable' => false,
            ];
            return [
                'state'=>'success',
                'msg'=>lang('已完成','Done')
            ];

        }else{
            return [
                'state'=>'error',
                'msg'=>lang('非法的文件或URL','Illegal file path or URL')
            ];
        }
    }

    private function mainService(){
        while (true){
            $db=maker()->arrayDb('gear/service');
            $data=&$db->data;

            if (!$data['enable']){
                break;
            }
            try{
                foreach ($data['list'] as $name=>$item){
                    if ($item['enable']){
                        if (preg_match('/^https?:/', $item['target'])){
                              maker()->sender()->curlGet($item['target'],1);
                        }else{
                            $rel=include PATH_GEAR.$item['target'];
                            if ($rel===false){
                                $data[$name]['enable']=false;
                            }
                        }
                    }
                }
            }catch (\Exception $e){
                maker()->logger()->emerg(lang(' [gear常驻服务运行时发生异常] ',' [gear service runtime error] ').$e->getMessage());
            }

            $db->save();
            unset($db);
            unset($data);
            sleep(5);
        }
    }

    public function __destruct()
    {
        if (isset($this->db)) {
            $this->db->save();
        }
    }

}