<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/28
 * Time: 11:27
 */

class V
{
    //常用html资源导入
    public static function import_js_jquery(){return self::import('common/js/'.(Yuri2::isOldIE()?'jquery-1.11.3.min.js':'jquery-2.2.4.min.js'));}
    public static function import_js_jquery_JSONP(){return self::import('common/js/jquery.jsonp.js');}
    public static function import_js_jquery_mouse_wheel(){return self::import('common/js/jqueryMousewheel.js');}
    public static function import_js_yuri2(){return self::import('common/js/Yuri2.js');}
    public static function import_js_lock(){return self::import('common/js/lock_IndreamLuo.js');}
    public static function import_js_thread(){return self::import('common/js/Concurrent.Thread.js');}
    public static function import_js_gravity_shadow(){return self::import('common/js/gravityShadow.js');}
    public static function import_js_vue(){return self::import('common/js/vue.min.js');}
    public static function import_js_marked(){return self::import('common/js/marked.js');}
    public static function import_css_animated(){return self::import('common/css/animate.css');}
    public static function import_css_grid(){return self::import('common/css/grid.css');}
    public static function import_css_parse_down(){return self::import('common/css/parseDown.css');}
    public static function import_css_font_awesome(){return self::import('common/font-awesome-4.7.0/css/font-awesome.min.css');}
    public static function import_rs_bootstrap3(){
        return self::import('common/bootstrap3/js/bootstrap.min.js')
             . self::import('common/bootstrap3/css/bootstrap.min.css');
    }
    public static function import_rs_element_ui($v='1.4.2'){
        switch ($v){
            case '1.4.2':
                return self::import("common/element-ui-v1.4.2/lib/theme-default/color.css")
                    . self::import("common/element-ui-v1.4.2/lib/theme-default/index.css")
                    . self::import("common/element-ui-v1.4.2/lib/index.js");
            case '2.0.8':
                return self::import("common/element-ui-v2.0.8/index.css")
                    . self::import("common/element-ui-v2.0.8/index.js");
        }
    }
    public static function import_rs_jq_ui(){
        return self::import('common/jquery-ui-1.12.1/jquery-ui.theme.css')
             . self::import('common/jquery-ui-1.12.1/jquery-ui.min.css')
             . self::import('common/jquery-ui-1.12.1/jquery-ui.js');
    }
    public static function import_rs_ie9_fix(){
        return self::import('common/js/html5shiv-3.7.2-html5shiv.min.js')
             . self::import('common/js/respond.js-1.4.2-respond.min.js');
    }
    public static function import_rs_context_menu(){
        return self::import('common/contextMenu/src/contextMenu.js')
             . self::import('common/contextMenu/src/contextMenu.css');
    }
    public static function import_rs_code_mirror(){
        return self::import('common/codemirror-5.2/lib/codemirror.css')
             . self::import('common/codemirror-5.2/lib/codemirror.js')
             . self::import('common/codemirror-5.2/addon/edit/matchbrackets.js')
             . self::import('common/codemirror-5.2/mode/htmlmixed/htmlmixed.js')
             . self::import('common/codemirror-5.2/mode/xml/xml.js')
             . self::import('common/codemirror-5.2/mode/javascript/javascript.js')
             . self::import('common/codemirror-5.2/mode/css/css.js')
             . self::import('common/codemirror-5.2/mode/clike/clike.js')
             . self::import('common/codemirror-5.2/mode/php/php.js');
    }

    /**
     *      new SimpleMDE({
     *          element:$('#t1')[0],
     *          autoDownloadFontAwesome:false,
     *      })
     */
    public static function import_rs_simpleMDE(){
        return self::import('common/simplemde-markdown-editor/dist/simplemde.min.css')
             . self::import('common/simplemde-markdown-editor/dist/simplemde.min.js');
    }
    public static function import_rs_layer(){return self::import('common/layer-v3.0.3/layer/layer.js');}


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
     * @deprecated use val() instead
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
        if(!preg_match('/^https?:\/\//i',$res_html)){
            if ($res_html{0} != '/') {
                $res_html = '/' . $res_html;
            }
            $res_html = URL_PUBLIC . $res_html;
        }
        $ext = \Yuri2::getExtension($res_html);
        $rel='';
        switch ($ext){
            case 'ico':
                $rel="<link rel = 'Shortcut Icon'  type='image/x-icon' href='$res_html' >";
                break;
            case 'js':
                $rel="<script type='text/javascript' src='$res_html' ></script>";
                break;
            case 'css':
                $rel="<link rel='stylesheet' type='text/css' href='$res_html' >";
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
     * @deprecated use val() instead
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
        \src\cores\Event::trigger(\src\traits\Ctrl::EVENT_ON_GET_FORM_TOKEN,$event);
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