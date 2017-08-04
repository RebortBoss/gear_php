<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/5
 * Time: 10:44
 */

namespace apps\Gear\ctrls;

use src\plugins\arrayDb\ArrayDb;
use src\traits\Ctrl;

class Favorites extends Ctrl
{
    private $favs=[];
    /** @var  ArrayDb */
    private $db;

    public function init()
    {
        $db=maker()->arrayDb('gear/favorites');
        $this->db=$db;
        $this->favs=$db->data;
        $this->assign('favs',$this->favs);
    }

    public function index(){
        $this->render();
    }

    /**
     * 添加一项收藏夹
     * @method post
     */
    public function addFav(){
        $href=request('href');
        $name=request('name');
        $id=\Yuri2::uniqueID();
        $this->db->data[$id]=['href'=>$href,'name'=>$name];
        $this->db->save();
        maker()->sender()->redirect(url_based('index'));
    }

    /**
     * 删除一项收藏夹
     * @method post
     */
    public function delFav(){
        if ($id=request('id')){
            unset($this->db->data[$id]);
            $this->db->save();
        }
        maker()->sender()->redirect(url_based('index'));
    }

    /**
     * 读取标题
     */
    public function getTitle(){
        $this->apiMode();
        $url=request('url');
        $data=[
            'msg'=>'success',
            'errno'=>0,
            'data'=>'',
        ];
        try{
            $html=maker()->sender()->curlGet($url);
            preg_match("/<title>([\\s\\S]+)<\\/title>/i",$html, $matches);
            $title=isset($matches[1])?maker()->format()->autoEncoding($matches[1]):'';
            $data['data']= $title;
            return $data;

        }catch (\Exception $e){
            $data=[
                'msg'=>$e->getMessage(),
                'errno'=>1,
                'data'=>'',
            ];
            return $data;
        }

    }
}