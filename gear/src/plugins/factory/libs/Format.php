<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 22:18
 */

namespace src\plugins\factory\libs;

use Michelf\Markdown;

class Format
{
    /**
     * 正则匹配删除指定后缀
     * @param $str string 目标字符串
     * @param $suffix string 要删除的后缀
     * @return string 结果
     * @author yuri2
     */
    public static function removeSuffix($str, $suffix)
    {
        return \Yuri2::removeSuffix($str, $suffix);
    }

    /**
     * XML转数组
     * @param $xml string xml
     * @return array
     * @author yuri2
     */
    public function xmlToArray($xml)
    {
        return \Yuri2::xmlToArray($xml);
    }

    /**
     * 数组转XML
     * @param $arr
     * @return string
     * @author yuri2
     */
    public function arrayToXml($arr)
    {
        return \Yuri2::arrayToXml($arr);
    }

    /**
     * json转数组
     * @param $json
     * @return array
     * @author yuri2
     */
    public function jsonToArray($json)
    {
        return json_decode($json, true);
    }

    /**
     * 数组转json
     * @param $arr array
     * @param $options int
     * @return string
     * @author yuri2
     */
    public function arrayToJson($arr, $options = 0)
    {
        return \json_encode($arr, $options);
    }

    /**
     * 驼峰法转下划线法
     *
     * @param  string $CamelCase
     * @return string
     */
    public function camelToUnderScore($CamelCase)
    {
        return \Yuri2::Camel_to_UnderScore($CamelCase);
    }

    /**
     * 驼峰法转连字符法
     * @param  string $CamelCase
     * @return string
     */
    public function camelToHyphen($CamelCase)
    {
        return \Yuri2::Camel_to_Hyphen($CamelCase);
    }

    /**
     * 下划线法转驼峰法
     * @param $str string
     * @param $uc_first bool 是否大写开头
     * @return string
     */
    public function underScoreToCamel($str, $uc_first = true)
    {
        return \Yuri2::UnderScore_to_Camel($str, $uc_first);
    }

    /**
     * 下划线法转连字符法
     * @param $str string
     * @param $uc_first bool 是否大写开头
     * @return string
     */
    public function hyphenToCamel($str, $uc_first = true)
    {
        return \Yuri2::Hyphen_to_Camel($str, $uc_first);
    }

    /**
     * 字符串为正则做转义
     * @param $str string
     * @return string
     */
    public function pregQuote($str)
    {
        return preg_quote($str);
    }

    /**
     *  将byte数字换成1024合适的单位
     * @param $size int 大小(byte)
     * @param $dec int 精度
     * @return string 结果
     * @author love_fc
     */
    public function byteSize($size, $dec = 2)
    {
        return \Yuri2::byteSize($size, $dec);
    }

    /**
     * 自动检测当前字符串编码，转换为指定编码
     * @param $data string 目标
     * @param $to string 转换为什么编码
     * @return string 结果
     * @author love_fc
     */
    public function autoEncoding($data, $to = 'utf-8')
    {
        return \Yuri2::autoEncoding($data, $to);
    }

    /**
     * 自动把字符串变成操作系统默认编码
     * 依赖于getOS() getOSCoding() autoEncoding()
     * @param $str string 处理目标
     * @return string 结果
     * @author yuri2
     */
    public function autoSysCoding($str)
    {
        return \Yuri2::autoSysCoding($str);
    }

    /**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     * @param string $str 需要转换的字符串
     * @param int $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @return string
     * @author love_fc
     */
    public function subStr($str, $start = 0, $length, $charset = "utf-8")
    {
        return \Yuri2::subStr($str, $start, $length, $charset);
    }

    /** md格式转html */
    public function mdToHtml($md)
    {
        $p = new Parsedown();
        $html = $p->text($md);
        return "<div class='parse-down'>$html</div>";
    }

    /**
     * 索引数组转关联数组
     * @param $rows array
     * @param $name_id string
     * @return array
     */
    public function arrayIndexToAssoc($rows, $name_id)
    {
        $rel = [];
        foreach ($rows as $row) {
            if (isset($row[$name_id])) {
                $rel[$row[$name_id]] = $row;
            }
        }
        return $rel;
    }

    /**
     * Excel文件转索引二维数组
     * @param $file_excel string
     * @return array
     */
    public function excelToRows($file_excel)
    {
        $file_excel = $this->autoSysCoding($file_excel);
        $excelHelper = maker()->excel();
        $cells = $excelHelper->loadFromFile($file_excel)->ObjToArray();
        return $cells;
    }

    /**
     * 索引二维数组转Excel文件
     * @param $rows array
     * @param $file string 全路径表示保存，否则表示浏览器下载(默认)
     * @return string
     */
    public function rowsToExcel($rows, $file = 'data.xlsx')
    {
        $excelHelper = maker()->excel();
        $excelObj = $excelHelper->getExcelObj();
        $sheet = $excelObj->setActiveSheetIndex(0);
        $y = 1;
        foreach ($rows as $index => $row) {
            $x = 1;
            foreach ($row as $col => $val) {
                $sheet->setCellValue($excelHelper->coordinateToCell($x, $y), $val);
                $x++;
            }
            $y++;
        }
        $ext = \Yuri2::getExtension($file);
        if ($ext == '') {
            $file .= '.xlsx';
        }

        //判断是否全路径
        if (strpos($file, '/') or strpos($file, '\\')) {
            //全路径
            maker()->file()->createDir(dirname($file));
            $file = $this->autoSysCoding($file);
            $excelHelper->saveFile($file);
            return $this->autoEncoding($file);
        } else {
            //下载
            $excelHelper->downloadFile($file);
            return $file;
        }
    }

}