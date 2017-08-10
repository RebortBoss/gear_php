<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/10
 * Time: 8:57
 */

namespace src\traits;


class CtrlCurd extends Ctrl
{

    /** @var  Model $model */
    protected $model;
    protected $pk;
    protected $data=[];
    protected $link_target='_blank';
    protected $min_width='1080px';

    protected function setData(){
        $this->data=null;
    }

    public function init()
    {
        $this->setData();
        if (is_null($this->data)){
            maker()->sender()->error('Data not set.');
        }
        $model_class='\apps\\'.$this->data['module_name'].'\\models\\'.$this->data['ctrl_name'];
        $this->model=new $model_class($this->data);
        $this->assign('model_alias',$this->data['model_alias']);
        $this->assign('pk',$this->data['pk']);
        $this->assign('fields',$this->data['fields']);
        $this->pk=$this->data['pk'];
    }

    public function index()
    {
        maker()->sender()->redirect(url_based('lists'));
    }

    public function lists()
    {
        $this->assign('link_target',$this->link_target);
        $this->assign('min_width',$this->min_width);
        $this->render();
    }

    public function getLists(){
        $this->apiMode();
        $page_index=request('page_index',1);
        $page_rows=request('page_rows',10);
        $con_col=request('con_col','');
        $con_op=request('con_op','');
        $con_val=request('con_val','');
        $order=request('order',"{$this->pk} DESC");
        $where=$this->conditionBuilder($con_col,$con_op,$con_val);
        $count=$this->model->getCount($where);
        $page_num=ceil($count/$page_rows);
        if ($page_index>$page_num){$page_index=$page_num;}
        if ($page_index<0){$page_index=1;}
        $rel=$this->model->getRows(compact('page_index','page_rows','where','order'));

        if (is_array($rel)){
            $rows=[];
            foreach ($rel as $k=>$v){
                $rows[]=[
                    $this->data['pk']=>$k,
                    'url'=>[
                        'detail'=>url_based('detail',[$this->data['pk']=>$k]),
                        'copy'=>url_based('create',[$this->data['pk']=>$k]),
                        'edit'=>url_based('update',[$this->data['pk']=>$k]),
                    ],
                    'data'=>$v,
                ];
            }
        }else{
            $rows=[];
        }
        $pagination=[];
        for ($i=1;$i<=$page_num;$i++){
            $pagination[$i]=[
                'isActive'=>$page_index==$i,
            ];
        }
        return [
            'fields'=>$this->data['fields'],
            'rows'=>$rows,
            'waiting'=>false,
            'pagination'=>$pagination,
            'count'=>$count,
            'page_index'=>$page_index,
            'page_rows'=>$page_rows,
            'con_col'=>$con_col,
            'con_op'=>$con_op,
            'con_val'=>$con_val,
            'order'=>$order,
        ];
    }

    public function conditionBuilder($col,$op,$val){
//        $col="`{$col}`";
        $query=null;
        $param=[];
        switch ($op){
            case '~=':
                $query=$col.' LIKE ?';
                $param=['%'.$val.'%'];
                break;
            case '=':
                $query=$col.'=?';
                $param=[$val];
                break;
            case '>':
                $query=$col.'>?';
                $param=[$val];
                break;
            case '<':
                $query=$col.'<?';
                $param=[$val];
                break;
            case '>=':
                $query=$col.'>=?';
                $param=[$val];
                break;
            case '<=':
                $query=$col.'<=?';
                $param=[$val];
                break;
            case '~~':
                $query=$col.' BETWEEN ? AND ?';
                $param=\Yuri2::explodeWithoutNull('~~',$val);
                break;
            case '<>':
                $query=$col.'<> ?';
                $param=[$val];
                break;

        }
        return is_null($query)?null:[$query,$param];
    }

