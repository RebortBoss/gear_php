<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\qrCode;


class QrCode
{
    private $qr_content='';
    private $water='';
    private $size=3;
    private $margin=1;
    private $is_cache=false;

    /**
     * @param string $qr_content
     * @return $this
     */
    public function setQrContent($qr_content)
    {
        $this->qr_content = $qr_content;
        return $this;
    }

    /**
     * @param string $water
     * @return $this
     */
    public function setWater($water)
    {
        $this->water = $water;
        return $this;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @param int $margin
     * @return $this
     */
    public function setMargin($margin)
    {
        $this->margin = $margin;
        return $this;
    }

    /**
     * @param bool $is_cache
     * @return $this
     */
    public function setIsCache($is_cache)
    {
        $this->is_cache = $is_cache;
        return $this;
    }

    /**
     * 显示图片
     * @return string
     */
    public function getCodeFile(){
        $qr_content=$this->qr_content;
        $water=$this->water;
        $size=$this->size;
        $margin=$this->margin;
        $pag=serialize(compact('qr_content','water','size','margin'));
        $fileName=md5($pag).'.png';
        $true_dir_path=PATH_RUNTIME.'/qrCode_cache';
        $full_path=$true_dir_path.'/'.$fileName;
        if (!is_file($full_path)){
            \Yuri2::createDir($true_dir_path);
            \QRcode::png($qr_content,$full_path,QR_ECLEVEL_M,$size,$margin);
            $waterPng=__DIR__."/waters/$water.png";
            if (is_file($waterPng)){
                $image=maker()->image($full_path);
                $info=$image->getImageInfo();
                $w = round($info[0]/5); //生成的水印宽度
                $objWater=maker()->image($waterPng);
                $newWaterPath=$true_dir_path.'/'.uniqid('qrcode_water').'png';
                $objWater->thumb($newWaterPath,$w);
                $image->water($full_path,$newWaterPath,5,100);
                unlink($newWaterPath);
            }
        }
        return $full_path;
    }

    public function getUrl($expire=3153600000){
        $urlQr=url('plugin/qrCode',[
            'content'=>$this->qr_content,
            'water'=>$this->water,
            'size'=>$this->size,
            'margin'=>$this->margin,
            'is_cache'=>$this->margin?'yes':'no',
            'token'=>order_token($expire),
        ]);
        return $urlQr;
    }
}