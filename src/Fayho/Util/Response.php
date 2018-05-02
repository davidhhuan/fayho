<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Util;

use Fayho\Util\Arrays;

/**
 * 响应请求工具类
 * 
 * @author birdylee <170915870@qq.com>
 * @version    1.0
 */
class Response
{
    /**
     * 返回json值
     *
     * @param $status
     * @param $msg
     * @param array $result
     *
     * @author birdylee <birdylee_cn@163.com>
     */
    public static function returnJson($status, $msg, $result = []): string
    {
        $json = json_encode(
            [
                'status' => $status,
                'msg' => $msg,
                'result' => $result,
            ],
            JSON_UNESCAPED_UNICODE
        );
        $json = Arrays::toString($json);

        return $json;
    }

}