    public function import()
    {
        if (!$this->checkFormToken()){maker()->sender()->warning('请勿重复提交。Please do not submit duplicate');}
        $up=maker()->uploader();
        $up->set('allowtype',['xls','xlsx']);
        if($up -> upload("excel")) {
            $filename=$up->getFileFullPath();
            $excelHelper=maker()->excel();
            $cells=$excelHelper->loadFromFile($filename)->ObjToArray();
//            $cells=$excelHelper->loadFromFile(maker()->format()->autoEncoding($filename))->ObjToArray();
            $rows=[];
            foreach ($cells as $cell){
                $row=[];
                foreach ($this->data['fields'] as $field=>$infos){
                    $row[$field]=array_shift($cell);
                }
                $rows[]=$row;
            }
            maker()->file()->deleteFile($filename);
            array_shift($rows);
            $this->model->saveMany($rows);
            maker()->sender()->success('导入完成(import success)','back',2);
        }else{
            maker()->sender()->error('导入失败(import failed)','back',3);
        }
    }

    public function export()
    {
        $this->apiMode();
        $rows=$this->getLists()['rows'];
        $excelHelper=maker()->excel();
        $excelObj=$excelHelper->getExcelObj();
        $sheet=$excelObj->setActiveSheetIndex(0);
        $index=0;
        foreach ($this->data['fields'] as $field=>$infos){
            $index++;
            $sheet->setCellValue($excelHelper->coordinateToCell($index,1),$infos['alias']);
        }
        $y=1;
        foreach ($rows as $id=>$row){
            $x=0;
            $y++;
            foreach ($row['data'] as $col){
                $x++;
                $sheet->setCellValue($excelHelper->coordinateToCell($x,$y),$col);
            }
        }
        $excelHelper->downloadFile($this->data['model_alias']);
        exit();
    }

    public function delete()
    {
        $this->apiMode();
        if (request('id')){
            $this->model->deleteOne(request('id'));
            if (maker()->request()->isAjax()){
                return [
                    'status'=>'success'
                ];
            }else{
                maker()->sender()->success('删除完成。Delete completed.',url_based('lists'),2);
            }

        }elseif(request('ids')){
            $ids=request('ids');
            $this->model->deleteMany($ids);
            if (maker()->request()->isAjax()){
                return [
                    'status'=>'success'
                ];
            }else{
                maker()->sender()->success('删除完成。Delete completed.','back',2);
            }
        }
    }

    public function update()
    {
        if (maker()->request()->isPost()){
            if (!$this->checkFormToken()){maker()->sender()->warning('请勿重复提交。Please do not submit duplicate','back',3);}
            $rel=$this->model->update(request());
            if ($rel){
                maker()->sender()->success('提交成功。Update Complete.',url_based('detail',[$this->data['pk']=>request($this->data['pk'])]),2);
            }else{
                maker()->sender()->error('提交失败，请检查输入。Update failed','back',2);
            }
        }else{
            $row=$this->model->getRow(request($this->data['pk']));
            if (!$row){maker()->sender()->error('找不到这条纪录。Can not find the data.','back');exit();}
            $this->assign('row',$row);
            $this->render();
        }
    }

    public function create()
    {
        if (maker()->request()->isPost()){
            if (!$this->checkFormToken()){maker()->sender()->warning('请勿重复提交。Please do not submit duplicate','back',3);}
            $rel=$this->model->insert(request());
            if ($rel){
                maker()->sender()->success('提交成功。Insert Complete.',url_based('detail',[$this->data['pk']=>$rel[$this->data['pk']]]),2);
            }else{
                maker()->sender()->error('提交失败，请检查输入。Insert failed','back',2);
            }
        }else{
            $row=$this->model->getRow(request($this->data['pk']));
            $this->assign('row',$row);
            $this->render();
        }
    }

    public function detail()
    {
        $row=$this->model->getRow(request($this->data['pk']));
        if (!$row){
            maker()->sender()->error('找不到这条纪录。Can not find the data.','back');
        }
        $this->assign('row',$row);
        $this->render();
    }


}