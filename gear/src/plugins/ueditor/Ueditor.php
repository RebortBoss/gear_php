<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\ueditor;


class Ueditor
{
    public static $TOOL_BAR_FULL = [
        [

        'fullscreen', //全屏


        //全局操作
        'undo', //撤销
        'redo', //重做
        'formatmatch', //格式刷
        'source', //源代码
        'pasteplain', //纯文本粘贴模式
        'selectall', //全选
        'print', //打印
        'preview', //预览
        'removeformat', //清除格式
        'cleardoc', //清空文档
        'searchreplace', //查询替换
        'drafts', // 从草稿箱加载
        'help', //帮助
        '|',

        //文字操作
        'bold', //加粗
        'italic', //斜体
        'underline', //下划线
        'strikethrough', //删除线
        'subscript', //下标
        'fontborder', //字符边框
        'superscript', //上标
        'blockquote', //引用
        'fontfamily', //字体
        'fontsize', //字号
        'touppercase', //字母大写
        'tolowercase', //字母小写
        'forecolor', //字体颜色
        'backcolor', //背景色
        '|',

        //排版
        'indent', //首行缩进
        'insertcode', //代码语言
        'paragraph', //段落格式
        'justifyleft', //居左对齐
        'justifyright', //居右对齐
        'justifycenter', //居中对齐
        'justifyjustify', //两端对齐
        'directionalityltr', //从左向右输入
        'directionalityrtl', //从右向左输入
        'rowspacingtop', //段前距
        'rowspacingbottom', //段后距
        'imagenone', //默认
        'imageleft', //左浮动
        'imageright', //右浮动
        'imagecenter', //居中
        'lineheight', //行间距
        'edittip ', //编辑提示
        'customstyle', //自定义标题
        'autotypeset', //自动排版
        'background', //背景
        'template', //模板
        '|',
    ],
        [
            //插入标记
            'anchor', //锚点
            'horizontal', //分隔线
            'time', //时间
            'date', //日期
            'insertorderedlist', //有序列表
            'insertunorderedlist', //无序列表
            'pagebreak', //分页
            'insertframe', //插入Iframe
            '|',

            //插入素材
            'emotion', //表情
            'spechars', //特殊字符
            'snapscreen', //截图
            'link', //超链接
            'unlink', //取消链接
            'simpleupload', //单图上传
            'insertimage', //多图上传
            'map', //Baidu地图
            'gmap', //Google地图
            'insertvideo', //视频
            'wordimage', //图片转存
            'attachment', //附件
            'scrawl', //涂鸦
            'music', //音乐
            'webapp', //百度应用
            'charts', // 图表
            '|',


            //表格
            'inserttable', //插入表格
            'edittable', //表格属性
            'edittd', //单元格属性
            'insertparagraphbeforetable', //"表格前插入行"
            'insertrow', //前插入行
            'insertcol', //前插入列
            'mergeright', //右合并单元格
            'mergedown', //下合并单元格
            'deleterow', //删除行
            'deletecol', //删除列
            'splittorows', //拆分成行
            'splittocols', //拆分成列
            'splittocells', //完全拆分单元格
            'deletecaption', //删除表格标题
            'inserttitle', //插入标题
            'mergecells', //合并多个单元格
            'deletetable', //删除表格


        ]
    ];
    public static $TOOL_BAR_NORMAL = [
        [
            //全局操作
            'undo', //撤销
            'redo', //重做
            'formatmatch', //格式刷
            'source', //源代码
            'searchreplace', //查询替换
            '|',

            //文字操作
            'bold', //加粗
            'underline', //下划线
            'strikethrough', //删除线
            'fontfamily', //字体
            'fontsize', //字号
            'touppercase', //字母大写
            'tolowercase', //字母小写
            'forecolor', //字体颜色
            'backcolor', //背景色
            '|',

            //排版
            'indent', //首行缩进
            'insertcode', //代码语言
            'paragraph', //段落格式
            'justifyleft', //居左对齐
            'justifyright', //居右对齐
            'justifycenter', //居中对齐
            'justifyjustify', //两端对齐
            'imagecenter', //居中
            'lineheight', //行间距
            'edittip ', //编辑提示
            'customstyle', //自定义标题
            'background', //背景
            '|',


            //插入标记
            'horizontal', //分隔线
            'insertorderedlist', //有序列表
            'insertunorderedlist', //无序列表
            'pagebreak', //分页
            'emotion', //表情
            'spechars', //特殊字符
            'snapscreen', //截图
            'link', //超链接
            'unlink', //取消链接
            'simpleupload', //单图上传
            'insertimage', //多图上传
            'map', //Baidu地图
            'attachment', //附件
            'inserttable', //插入表格
            'edittable', //表格属性
        ]
    ];
    public static $TOOL_BAR_LITE = [
        [
            //文字操作
            'bold', //加粗

            //插入标记
            'horizontal', //分隔线
            'insertunorderedlist', //无序列表
            'link', //超链接

            //插入素材
            'emotion', //表情



        ]
    ];

    public function getImportHtml()
    {
        $prefix = URL_PUBLIC . '/plugin/ueditor';
        return "
<script type=\"text/javascript\" src='$prefix/ueditor.config.js'></script>
<script type=\"text/javascript\" src='$prefix/ueditor.all.min.js'></script>
<script type=\"text/javascript\" src='$prefix/ueditor.parse.min.js'></script>
        ";
    }

    /**
     * @param $configs array
     * @return string
     */
    public function getInitHtml($configs)
    {
        $params=[
            'id'=>'ueditor',
            'name'=>'ueditor',
            'content'=>'',
            'toolbars'=>'normal',
            'width'=>'100%',
            'height'=>'160',
        ];
        $params=array_merge($params,$configs);

        switch ($toolbars=$params['toolbars']) {
            case 'full':
                $toolbars = self::$TOOL_BAR_FULL;
                break;
            case 'normal':
                $toolbars = self::$TOOL_BAR_NORMAL;
                break;
            case 'lite':
                $toolbars = self::$TOOL_BAR_LITE;
                break;
            default:
                $toolbars = self::$TOOL_BAR_NORMAL;
                break;
        }
        $config = [
            'toolbars'=>$toolbars,
            'serverUrl'=>url('plugin/ueditor', ['id' => $params['id'],'token'=>order_token(3600*6,'ue')]),
            'initialFrameWidth'=>$params['width'],
            'initialFrameHeight'=>$params['height'],
        ];
        $config_json = maker()->format()->arrayToJson($config);
        return "
    <!-- 加载编辑器的容器 -->
    <script id=\"{$params['id']}\" name=\"{$params['name']}\" class='ueditor' type=\"text/plain\">{$params['content']}</script>
    <!-- 实例化编辑器 -->
    <script type=\"text/javascript\">
       UE.getEditor('{$params['id']}',$config_json);
    </script>
        ";
    }

    public function getParseHtml($cssSelector='.ueditor'){
        $rpt=URL_PUBLIC.'/plugin/ueditor/';
        return "
            <script type=\"text/javascript\">
                uParse('$cssSelector', {
                    rootPath: '$rpt'
                })
            </script>
        ";
    }
}