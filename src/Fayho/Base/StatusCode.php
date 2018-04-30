<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Base;

use Fayho\Util\Arrays;
use Fayho\Util\Response;

/**
 * 状态码
 *
 */
class StatusCode
{
    const SUCCESS = 200;
    
    /**
     * 获取状态。返回包括状态码和提示语
     * 
     * @param type $status
     * @return array
     * > 说明
     * key | value
     * -----------------------------|---------------------------
     * status                       |  int 
     * msg                          |  string
     */
    public static function get($status)
    {
        return [
            'status' => $status, 
            'msg' => static::getList()[$status], 
        ];
    }
    
    /**
     * 获取提示语
     * 
     * @param int $status
     * @return string
     */
    public static function getMsg($status)
    {
        return static::getList()[$status];
    }
    
    /**
     * 获取返回数据
     * 
     * @param int $status
     * @param array|string $result
     */
    public static function handleReturn($status, $result = '')
    {
        $statusInfo = static::get($status);
        
        return Arrays::mergeArray($statusInfo, ['result' => $result]);
    }

    /**
     * 获取返回数据
     * 
     * @param int $status
     * @param array|string $result
     */
    public static function handleReturnJson($status, $result = '')
    {
        $result = static::handleReturn($status, $result);

        return Response::returnJson($result['status'], $result['msg'], $result['result']);
    }
    
    /**
     * 结果是否成功
     * @param type $rs （array || code）
     */
    public static function isSuccess($rs)
    {
        if(is_array($rs)){
            return $rs['status'] === self::SUCCESS;
        }
        
        return $rs === self::SUCCESS;
    }
    
    /**
     * 
     * @return type
     */
    protected static function getList()
    {
        return self::$list;
    }
        
    /**
     * 状态码列表
     *
     * @var array
     */
    private static $list = [
        //////////////////////////////  系统级错误  ////////////////////////////////////
        200 => 'success', //成功
        400 => 'Bad request', //请求参数错误
        403 => 'Forbidden', //无权限
        404 => 'Not found', //未找到(无此种请求)
        407 => 'Paramers error', //服务请求参数错误
        408 => 'Time out', //请求超时
        409 => 'conflict', //重复请求
        500 => 'Server error', //服务器未知错误
        
        //////////////////////////////  接口错误  ////////////////////////////////////
        40000 => 'Unknow error', //未知错误
        40001 => 'Db server error', //数据库服务器未知错误
        40002 => 'Server account error', //服务帐号不存在
        40003 => 'Server decode error', //服务器解码错误
        
        //////////////////////////////  常规错误  ////////////////////////////////////
        50001 => 'Header: Request-Valid-Appid required', //Request-Valid-Appid 必须包含
        50002 => 'Header: Request-Valid-Version required', //Request-Valid-Version 必须包含
        50003 => 'Header: Request-Valid-Token required', //Request-Valid-Token 必须包含
        50004 => 'Header: Request-Valid-Sign required', //Request-Valid-Sign 必须包含
        50005 => 'Header: Request-Valid-Appid invalid', //Request-Valid-Appid 非法
        50006 => 'Header: Request-Valid-Version invalid', //Request-Valid-Version 非法
        50007 => 'Header: Request-Valid-Token invalid', //Request-Valid-Token 非法
        50008 => 'Header: Request-Valid-Sign invalid', //Request-Valid-Sign 非法
    ];
}