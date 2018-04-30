<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

 namespace Fayho\Services\Store\Pool;

use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Pool;
use Fayho\Services\Store\Pool\Config\StorePoolConfig;
use Swoft\Rpc\Client\Pool\ServicePool;

/**
 * the pool of user service
 *
 * @Pool(name="store")
 */
class StoreServicePool extends ServicePool
{
    /**
     * @Inject()
     *
     * @var StorePoolConfig
     */
    protected $poolConfig;
}