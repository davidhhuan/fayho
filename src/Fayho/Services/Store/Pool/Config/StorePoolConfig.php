<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Services\Store\Pool\Config;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Pool\PoolProperties;

/**
 * the config of service user
 *
 * @Bean()
 */
class StorePoolConfig extends PoolProperties
{
    /**
     * the name of pool
     *
     * @Value(name="${config.service.store.name}", env="${STORE_POOL_NAME}")
     * @var string
     */
    protected $name = '';

    /**
     * Minimum active number of connections
     *
     * @Value(name="${config.service.store.minActive}", env="${STORE_POOL_MIN_ACTIVE}")
     * @var int
     */
    protected $minActive = 5;

    /**
     * the maximum number of active connections
     *
     * @Value(name="${config.service.store.maxActive}", env="${STORE_POOL_MAX_ACTIVE}")
     * @var int
     */
    protected $maxActive = 50;

    /**
     * the maximum number of wait connections
     *
     * @Value(name="${config.service.store.maxWait}", env="${STORE_POOL_MAX_WAIT}")
     * @var int
     */
    protected $maxWait = 100;

    /**
     * Maximum waiting time
     *
     * @Value(name="${config.service.store.maxWaitTime}", env="${STORE_POOL_MAX_WAIT_TIME}")
     * @var int
     */
    protected $maxWaitTime = 3;

    /**
     * Maximum idle time
     *
     * @Value(name="${config.service.store.maxIdleTime}", env="${STORE_POOL_MAX_IDLE_TIME}")
     * @var int
     */
    protected $maxIdleTime = 60;

    /**
     * the time of connect timeout
     *
     * @Value(name="${config.service.store.timeout}", env="${STORE_POOL_TIMEOUT}")
     * @var int
     */
    protected $timeout = 200;

    /**
     * the addresses of connection
     *
     * <pre>
     * [
     *  '127.0.0.1:88',
     *  '127.0.0.1:88'
     * ]
     * </pre>
     *
     * @Value(name="${config.service.store.uri}", env="${STORE_POOL_URI}")
     * @var array
     */
    protected $uri = [];

    /**
     * whether to user provider(consul/etcd/zookeeper)
     *
     * @Value(name="${config.service.store.useProvider}", env="${STORE_POOL_USE_PROVIDER}")
     * @var bool
     */
    protected $useProvider = false;

    /**
     * the default balancer is random balancer
     *
     * @Value(name="${config.service.store.balancer}", env="${STORE_POOL_BALANCER}")
     * @var string
     */
    protected $balancer = '';

    /**
     * the default provider is consul provider
     *
     * @Value(name="${config.service.store.provider}", env="${STORE_POOL_PROVIDER}")
     * @var string
     */
    protected $provider = '';
}