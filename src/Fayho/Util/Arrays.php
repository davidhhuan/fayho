<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Util;

/**
 * 数组
 *
 * @author  birdylee <birdylee_cn@163.com>
 *
 */
class Arrays
{

    /**
     * 二维数组 排序
     * Arrays::multisort($data, 'order', SORT_ASC, 'id', SORT_DESC);
     * 
     * @param  mixed ...$args
     * @return array
     * @author birdylee <170915870@qq.com>
     */
    public static function multisort()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return $data;
    }

    /**
     * 读取一列
     * 
     * @param  array  $array      源数组
     * @param  string $column_key 列名
     * @param  string $index_key  是否使用某个列做键值
     * @return array
     * @author birdylee <170915870@qq.com>
     */
    public static function column(array $array, $column_key, $index_key = null)
    {
        if (function_exists('array_column')) {
            return array_column($array, $column_key, $index_key);
        }
        if (is_null($column_key)) {
            return $array;
        }
        $result = [];
        foreach ($array as $arr) {
            if (! is_array($arr)) {
                continue;
            }
            $value = $arr[$column_key];
            if (isset($index_key)) {
                $result[$arr[$index_key]] = $value;
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * 数组字段映射
     * Arrays::mapping(array('id'=>11),array('id'=>'uid'));
     * 将id的键名改为uid
     *
     * @param  array $array
     * @param  array $map
     * @return array
     * @author birdylee <170915870@qq.com>
     */
    public static function mapping(array $array, array $map)
    {
        $retval = array();
        if (empty($map)) {
            return $retval;
        }
        foreach ($array as $key => $value) {
            $retval[isset($map[$key]) ? $map[$key] : $key] = $value;
        }
        return $retval;
    }

    /**
     * 获取数组最后的一个键值
     *
     *
     * @param array $arr
     * @return array
     * @author birdylee <170915870@qq.com>
     */
    public static function getArrayLastKey(array $arr)
    {
        $keys = array_keys($arr);
        return end($keys);
    }

    /**
     * 多维数组转一维数组,此方法不保留键名
     *
     * @param  $arr     array    要处理的多维数组
     * @param  $fields  array    要保留字段，默认全部保留
     * @return array
     * @author birdylee <170915870@qq.com>
     */
    public static function multidimenToOne(array $arr, array $fields = [])
    {
        if (! is_array($arr))
            return false;
        
        $res = [];
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $tmp = Arrays::multidimenToOne($value, $fields);
                $res = array_merge($res, $tmp);
                continue;
            }
            if (empty($fields) || in_array($key, $fields)) {
                array_push($res, $value);
            }
        }
        return $res;
    }

    /**
     * 数组键值转换字符串
     *
     * @param $data
     * @return string
     * @author birdylee <170915870@qq.com>
     */
    public static function toString($data)
    {
        if (empty($data) && !is_object($data)) {
            return [];
        }
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    $data[$key] = self::toString($val);
                } else {
                    !is_bool($val) && !is_object($data) && $data[$key] = (string) $val;
                }
            }
        } else if (!is_bool($data) && !is_object($data)) {
            $data = (string) $data;
        }
        return $data;
    }
    
    /**
     * 合并两个，或者多个数组
     *
     * 跟Arrays::mergeArray的区别。
     *
     * Arrays::mergeArray当索引是数字时不覆盖，这个方法是不同索引是不是数字，都进行覆盖
     *
     * @param array $a
     * @param array $b
     * @return array
     * @author birdylee <170915870@qq.com>
     */
    public static function mergeArrayAll($a, $b) {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::mergeArrayAll($res[$k], $v);
                } else {
                    
                    $res[$k] = $v;
                }
            }
        }
        return $res;
    }

    /**
     * 合并两个，或者多个数组
     *
     * @param array $a
     * @param array $b
     * @return array
     * @author birdylee <170915870@qq.com>
     */
    public static function mergeArray($a, $b) {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_integer($k))
                    isset($res[$k]) ? $res[] = $v : $res[$k] = $v;
                elseif (is_array($v) && isset($res[$k]) && is_array($res[$k]))
                    $res[$k] = self::mergeArray($res[$k], $v);
                else
                    $res[$k] = $v;
            }
        }
        return $res;
    }
    
    /**
     * 数组转换, 驼峰命名法转下划线风格
     * 
     * @param array $data
     * 
     * @author birdylee <170915870@qq.com>
     */
    public static function toUnderScoreArray($data) 
    {
        if (is_string($data)){
            return $data;
       }

        if (is_array($data)){
            foreach ($data as $key => $value){
                $newvalue = static::toUnderScoreArray($value);
                unset($data[$key]);
                $newkey = static::toUnderScore($key);
                $data[$newkey]=$newvalue;
            }
        }

         return $data;
    }
    
    /**
     * 驼峰命名法转下划线风格
     * 
     * @param string $str
     * @return string
     * 
     * @author birdylee <170915870@qq.com>
     */
    public static function toUnderScore($str)
    {
        if (is_numeric($str)) {
            return $str;
        }
        
        $array = array();
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] == strtolower($str[$i])) {
                $array[] = $str[$i];
            } else {
                if ($i > 0) {
                    $array[] = '_';
                }
                $array[] = strtolower($str[$i]);
            }
        }

        $result = implode('', $array);
        return $result;
    }
    
    /**
     * 数组转换, 下划线风格转驼峰命名法
     * 
     * @param array $data
     * @see Arrays::toUnderScore
     * 
     * @author birdylee <170915870@qq.com>
     */
    public static function toCamelCaseArray($data) 
    {
        if (is_string($data)){
            return $data;
       }

        if (is_array($data)){
            foreach ($data as $key => $value){
                $newvalue = static::toCamelCaseArray($value);
                unset($data[$key]);
                $newkey = static::toCamelCase($key);
                $data[$newkey]=$newvalue;
            }
        }

         return $data;
    }
    
    /**
     * 下划线风格转驼峰命名法
     * 
     * @param string $str
     * @return string
     * 
     * @author birdylee <170915870@qq.com>
     */
    public static function toCamelCase($str)
    {
        if (is_numeric($str)) {
            return $str;
        }
        
        $array = explode('_', $str);
        $result = '';
        if (isset($array[0]) && empty($array[0])) {
            unset($array[0]);
            sort($array);
        }
        if (count($array) == 1) {
            $result = $array[0];
        } else {
            foreach ($array as $key => $value) {
                $result .= $key == 0 ? $value : ucfirst($value);
            }
        }

        return $result;
    }

    /**
     * xml转换成数组
     *
     * @param $xml
     * @return mixed
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    public static function xml2array($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }

    /**
     * 数组转换成xml
     *
     * @param array $arr
     * @param int $level
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    public static function array2xml(array $arr, $level = 1)
    {
        $s = $level == 1 ? "<xml>" : '';
        foreach ($arr as $tagname => $value) {
            if (is_numeric($tagname)) {
                $tagname = $value['TagName'];
                unset($value['TagName']);
            }
            if (!is_array($value)) {
                $s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
            } else {
                $s .= "<{$tagname}>" . static::array2xml($value, $level + 1) . "</{$tagname}>";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s . "</xml>" : $s;
    }
    
}