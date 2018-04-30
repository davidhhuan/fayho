<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Util;

/**
 * 数字扩展类
 *
 * @author  birdylee <birdylee_cn@163.com>
 *
 */
class Number
{

    /**
     * 比较两个浮点数的大小
     *
     * @param float $leftValue
     * @param float $rightValue
     * @return number 1:>; 0:==; -1:<
     * 
     */
    public static function comparedNumber($leftValue, $rightValue)
    {
        $leftValue = floor(($leftValue + 0.00001) * 100);
        $rightValue = floor(($rightValue + 0.00001) * 100);
        if ($leftValue > $rightValue) {
            return 1;
        } elseif ($leftValue < $rightValue) {
            return -1;
        }
        return 0;
    }

    /**
     * 多个浮点数相加减
     * 
     * @param array $numbers [8, -8, 9] 要减的数值为负数
     * @return int
     */
    public static function calculateNumber(array $numbers)
    {
        $rs = 0;
        if (!empty($numbers)) {
            foreach ($numbers as $num) {
                $rs += floor(($num + 0.00001) * 1000);
            }
        }
        return $rs / 1000;
    }

    /**
     * 获取包含某个值的位运算组合(目前支持64以内)
     *
     * @param int $value 查询的值
     * @param int $level 字段所包含的个数
     * @return array
     *
     */
    public static function getGroupsByPosition($value, $level = 4)
    {
        $value = intval($value);

        $data = array();
        $level = pow(2, $level);
        for ($i = 1; $i < $level; $i++) {
            $i & $value && $data[] = $i;
        }
        return $data;
    }

    /**
     * 获取某个值所包含的位运算组合(目前支持64以内)
     *
     * @param int $value 查询的值
     * @param int $level 字段所包含的个数
     * @return array
     *
     */
    public static function getChildrenByPosition($value, $level = 4)
    {
        $value = intval($value);

        $data = array();
        for ($i = 0; $i < $level; $i++) {
            $j = pow(2, $i);
            $value & $j && $data[] = $j;
        }
        return $data;
    }

    /**
     * 将数字转换成字符串表示
     * ----------------------------------
     * 小于10000,   全部显示
     * 大于100000,  显示10W+
     * 1~10W之间,   以W为单位，保留两位小数
     *
     * @param  int   $number   数字
     */
    public static function getNumberString($number)
    {
        return $number >= 100000 ? '10万 +' : ($number < 10000 ? $number : round(($number / 10000), 2) . '万');
    }

}
