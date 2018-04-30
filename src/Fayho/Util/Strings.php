<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Util;

/**
 * 字符串
 *
 * @author  birdylee <birdylee_cn@163.com>
 *
 */
class Strings
{

    /**
     * 生成随机字符串
     *
     * @param  number $len 需要随机字符串长度
     * @param  number $type 组合需要的字符串
     *                           0001：大写字母，0010：小写字母，0100：数字，1000：特殊字符；根据需要组合(二进制相加后对应的十进制值)
     *                           默认：7=0111，即：大写字母+小写字母+数字
     * @param  string $addChars 添加自己的字符串
     * @return string
     *
     */
    public static function randString($len = 10, $type = 7, $addChars = '')
    {
        static $strings = array(
            1 => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            2 => 'abcdefghijklmnopqrstuvwxyz',
            4 => '0123456789',
            8 => '!@#$%^&*()-_ []{}<>~`+=,.;:/?|');
        $type > 15 && $type = 15;
        $chars = $addChars;
        foreach ($strings as $k => $v) {
            $type & $k && $chars .= $v;
        }
        return substr(str_shuffle($chars), 0, $len);
    }

    /**
     * 随机md5值
     *
     * @param int $len
     * @param string $prefix
     *
     * @return string
     *
     * @author birdylee <birdylee_cn@163.com>
     * @date 2018.04.12
     */
    public static function randMd5($prefix = 'randAlnum')
    {
        return md5(uniqid($prefix, true) . '_' . static::randString(256, 15) . microtime());
    }

