<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Util;

use Fayho\Util\Strings;

/**
 * 文件
 *
 * @author  birdylee <birdylee_cn@163.com>
 * @since   2017年08月07日
 * @version 1.0
 *
 */
class File 
{

    /**
     * 获取随机名字
     * 
     * @param string $prefix 前缀
     * @return string
     * 
     */
    public static function getRandomName($prefix = '') 
    {
        return md5(uniqid($prefix, true) . '_' . Strings::randString(10, 7));
    }

    /**
     * 创建文件
     * @param type $absolute_path
     * @param type $mode
     * @return boolean
     */
    public static function fayhoMkdir($absolutePath, $mode = 0777) 
    {
        if (is_dir($absolutePath)) {
            return true;
        }

        $curPath = '';
        $prePath = array(ROOT_PATH);
        foreach ($prePath as $path) {
            if (0 === stripos($absolutePath, $path)) {
                $curPath = $path;
                break;
            }
        }

        if ('' == $curPath) {
            return false;
        }

        $relativePath = str_replace($curPath, '', $absolutePath);
        $eachPath = explode('/', $relativePath);
        foreach ($eachPath as $path) {
            if ($path) {
                $curPath = $curPath . '/' . $path;
                if (!is_dir($curPath)) {
                    if (@mkdir($curPath, $mode)) {
                        chmod($curPath, 0777); //修改目录权限
                        //fclose(fopen($curPath . '/index.htm', 'w'));
                    } else {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * 解压文件 可解压指定类型文件.
     * @param type $file  解压的文件
     * @param type $savePath  解压存放的路径
     */
    public static function extract($file, $savePath) 
    {
        if (!file_exists($savePath)) {
            File::fayhoMkdir($savePath);
        }

        $fileExt = pathinfo($file, PATHINFO_EXTENSION);
        if ($fileExt == 'zip') {
            $zip = new Zip();
            $res = $zip->open($file);
            if ($res === TRUE) {
                @$zip->extractTo($savePath);
                $zip->close();
            } else {
                if (\think\App::$debug) {
                    throw new \Exception($zip->message($res));
                }
                return false;
            }
        } elseif ($fileExt == 'rar') {
            $rar = \RarArchive::open($file);
            if (is_object($rar)) {
                foreach ($rar->getEntries() as $file) {
                    @$file->extract($savePath);
                }
                $rar->close();
            } else {
                return false;
            }
        } else {
            if (\think\App::$debug) {
                throw new \Exception('extract file error:extension not support,only zip、rar。');
            }
            return false;
        }

        return true;
    }
    
    /**
     * 压缩文件
     * @param type $filename 需压缩的文件，可传入目录，自动会读取目录下文件
     * @param type $saveFilename 保存压缩文件名
     * @param type $savePath 保存压缩文件位置
     * @param type $filterExt 压缩时需过滤的文件
     * @return boolean
     */
    public static function zip($filename ,$saveFilename ,$savePath ,$filterExt = "*")
    {
        $allFiles = [];
        if(is_dir($filename)){
            $allFiles = self::readAllFiles($filename ,true);
        }else{
            $allFiles[] = $filename;
        }
        
        if(!empty($filterExt) && $filterExt != "*"){
            $allFiles = self::filterFilesByExt($allFiles, $filterExt);
        }
               
        $zip = new Zip();
        self::fayhoMkdir($savePath);
        $res = $zip->open($savePath . DS . $saveFilename, \ZipArchive::CREATE);
        if($res){
            foreach($allFiles as $file){
                $fileInfo = pathinfo($file);
                $zip->addFile($file ,$fileInfo['basename']);
            }
            
            $zip->close();
            return true;
        }
        
        return false;        
    }

    /**
     * 读取指定路径下的所有文件
     * @param type $dir
     * @param type $recursive
     * @return boolean|string
     */
    public static function readAllFiles($dir, $recursive = FALSE) 
    {
        if (is_dir($dir)) {
            for ($list = array(), $handle = opendir($dir); (FALSE !== ($file = readdir($handle)));) {
                if (($file != '.' && $file != '..') && (file_exists($path = $dir . '/' . $file))) {
                    if (is_dir($path) && ($recursive)) {
                        $list = array_merge($list, self::readAllFiles($path, TRUE));
                    } else {
                        $item = $dir . '/' . $file;
                        do
                            if (!is_dir($path)) {
                                break;
                            } else {
                                break;
                            } while (FALSE);
                        $list[] = $item;
                    }
                }
            }
            closedir($handle);
            return $list;
        } else
            return FALSE;
    }

    /**
     * 从文件列表中筛选出指定扩展名文件
     * @param type $fileList
     * @param type $ext
     * @param type $invert true:非正则内数据
     * @return boolean
     */
    public static function filterFilesByExt($fileList, $ext, $invert = false) 
    {
        if (is_array($fileList)) {
            $res = $invert ? preg_grep("#{$ext}#i", $fileList, PREG_GREP_INVERT) : preg_grep("#{$ext}#i", $fileList);
            if ($res) {
                return $res;
            }
        }

        return false;
    }

    /**
     * 使用utf-8打开unicode编码文件
     * @param type $filename 文件名
     * @return boolean
     */
    public static function fopenUtf8($filename) 
    {
        if (!file_exists($filename)) {
            return false;
        }
        $encoding = '';
        $data = file_get_contents($filename);
        $bom = substr($data, 0, 2);
        if ($bom === chr(0xff) . chr(0xfe) || $bom === chr(0xfe) . chr(0xff)) { //mb_detect_encoding 无法检测UFT-16
            $encoding = 'UTF-16';
        } else {
            $encoding = mb_detect_encoding($data, 'UTF-8, GBK, UTF-7, ASCII, EUC-JP,SJIS, eucJP-win, SJIS-win, JIS, ISO-2022-JP');
        }
        if ($encoding) {
            $data = mb_convert_encoding($data, 'UTF-8', $encoding); //转换为UTF-8
            file_put_contents($filename, $data);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除文件目录
     * @param type $dir
     * @return boolean
     */
    public static function removeDir($dir) 
    {
        
        if (file_exists($dir)) {
            if(!is_dir($dir)){
                @unlink($dir);
                return true;
            }
            
            //先删除目录下的文件：
            $dh = opendir($dir);
            while (false !== ($file = readdir($dh))) {
                if ($file != "." && $file != "..") {
                    $fullpath = $dir . "/" . $file;
                    if (!is_dir($fullpath)) {
                        @unlink($fullpath);
                    } else {
                        self::removeDir($fullpath);
                    }
                }
            }

            closedir($dh);
            //删除当前文件夹：
            $res = @rmdir($dir) ? true : false;
            return $res;
        }

        return false;
    }
    
    /**
     * 删除文件
     * @param type $fileName
     */
    public static function removeFile($fileName)
    {        
        @unlink($fileName);
    }
}
