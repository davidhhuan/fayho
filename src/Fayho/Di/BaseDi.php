<?php
/**
 * Created by PhpStorm.
 * User: birdylee
 * Date: 2018/5/3
 * Time: 14:34
 */

namespace Fayho\Di;

/**
 * Class BaseDi
 * @package Fayho\Di
 *
 * @author birdylee <birdylee_cn@163.com>
 * @since 2018.05.03
 */
abstract class BaseDi
{
    protected static $diList = null;

    /**
     * 抽象初始化注入
     *
     * @return BaseDi
     *
     */
    public abstract static function init();

    /**
     * 获取注入容器
     * @return mixed
     */
    public abstract static function getDi();

    /**
     * 重置写入对象
     *
     * @param string $name
     * @param $diVal
     * @throws \Exception
     *
     * @author birdylee <birdylee_cn@163.com>
     * @since 2018.05.03
     */
    protected function reset(string $name ,$diVal)
    {
        unset(static::$diList[$name]);
        $this->set($name, $diVal);
    }

    /**
     * 写入注入对象
     *
     * @param string $name
     * @param $diVal
     * @throws \Exception
     *
     * @author birdylee <birdylee_cn@163.com>
     * @since 2018.05.03
     */
    public function set(string $name ,$diVal)
    {
        if(isset(static::$diList[$name])){
            throw new \Exception('For your code safe,can not reset di name/value if exist！！!');
        }

        static::$diList[$name] = $diVal;
    }

    /**
     * 获取注入对象
     *
     * @param string $name
     * @return mixed
     *
     * @author birdylee <birdylee_cn@163.com>
     * @since 2018.05.03
     */
    public function get(string $name)
    {
        if(isset(static::$diList[$name])){
            return static::$diList[$name];
        }

        return null;
    }
}