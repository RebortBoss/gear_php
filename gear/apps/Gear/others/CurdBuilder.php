<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/6
 * Time: 10:29
 */

namespace apps\Gear\others;


class CurdBuilder
{
    private $data = [];
    private $viewsFilePrefix = '';
    private $fileState=[];

    public function __construct($data)
    {
        unset($data['action']);
        array('module_name' => 'Gear', 'ctrl_name' => 'User', 'model_alias' => '用户', 'db_name' => 'local', 'tb_name' => 'tb_user', 'pk' => 'id', 'field_name_1' => 'id', 'field_alias_1' => '主键', 'field_info_1' => 'id是主键', 'field_name_2' => 'usn', 'field_alias_2' => '用户名', 'field_info_2' => 'usn是用户名', 'field_name_3' => 'psw', 'field_alias_3' => '密码', 'field_info_3' => 'psw是密码',);
        $data['fields'] = [];
        foreach ($data as $key => $value) {
            if (preg_match('/^field_name_(\d+)$/', $key, $matches)) {
                $index = $matches[1];
                $data['fields'][$data['field_name_' . $index]] = [
                    'name' => $data['field_name_' . $index],
                    'alias' => $data['field_alias_' . $index],
                    'info' => $data['field_info_' . $index]?$data['field_info_' . $index]:$data['field_alias_' . $index],
                ];
                unset($data['field_name_' . $index]);
                unset($data['field_alias_' . $index]);
                unset($data['field_info_' . $index]);
            }
        }
        $this->data = $data;
        $this->viewsFilePrefix = PATH_APPS . DS . $this->data['module_name'] . DS . 'views' . DS . $this->data['ctrl_name'];

    }

    /* protected function setData()
    {
        $this->data = array(
            'module_name' => 'Test',
            'ctrl_name' => 'User',
            'model_alias' => '用户',
            'db_name' => 'local',
            'tb_name' => 'tb_user',
            'pk' => 'id',
            'fields' => array(
                'id' => array(
                    'name' => 'id',
                    'alias' => '主键',
                    'info' => '全表的主键',
                    'verify' => ['/^\w+$/', '字母数字的组合'],
                ),
                'usn' => array(
                    'name' => 'usn',
                    'alias' => '用户名',
                    'info' => '用户名，字母数字组合',
                    'verify' => ['/^\w+$/', '字母数字的组合'],
                    'filter' => ['/abc/']
                ),
                'psw' => array(
                    'name' => 'psw',
                    'alias' => '密码',
                    'info' => '不少于5位',
                    'verify' => ['/^\d{5,10}$/', '不少于五位的数字'],
                ),
            ),
        );
    }
*/

    public function main()
    {
        $this->writeCtrl();
        $this->writeModel();
        $this->writeLists();
        $this->writeDetail();
        $this->writeCreate();
        $this->writeUpdate();
        return $this->fileState;
    }

    private function writeCtrl()
    {
        $content = "<?php

namespace apps\\" . $this->data['module_name'] . "\\ctrls;

use src\\traits\\CtrlCurd;

class " . $this->data['ctrl_name'] . " extends CtrlCurd
{
    protected function setData(){
        \$this->data=" . var_export($this->data, true) . ";
        //'verify' => ['/^\\d{5,10}$/', '不少于五位的数字'],
    }
}
        ";
        $file = PATH_APPS . DS . $this->data['module_name'] . DS . 'ctrls' . DS . $this->data['ctrl_name'] . '.php';
        if (is_file($file)){
            $this->fileState[$file]=false;
        }else{
            $this->fileState[$file]=true;
            maker()->file()->writeFile($file, $content);
        }
    }

    private function writeModel(){
        $content="<?php
namespace apps\\" . $this->data['module_name'] . "\\models;


use src\\traits\\Model;

class " . $this->data['ctrl_name'] . " extends Model
{

}";
        $file=PATH_APPS.DS.$this->data['module_name'].DS.'models'.DS.$this->data['ctrl_name'].'.php';
        if (is_file($file)){
            $this->fileState[$file]=false;
        }else{
            $this->fileState[$file]=true;
            maker()->file()->writeFile($file, $content);
        }
    }

