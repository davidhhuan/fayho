<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Util;

/**
 * 日期处理
 * 
 * @author allenbin
 */
class Date 
{
    /**
     * 将日期格式化为倒计时
     * @param type $timestamp
     */
    public function formatExpireString($timestamp = 0)
    {
        $string = "";
        
        //天
        if($timestamp > 86400){
            $day = $timestamp/86400;
            $timestamp -= $day * 86400;
        }
        
        //小时
        if($timestamp > 3600){
            $hour = $timestamp/3600;
            $timestamp -= $hour * 3600;
        }
        
        //分
        if($timestamp > 60){
            $min = $timestamp/60;
            $timestamp -= $min * 60;
        }
        
        //秒
        $sec = $timestamp;
        
        !empty($day) && $string .= $day . "天";
        !empty($hour) && $string .= $hour . "小时";
        !empty($min) && $string .= $min . "分";
        !empty($sec) && $string .= $sec . "秒";
        
        return $string;
    }
}
