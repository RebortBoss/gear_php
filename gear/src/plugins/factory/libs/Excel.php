<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/22
 * Time: 9:37
 */

namespace src\plugins\factory\libs;


/**
 * PHPExcel的辅助类
 * 傻瓜化使用常用功能
 */
class Excel
{
    private $excel=false;//excel对象存储
    private $writer=false;//writer对象存储

    /**
     * phpExcelWriter对象
     * @param  $objExcel \PHPExcel
     * @param $writerType string
     * @return	\PHPExcel_Writer_IWriter
     */
    public static function getPHPExcelWriter($objExcel,$writerType = 'Excel2007'){
        $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, $writerType);
        return $objWriter;
    }

    /**
     * 获得excel对象，如果为空，生成一个空对象
     * @return \PHPExcel
     */
    public function getExcelObj(){
        if (!$this->excel){
            $this->excel=new \PHPExcel();
        }
        return $this->excel;
    }

    /**
     * 设置excel对象，覆盖原有的
     * @param $excelObj \PHPExcel
     * @return $this
     */
    public function setExcelObj($excelObj){
        $this->excel=$excelObj;
        return $this;
    }

    /**
     * 从本地excel文件生成对象
     * @param $fromFile string
     * @return $this
     */
    public function loadFromFile($fromFile){
        $this->excel= \PHPExcel_IOFactory::load($fromFile);
        return $this;
    }

    /**
     * excel转换为数组
     * @return array
     */
    public function ObjToArray(){
        $objPHPExcel = $this->getExcelObj();
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }

    /**
     * 获得writer对象，如果为空，生成一个空对象
     * @return \PHPExcel_Writer_IWriter
     */
    public function getExcelWriter(){
        if (!$this->writer){
            $this->writer=\PHPExcel_IOFactory::createWriter($this->getExcelObj(), 'Excel2007');
        }
        return $this->writer;
    }

    /**
     * 设置excelWriter对象，覆盖原有的
     * @param $excelWriterObj \PHPExcel_Writer_IWriter
     * @return $this
     */
    public function setExcelWriterObj($excelWriterObj){
        $this->writer=$excelWriterObj;
        return $this;
    }

    /**
     * 保存为excel文件，默认2007格式
     * @param $fileFullPath string
     * @return string filePathReal
     */
    public function saveFile($fileFullPath){
        $ext=\Yuri2::getExtension($fileFullPath);
        if ($ext==''){
            $fileFullPath.='.xlsx';
        }
        $this->getExcelWriter()->save($fileFullPath);
        return $fileFullPath;
    }

    /**
     * 直接下载为excel文件
     * @param $filename string 文件名
     * @return $this
     */
    public function downloadFile($filename='export.xlsx'){
        $ext=\Yuri2::getExtension($filename);
        if ($ext==''){
            $filename.='.xlsx';
        }
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = $this->getExcelWriter();
        $objWriter->save('php://output');
//        config(Config::API_MODE,true);//避免其他html内容污染
        return $this;
    }

    /** 坐标转单元格 */
    public function coordinateToCell($x,$y){
        $x--; //从0开始
        $rel='';
        $xx=intval($x/26);
        if ($xx){
            //此处修正列数过多的情况，转为AA
            $xx=chr(ord('A')+$xx-1);
            $rel.=$xx;
        }

        $x=$x%26;
        $rel.=chr(ord('A')+$x);
        return $rel.$y;
    }

}