    private function writeLists()
    {
        $ths = '';
        $tds = '';
        foreach ($this->data['fields'] as $field => $datum) {
            $ths .= "<th :title=fields.$field.name+\":\"+fields.$field.info>{{fields.$field.alias}}
        <div class=\"btn-group\" style=\"float: right;\">
            <a type=\"button\" @click=\"order='$field ASC';\"  class=\"btn btn-default btn-xs\" :class=\"{'btn-primary': order=='$field ASC' }\"><span class=\"glyphicon glyphicon-sort-by-attributes\"    ></span></a>
            <a type=\"button\" @click=\"order='$field DESC';\" class=\"btn btn-default btn-xs\" :class=\"{'btn-primary': order=='$field DESC' }\"><span class=\"glyphicon glyphicon-sort-by-attributes-alt\"></span></a>
        </div>
    </th>" . RN;
            $tds .= "    <td>{{row.data.$field}}</td>" . RN;
        }
        $content = "<gear-extend>Gear/CurdBuilder/lists</gear-extend>
<gear-block-ths>
$ths
</gear-block-ths>
<gear-block-tds>
$tds
</gear-block-tds>
        ";
        $file = $this->viewsFilePrefix . '/lists.php';
        if (is_file($file)){
            $this->fileState[$file]=false;
        }else{
            $this->fileState[$file]=true;
            maker()->file()->writeFile($file, $content);
        }
    }

    private function writeDetail()
    {
        $cols = '';
        foreach ($this->data['fields'] as $field => $datum) {
            $cols .= "                    <tr>
                        <td title=\"{$datum['name']} : {$datum['info']}\">{$datum['alias']}</td>
                        <td><?=V::displayVar(\$row['$field'])?></td>
                    </tr>" . RN;
        }
        $content = '<gear-extend>Gear/CurdBuilder/lists</gear-extend>
<gear-block-title><?=V::displayVar($model_alias)?> 详情(detail)</gear-block-title>
<gear-block-head> </gear-block-head>
<gear-block-body>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?=V::displayVar($model_alias)?> 详情(detail)</h3>
            </div>
            <div class="panel-body">
                <div class="btn-toolbar" role="toolbar" style="margin-bottom: 10px">
                    <a title="拷贝 (Copy)" type="button" href="<?=url_based(\'create\',[$pk=>$row[$pk]])?>" target="_self"
                       class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-file"></span> 拷贝 (Copy)</a>
                    <a title="修改 (Edit)" type="button" href="<?=url_based(\'update\',[$pk=>$row[$pk]])?>" target="_self"
                       class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit"></span> 修改 (Edit)</a>
                    <a title="删除 (Delete)" type="button" href="<?=url_based(\'delete\',[$pk=>$row[$pk]])?>" class="btn btn-danger btn-xs"><span
                       class="glyphicon glyphicon-trash"></span> 删除 (Delete)</a>
                    <a title="返回列表 (lists)" type="button" href="<?=url_based(\'lists\')?>" class="btn btn-info btn-xs"><span
                       class="glyphicon glyphicon-trash"></span> 返回列表 (lists)</a>
                </div>
                <table class="table table-hover table-responsive table-bordered">
                    <tbody>
                    ' . $cols . '
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">

            </div>
        </div>
    </div>
</gear-block-body>';
        $file = $this->viewsFilePrefix . '/detail.php';
        if (is_file($file)){
            $this->fileState[$file]=false;
        }else{
            $this->fileState[$file]=true;
            maker()->file()->writeFile($file, $content);
        }
    }

