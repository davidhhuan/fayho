<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Services\Api\Interfaces;

use Swoft\Core\ResultInterface;

/**
 * The interface of api service
 *
 * @method ResultInterface deferGetApp(string $appid)
 */
interface ApiInterface
{

    /**
     * 
     * 获取app信息
     *
     * @param string $appid
     *
     * @return array
     * <pre>
     * > 基础数据
     * 参数                       |      类型      |   描述
     * --------------------------|----------------|-------------------
     * appid                     |    string      |
     * appsecret                 |    string      |  
     * status                    |    string      |  0，未激活。1，正常。2，禁止使用
     * created_time              |    string      |  
     * </pre>
     *
     * @author birdylee <birdylee_cn@163.com>
     * 
     */
    public function getApp(string $appid);

    /**
     * 
     * 校验token是否有效
     * 
     * @param string $appid
     * @param string $token
     *
     * @return array
     * <pre>
     * > 基础数据
     * 参数                       |      类型      |   描述
     * --------------------------|----------------|-------------------
     * expired_time              |    string      |  过期时间戳
     * </pre>
     *
     * @author birdylee <birdylee_cn@163.com>
     * 
     */
    public function isTokenOk(string $appid, string $token);

    /**
     * 
     * 生成token
     * 
     * @param string $appid
     *
     * @return array
     * <pre>
     * > 基础数据
     * 参数                       |      类型      |   描述
     * --------------------------|----------------|-------------------
     * token_key                 |    string      |  token
     * expired_time              |    string      |  过期时间戳
     * </pre>
     *
     * @author birdylee <birdylee_cn@163.com>
     * 
     */
    public function generateToken(string $appid);

    /**
     * 
     * 设置token值
     * 
     * @param string $appid
     * @param string $token
     * @param string $sign 签名。RPC调用者，需要根据以下方法进行签名
     * <pre>
     * \Fayho\Services\Api\Toolkit::generateTokenSign()
     * </pre>
     * @param array $data
     *
     * @return array
     * <pre>
     * > 基础数据
     * 参数                       |      类型      |   描述
     * --------------------------|----------------|-------------------
     * expired_time              |    string      |  过期时间戳
     * data_value                |    array       |  token对应的所有数据
     * 
     * > data_value
     * 
     * </pre>
     *
     * @author birdylee <birdylee_cn@163.com>
     * 
     */
    public function setTokenData(string $appid, string $token, string $sign, array $data = []);

    /**
     * 
     * 设置token值
     * 
     * @param string $appid
     * @param string $token
     * @param string $sign 签名。RPC调用者，需要根据以下方法进行签名
     * <pre>
     * \Fayho\Services\Api\Toolkit::generateTokenSign()
     * </pre>
     *
     * @return array
     * <pre>
     * > 基础数据
     * 参数                       |      类型      |   描述
     * --------------------------|----------------|-------------------
     * expired_time              |    string      |  过期时间戳
     * data_value                |    array       |  token对应的所有数据
     * 
     * > data_value
     * 
     * </pre>
     *
     * @author birdylee <birdylee_cn@163.com>
     * 
     */
    public function getTokenData(string $appid, string $token, string $sign);

}