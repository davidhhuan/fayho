<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Util;

/**
 * 响应请求工具类
 * 
 * @author birdylee <170915870@qq.com>
 */
class Request
{
    private static $isWechat = null;

    private static $isAlipay = null;

    /**
     * 请求是否在微信内置浏览器
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    public static function isWechat()
    {
        if (is_null(self::$isWechat)) {
            self::$isWechat = strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger');
        }

        return self::$isWechat;
    }

    /**
     * 请求是否在支付宝内置浏览器
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    public static function isAlipay()
    {
        if (is_null(self::$isAlipay)) {
            self::$isAlipay = strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient');
        }

        return self::$isAlipay;
    }

}