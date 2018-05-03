<?php
/**
 * Created by PhpStorm.
 * User: birdylee
 * Date: 2018/5/3
 * Time: 14:38
 */

namespace Fayho\Di;

/**
 * Class FayhoDi
 * @package Fayho\Di
 *
 * @author birdylee <birdylee_cn@163.com>
 * @since 2018.05.03
 */
class FayhoDi extends BaseDi
{
    /**
     * @var BaseDi
     */
    protected static $instance;

    protected static $diList = [];

    /**
     * @inheritDoc
     *
     */
    public static function init()
    {
        if(empty(static::$instance)){
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @inheritDoc
     */
    public static function getDi()
    {
        return static::init();
    }
}