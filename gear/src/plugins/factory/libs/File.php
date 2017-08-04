<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/3/21
 * Time: 10:44
 */

namespace src\plugins\factory\libs;

class File
{
    /**
     * 加锁写入
     * @param $path string
     * @param $mode int
     * @param $data string
     * @return bool
     */
    public static function writeData($path, $mode, $data)
    {
        return \Yuri2::writeData($path, $mode, $data);
    }

    /**
     * 删除文件
     * @param $filename string path
     * @return string|bool
     */
    public function deleteFile($filename){
        $filename = \Yuri2::autoSysCoding($filename);
        if (is_file($filename)) {
            return unlink($filename);
        } else {
            return false;
        }
    }

    /**
     * 读文件
     * @param $filename string path
     * @return string|bool
     */
    public function readFile($filename)
    {
        $filename = \Yuri2::autoSysCoding($filename);
        if (is_file($filename)) {
            return file_get_contents($filename, LOCK_SH);
        } else {
            return false;
        }
    }

    /**
     * 写文件
     * @param $filename string path
     * @param $data string content
     * @return bool
     */
    public function writeFile($filename, $data)
    {
        $filename=\Yuri2::autoSysCoding($filename);
        \Yuri2::createDir(dirname($filename));
        $result = file_put_contents($filename, $data, LOCK_EX);
        if ($result!==false) {
            clearstatcache();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $file string
     * @return bool
     */
    public function fileExists($file){
        $file=\Yuri2::autoSysCoding($file);
        return file_exists($file);
    }

    /**
     * 删除目录及之下的文件
     * @param $dir string 目标目录
     * @param $remain_self bool 是否不删除目录本身
     * @return bool 是否成功
     */
    public function deleteDir($dir, $remain_self = false)
    {
        return \Yuri2::delDir($dir, 'first', $remain_self);
    }

    /**
     * 拷贝目录到一个新目录
     * @param $src string 源目录
     * @param $dst string 目标目录
     * @return bool 是否成功
     * @author yuri2
     */
    public function copyDir($src, $dst)
    {
        return \Yuri2::recurseCopy($src, $dst);
    }

    /**
     * 创建多级目录
     * @param $path string 目标路径
     * @param $mode int 权限
     * @return bool 是否成功
     * @author yuri2
     * */
    public function createDir($path, $mode = 0775){
        return \Yuri2::createDir($path, $mode);
    }

    /**
     * 获取扩展名，不含.符号
     * @param $fileName string 一个合法的文件名
     * @return string 扩展名
     * @author yuri2
     */
    public function getExtension($fileName)
    {
        return \Yuri2::getExtension($fileName);
    }

    /**
     * 获取文件名，不含路径
     * @param $file_path string
     * @return string
     */
    public function getRetrieve($file_path)
    {
        return \Yuri2::getRetrieve($file_path);
    }

    /**
     * 遍历文件夹
     * @param $dir string
     * @param $callable callable
     * @return array
     */
    public function ergodicDir($dir, $callable)
    {
        return \Yuri2::ergodicDir($dir, $callable);
    }

    /**
     * 递归遍历目录及之下的文件
     * @param $dir string 目标目录
     * @param $funcFile callable|string 对文件的操作函数
     * @param $funcDir callable|string 对目录的操作函数
     * @author yuri2
     */
    public function ergodicDirRecursion($dir, $funcFile = '', $funcDir = '')
    {
        \Yuri2::ergodicDirRecursion($dir, $funcFile, $funcDir);
    }

    /**
     * 下载文件
     * @param $file_path string 文件路径
     * @return bool 文件是否存在
     * @author love_fc
     */
    public function download($file_path)
    {
        return \Yuri2::download($file_path);
    }

    /**
     * 离线下载
     * @param $url string 下载地址
     * @param $file string 保存路径
     * @param $second int 超时时间
     * @return bool 是否成功
     * @author love_fc
     */
    public function offlineDownload($url, $file, $second = 720)
    {
        return \Yuri2::offlineDownload($url, $file, $second);
    }

    /**
     * 获取文件夹大小
     * @param $dir string 文件夹路径
     * @return int $sizeResult 大小（字节）
     */
    public function getDirSize($dir){
        return \Yuri2::getDirSize($dir);
    }

}