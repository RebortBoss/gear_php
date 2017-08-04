<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\picMagic;


class PicMagic
{
    public static $configs=[];
    public static $cacheDir='';

    private $picFileOriginal=''; //图片的原始地址
    private $picFileCache=''; //处理后图片的缓存地址
    private $picUrlCache=''; //处理后图片的url
    private $width=720;
    private $height=480;
    private $extension='';

    public static function setConfigs($configs){
        self::$configs=$configs;
        self::$cacheDir=$configs['cacheDir'];
    }

    /**
     * @param $pic string 原始的路径 不带PUBLIC前缀
     * @param $params array 参数数组
     */
    public function __construct($pic,$params)
    {
        $this->width=issetOrDefault($params['width'], 720);
        $this->height=issetOrDefault($params['height'], 480);
        $this->extension=maker()->file()->getExtension($pic);

        $this->picFileOriginal=PATH_PUBLIC.DS.$pic; //图片原始地址
        $this->picFileCache=self::$cacheDir.DS.md5(
            serialize(
                [
                    $pic,
                    $this->width,
                    $this->height,
                ]
                )
            ).'.'.$this->extension;
        $this->picUrlCache=url('plugin/picMagic',[
            'pic'=>$pic,
            'width'=>$this->width,
            'height'=>$this->height,
        ]);
    }

    /** 按参数处理图片 */
    private function process(){
        if (!is_file($this->picFileOriginal)){return false;}
        $img=maker()->image($this->picFileOriginal);
        $img->thumb($this->picFileCache,$this->width,$this->height);
    }

    /** 缓存是否存在 */
    public function isCacheExist(){
        return is_file($this->picFileCache);
    }

    /**
     * 获取链接
     * @param $expire int 默认3天
     * @return string
     */
    public function getMagicUrl($expire=259200){
        $token=order_token($expire);
        $this->picUrlCache=url($this->picUrlCache,['token'=>$token]);
        return $this->picUrlCache;
    }

    public function getMagicFile(){
        return $this->picFileCache;
    }

    /** 显示处理后的图 */
    public function display(){
        if (!$this->isCacheExist()){
            $this->process();
        }
        if (is_file($this->picFileCache)){
            header('Content-type: image/'.$this->extension);
            $content=file_get_contents($this->picFileCache);
            maker()->sender()->headerSendCachePage();
            echo $content;
        }
    }
}