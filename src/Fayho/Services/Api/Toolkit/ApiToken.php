<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Services\Api\Toolkit;

/**
 * The interface of api service
 *
 */
class ApiToken
{
    /**
     * 生成token的签名
     * 
     * @param string $appid
     * @param string $appsecret
     * @param string $token
     * 
     * @return string
     *
     * @author birdylee <birdylee_cn@163.com>
     *
     */
    public static function generateTokenSign(string $appid, string $appsecret, string $token)
    {
        $string = http_build_query([
            'appid' => $appid, 
            'appsecret' => $appsecret, 
            'token' => $token, 
        ]);

        return md5($string);
    }

    /**
     * token签名是否合法
     * 
     * @param string $appid
     * @param string $appsecret
     * @param string $token
     * @param string $sign
     * 
     * @return boolean
     *
     * @author birdylee <birdylee_cn@163.com>
     *
     */
    public static function isTokenSignOk(string $appid, string $appsecret, string $token, string $sign)
    {
        $generageSign = static::generateTokenSign($appid, $appsecret, $token);
        return $sign === $generageSign;
    }
}