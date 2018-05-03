<?php
/**
 * Created by PhpStorm.
 * User: birdylee
 * Date: 2018/5/3
 * Time: 14:46
 */

namespace Fayho\Services\Api;

/**
 * 常量定义
 *
 * Interface Constants
 * @package Fayho\Services\Api
 */
interface Constants
{
    const REQUEST_VALID_VERSION = 'Request-Valid-Version';

    const REQUEST_VALID_APPID = 'Request-Valid-Appid';

    const REQUEST_VALID_TOKEN = 'Request-Valid-Token';

    const REQUEST_VALID_SIGN = 'Request-Valid-Sign';

    const REQUEST_APP_INFO = __CLASS__ . 'app_info';
}