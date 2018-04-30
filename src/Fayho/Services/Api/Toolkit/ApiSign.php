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
 * api签名
 *
 */
class ApiSign
{
    /**
     * 生成签名
     *
     * @param string $appid
     * @param string $signSecret 签名时的密钥。可以是appsecret，或者是token
     * @param string $timestamp 时间戳
     * @param array $data 数据
     * 
     * @return string
     *
     * @author birdylee <birdylee_cn@163.com>
     * 
     */
    public static function generateSign(string $appid, string $signSecret, string $timestamp, array $data = []): string
    {
        $dataString = http_build_query([
            'appid' => $appid, 
            'sign_secret' => $signSecret, 
            'timestamp' => $timestamp, 
        ]);

        if (!empty($data)) {
            ksort($data);
            $dataString .= '&' . http_build_query($data);
        }
        
        return md5($dataString);
    }

    /**
     * 签名是否正确
     *
     * @param string $appid
     * @param string $signSecret 签名时的密钥。可以是appsecret，或者是token
     * @param string $timestamp 时间戳
     * @param array $data 数据
     * 
     * @return string
     *
     * @author birdylee <birdylee_cn@163.com>
     * 
     */
    public static function isSignOk(string $appid, string $signSecret, string $timestamp, string $sign, array $data = []): boolean
    {
        $generateSign = static::generateSign($appid, $signSecret, $timestamp, $data);
        return $generateSign === $sign;
    }
    
}