    private function writeCreate()
    {
        $cols = '';
        $valis = '';
        foreach ($this->data['fields'] as $field => $datum) {
            $cols .= "                    <div class=\"form-group\">
                        <label for=\"\" class=\"col-sm-2 control-label\">{$datum['alias']}</label>
                        <div class=\"col-sm-10\">
                            <input type=\"text\" class=\"form-control\" name=\"{$datum['name']}\" id=\"ipt_{$datum['name']}\"
                                   value=\"<?= V::displayVar(\$row['{$datum['name']}']) ?>\" placeholder=\"{$datum['info']}\">
                        </div>
                    </div>" . RN;
            $valis .= "                $field:<?=V::displayVar(\$fields['$field']['verify'][0],'/^.*?$/')?> ," . RN;
        }
        $content = '<gear-extend>Gear/CurdBuilder/lists</gear-extend>
<gear-block-title><?= V::displayVar($model_alias) ?> 创建(Create)</gear-block-title>
<gear-block-head>
</gear-block-head>
<gear-block-body>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= V::displayVar($model_alias) ?> 创建(Create)</h3>
            </div>
            <div class="panel-body">
                <div class="btn-toolbar" role="toolbar" style="margin-bottom: 10px">
                    <a title="返回列表 (lists)" type="button" href="<?=url_based(\'lists\')?>" class="btn btn-info btn-xs"><span
                                class="glyphicon glyphicon-trash"></span> 返回列表 (lists)</a>
                </div>
                <form action="<?= url() ?>" id="frm" method="post" class="form-horizontal" role="form">
                    ' . $cols . '
                    <?= V::formToken() ?>
                    <div class="form-group">
                    	<div class="col-sm-10 col-sm-offset-2">
                    		<button type="submit" class="btn btn-primary">提交(Submit)</button>
                    	</div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">

            </div>
        </div>
    </div>
    <script>
        Yuri2.formValidator({
            form: \'#frm\',
            fields: {' . $valis . '
            }
        });
    </script>
</gear-block-body>';

        $file = $this->viewsFilePrefix . '/create.php';
        if (is_file($file)){
            $this->fileState[$file]=false;
        }else{
            $this->fileState[$file]=true;
            maker()->file()->writeFile($file, $content);
        }
    }

    private function writeUpdate()
    {
        $cols = '';
        $valis = '';
        foreach ($this->data['fields'] as $field => $datum) {
            $cols .= "                    <div class=\"form-group\">
                        <label for=\"\" class=\"col-sm-2 control-label\">{$datum['alias']}</label>
                        <div class=\"col-sm-10\">
                            <input type=\"text\" class=\"form-control\" name=\"{$datum['name']}\" id=\"ipt_{$datum['name']}\"
                                   value=\"<?= V::displayVar(\$row['{$datum['name']}']) ?>\" placeholder=\"{$datum['info']}\"
                                   <?php if ('$field'==\$pk)echo 'readonly'?> >
                        </div>
                    </div>" . RN;
            $valis .= "                $field:<?=V::displayVar(\$fields['$field']['verify'][0],'/^.*?$/')?> ," . RN;
        }
        $content = '<gear-extend>Gear/CurdBuilder/lists</gear-extend>
<gear-block-title><?= V::displayVar($model_alias) ?> 修改(Update)</gear-block-title>
<gear-block-head>
</gear-block-head>
<gear-block-body>
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= V::displayVar($model_alias) ?> 修改(Update)</h3>
            </div>
            <div class="panel-body">
                <div class="btn-toolbar" role="toolbar" style="margin-bottom: 10px">
                    <a title="返回列表 (lists)" type="button" href="<?=url_based(\'lists\')?>" class="btn btn-info btn-xs"><span
                                class="glyphicon glyphicon-trash"></span> 返回列表 (lists)</a>
                </div>
                <form action="<?= url() ?>" id="frm" method="post" class="form-horizontal" role="form">
                    ' . $cols . '
                    <?= V::formToken() ?>
                    <div class="form-group">
                    	<div class="col-sm-10 col-sm-offset-2">
                    		<button type="submit" class="btn btn-primary">提交(Submit)</button>
                    	</div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">

            </div>
        </div>
    </div>
    <script>
        Yuri2.formValidator({
            form: \'#frm\',
            fields: {' . $valis . '
            }
        });
    </script>
</gear-block-body>';

        $file = $this->viewsFilePrefix . '/update.php';
        if (is_file($file)){
            $this->fileState[$file]=false;
        }else{
            $this->fileState[$file]=true;
            maker()->file()->writeFile($file, $content);
        }
    }


}