    /**
     * Utf-8 gb2312都支持的汉字截取函数 cutStr(字符串, 截取长度, 开始长度, 编码)
     *
     * @param $string string 要处理的字符串
     * @param $sublen number 截取长度
     * @param $start  number 偏移量
     * @param $code   string 要处理的字符串编码
     * @return string
     */
    public static function cutStr($string, $sublen, $start = 0, $code = 'UTF-8')
    {
        if ($code == 'UTF-8') {
            $t_string = [];
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);

            if (count($t_string[0]) - $start > $sublen)
                return join('', array_slice($t_string[0], $start, $sublen)) . "...";
            return join('', array_slice($t_string[0], $start, $sublen));
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';

            for ($i = 0; $i < $strlen; $i++) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    $tmpstr = ord(substr($string, $i, 1)) > 129 ? $tmpstr . substr($string, $i, 2) : $tmpstr . substr($string, $i, 1);
                }
                if (ord(substr($string, $i, 1)) > 129)
                    $i++;
            }

            if (strlen($tmpstr) < $strlen)
                $tmpstr .= "...";
            return $tmpstr;
        }
    }

    /**
     * 字符串长度
     * @param   string $string
     * @param   bool $type str: 字符长度；char: 字长度，中文英文都算1个字；word: 字长度2，中文算2个，英文算1个
     * @param   bool $onlyWord 只算字，中文算一个，英文算半个
     *
     * @return  type
     */
    public static function sysStrlen($string, $type = 'str')
    {
        $rslen = 0;
        $strlen = 0;
        $length = strlen($string);
        switch ($type) {
            case 'str':
                $rslen = $length;
                break;
            case 'char':
                while ($strlen < $length) {
                    $stringTMP = substr($string, $strlen, 1);
                    if (ord($stringTMP) >= 224) {
                        $strlen = $strlen + 3;
                    } elseif (ord($stringTMP) >= 192) {
                        $strlen = $strlen + 2;
                    } else {
                        $strlen = $strlen + 1;
                    }
                    $rslen++;
                }
                break;
            case 'word':
                while ($strlen < $length) {
                    $stringTMP = substr($string, $strlen, 1);
                    if (ord($stringTMP) >= 224) {
                        $strlen = $strlen + 3;
                        $rslen += 2;
                    } elseif (ord($stringTMP) >= 192) {
                        $strlen = $strlen + 2;
                        $rslen += 2;
                    } else {
                        $strlen = $strlen + 1;
                        $rslen++;
                    }
                }
                break;
        }

        return $rslen;
    }

    /**
     * 字符串截取，支持中文和其他编码
     *
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * @return string
     */
    public static function msubstr($str, $start = 0, $length, $charset = "utf-8")
    {
        if (function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        return $slice;
    }

    /**
     * 超出字符串替换
     *
     * @param string $str 需要处理的字符串
     * @param int $len 字符串长度限定
     * @param string $replaceStr 超长部分替换为此字符串
     * @return string
     */
    public static function replaceStrOverLength($str, $len, $replaceStr = '...')
    {
        $strlen = Strings::sysStrlen($str, 'char');
        return ($strlen <= $len) ? $str : (Strings::msubstr($str, 0, $len) . $replaceStr);
    }

    /**
     * 截取UTF-8编码下字符串的函数
     *
     * @param   string $str 被截取的字符串
     * @param   int $length 截取的长度
     * @param   bool $append 是否附加省略号
     *
     * @return  string
     */
    public static function utf8Substr($string, $length = 0, $append = true)
    {
        if (strlen($string) <= $length) {
            return $string;
        }

        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

        $strcut = '';

        if (strtolower(CHARSET) == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {

                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t < 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }

                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }

            $strcut = substr($string, 0, $n);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
            }
        }

        $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

        if ($append && $string != $strcut) {
            $strcut .= '...';
        }
        return $strcut;
    }

    /**
     * 长字符串压缩
     * @param mixed $dataToStore string, array, object, etc.
     * @param int $strlen 截断长度
     * @return string
     */
    public static function gz($dataToStore, $strlen = 60000)
    {
        if (is_string($dataToStore)) {
            $dataToStore = Strings::utf8Substr($dataToStore, $strlen);
        }

        $strData = strtr(
            base64_encode(
                addslashes(
                    gzcompress(serialize($dataToStore), 9)
                )
            ), '+/=', '-_,');

        return $strData;
    }

    /**
     * 数据库压缩数据解压
     * @param string $strDataFromDb
     * @return mixed
     */
    public static function ungz($strDataFromDb)
    {
        $strDataFromDb = stripslashes(
            base64_decode(
                strtr($strDataFromDb, '-_,', '+/=')
            )
        );

        if (strlen($strDataFromDb) == 0 || ord($strDataFromDb[0]) != 0x78 || !in_array(ord($strDataFromDb[1]), array(0x01, 0x5e, 0x9c, 0xda))) {
            return NULL;
        }

        $returnData = @unserialize(
            gzuncompress($strDataFromDb)
        );

        return $returnData;
    }

    /**
     * 是否手机号码
     *
     * @param type $mobile
     * @return boolean
     */
    public static function isMobile($mobile)
    {
        if (preg_match('/1[34578]\d{9}$/', $mobile)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否固定电话
     *
     * @param type $phone
     * @return boolean
     */
    public static function isTelephone($phone)
    {
        if (preg_match('/^([0-9]{3,4}-)?[0-9]{7,8}$/', $phone)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成签名算法
     *
     * @param $appid
     * @param $appsecret
     * @param array $data
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    public static function generateSign($appid, $appsecret, array $data)
    {
        ksort($data);
        $str = '';
        foreach ($data as $key => $value) {
            $str .= '&' . $key . '=' . $value;
        }
        ltrim($str, '&');

        return md5($appid . $str . $appsecret);
    }

    /**
     * 签名是否吻合
     *
     * @param $appid
     * @param $appsecret
     * @param array $data
     * @param $sign
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    public static function isSignOk($appid, $appsecret, array $data, $sign)
    {
        return static::generateSign($appid, $appsecret, $data) == $sign;
    }

    /**
     * @param $string
     * @param string $class_name
     * @param int $options
     * @param string $ns
     * @param bool $is_prefix
     * @return bool|\SimpleXMLElement
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    public static function simplexmlLoadString($string, $class_name = 'SimpleXMLElement', $options = 0, $ns = '', $is_prefix = false)
    {
        libxml_disable_entity_loader(true);
        if (preg_match('/(\<\!DOCTYPE|\<\!ENTITY)/i', $string)) {
            return false;
        }
        return simplexml_load_string($string, $class_name, $options, $ns, $is_prefix);
    }

}
