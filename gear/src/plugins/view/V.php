<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/28
 * Time: 11:27
 */

class V
{
    /** 辅助方法 ---------------------------------------------------- */

    /**
     * 新增 2017年6月1日
     * 作为displayVar的缩写版
     * @param $var mixed 变量
     * @param $filter string 过滤器
     * @param $default string 默认值
     * @return string
     */
    public static function val(&$var,$default='',$filter='|e'){
        $varDisplay = (!isset($var) or is_null($var) or $var==='')?$default:$var;
        return \Yuri2::smarterEcho($varDisplay,$filter,false);
    }


    /**
     * 方便地显示变量
     * @param $var mixed 变量
     * @param $filter string 过滤器
     * @param $default string 默认值
     * @return string
     */
    public static function displayVar(&$var,$default='',$filter='|e'){
        $varDisplay = (!isset($var) or is_null($var) or $var==='')?$default:$var;
        return \Yuri2::smarterEcho($varDisplay,$filter,false);
    }

    /**
     * 验证码
     * @param $width int
     * @param $height int
     * @return string
     */
    public static function captcha($width=150,$height=30){
        $url=url('/plugin/captcha',['width'=>$width,'height'=>$height,'random'=>Yuri2::randFloat()]);
        return  "<img style='max-width: {$width}px;max-height:{$height}px;width:100%;height: 100%;' src='$url' onclick='this.src=\"$url&nonce=\"+Math.random(); '/>";
    }

    /**
     * 返回合理的导入html资源字符串
     * @param $res_html string
     * @return  string
     */
    public static function import($res_html)
    {
        if ($res_html{0} != '/') {
            $res_html = '/' . $res_html;
        }
        $ext = \Yuri2::getExtension($res_html);
        $href = URL_PUBLIC . $res_html;
        $rel='';
        switch ($ext){
            case 'ico':
                $rel="<link rel = 'Shortcut Icon'  type='image/x-icon' href='$href' >";
                break;
            case 'js':
                $rel="<script type='text/javascript' src='$href' ></script>";
                break;
            case 'css':
                $rel="<link rel='stylesheet' type='text/css' href='$href' >";
                break;
        }
        $rel.=RN;
        return $rel;
    }

    /**
     * 数组转表格
     * @param $arr array 数组 数据源
     * @return string
     */
    public static function arrToHtmlTableBody($arr){
        $ths=$arr[0];
        $htmlThs='';$htmlTds='';
        if (!\Yuri2::isArrayAssoc($ths)){
            foreach ($ths as $th){
                $htmlThs.="<th>$th</th>";
            }
            $htmlThs="\n<tr>$htmlThs</tr>";
            array_shift($arr);
        }else{
            foreach ($ths as $th=>$val){
                $htmlThs.="<th>$th</th>";
            }
            $htmlThs="\n<tr>$htmlThs</tr>";
        }
        foreach ($arr as $item){
            $htmlTds.="\n<tr>";
            foreach($item as $td){
                $htmlTds.="    \n<td>$td</td>";
            }
            $htmlTds.="\n</tr>";
        }
        $htmlTable="$htmlThs $htmlTds";
        return $htmlTable;
    }

    /**
     * 把数组转换为html代码，hidden表单隐藏域
     * @param $arr array
     * @return string
     */
    public static function arrToInputHidden($arr){
        $rel='<!-- 数组转表单隐藏域 begin -->'.RN;
        foreach ($arr as $k=>$v){
            if (is_string($v) or is_numeric($v)){
                $rel.="<input type='hidden' name='$k' value='$v' />".RN;
            }elseif(is_array($v)){
                foreach ($v as $vv){
                    $rel.="<input type='checkbox' name='{$k}[]' value='$vv' style='display: none' />.RN";
                }
            }
        }
        $rel.='<!-- 数组转表单隐藏域 end -->'.RN;
        return $rel;
    }

    /**
     * 更聪明的echo
     * @param $var mixed
     * @param $filters string 过滤器
     * @return string
     */
    public static function smarterEcho($var,$filters='|e'){
        return \Yuri2::smarterEcho($var,$filters,false);
    }

    /**
     * 生成并输出一个表单令牌
     * @return string
     */
    public static function formToken(){
        $event=new \src\cores\Event(['rel'=>'']);
        \src\cores\Event::fire(\src\traits\Ctrl::EVENT_ON_GET_FORM_TOKEN,$event);
        return $event['rel'];
    }

    /** 图片链接接口
     * @param $picFileOriginal string
     * @param $params  array
     * @return string
     */
    public static function picMagic( $picFileOriginal, $params){
        $pic=maker()->picMagic( $picFileOriginal, $params);
        $cache_key='plugin_picMagic_'.$picFileOriginal.'_'.serialize($params);
        if(cache()->has($cache_key)){
            $url=cache()->get($cache_key);
        }else{
            $url= $pic->getMagicUrl();
            cache()->set($cache_key,$url,259200);
        }
        return $url;
    }

}