<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Services\Api\Fallback;

use Fayho\Services\Api\Interfaces\ApiInterface;
use Swoft\Sg\Bean\Annotation\Fallback;
use Swoft\Core\ResultInterface;

/**
 * Class ApiFallback
 * @package Fayho\Services\Api\Fallback
 *
 * @Fallback("apiFallback")
 *
 * @method ResultInterface deferGetApp(string $appid)
 * @method ResultInterface deferIsTokenOk(string $appid, string $token)
 * @method ResultInterface deferGenerateToken(string $appid)
 * @method ResultInterface deferSetTokenData(string $appid, string $token, array $data = [])
 * @method ResultInterface deferGetTokenData(string $appid, string $token)
 *
 * @author birdylee <birdylee_cn@163.com>
 * @since 2018.05.03
 */
class ApiFallback implements ApiInterface
{

    /**
     * @inheritDoc
     */
    public function getApp(string $appid)
    {
        return ['fallback', 'getApp', func_get_args()];
    }

    /**
     * @inheritDoc
     */
    public function isTokenOk(string $appid, string $token)
    {
        return ['fallback', 'isTokenOk', func_get_args()];
    }

    /**
     * @inheritDoc
     */
    public function generateToken(string $appid)
    {
        return ['fallback', 'generateToken', func_get_args()];
    }

    /**
     * @inheritDoc
     */
    public function setTokenData(string $appid, string $token, array $data = [])
    {
        return ['fallback', 'setTokenData', func_get_args()];
    }

    /**
     * @inheritDoc
     */
    public function getTokenData(string $appid, string $token)
    {
        return ['fallback', 'getTokenData', func_get_args()];
    }
}