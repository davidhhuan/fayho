<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

 namespace Fayho\Services\Breaker;

use Swoft\Sg\Bean\Annotation\Breaker;
use Swoft\Bean\Annotation\Value;
use Swoft\Sg\Circuit\CircuitBreaker;

/**
 * the breaker of user
 *
 * @Breaker("store")
 */
class StoreBreaker extends CircuitBreaker
{
    /**
     * The number of successive failures
     * If the arrival, the state switch to open
     *
     * @Value(name="${config.breaker.store.failCount}", env="${STORE_BREAKER_FAIL_COUNT}")
     * @var int
     */
    protected $switchToFailCount = 3;

    /**
     * The number of successive successes
     * If the arrival, the state switch to close
     *
     * @Value(name="${config.breaker.store.successCount}", env="${STORE_BREAKER_SUCCESS_COUNT}")
     * @var int
     */
    protected $switchToSuccessCount = 3;

    /**
     * Switch close to open delay time
     * The unit is milliseconds
     *
     * @Value(name="${config.breaker.store.delayTime}", env="${STORE_BREAKER_DELAY_TIME}")
     * @var int
     */
    protected $delaySwitchTimer = 500;
}