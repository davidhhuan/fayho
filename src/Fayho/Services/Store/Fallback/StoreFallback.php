<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Services\Store\Fallback;

use Swoft\Sg\Bean\Annotation\Fallback;
use Swoft\Core\ResultInterface;
use Fayho\Services\Store\Interfaces\StoreInterface;

/**
 * Class StoreFallback
 * @package Fayho\Services\Store\Fallback
 *
 * @Fallback("storeFallback")
 *
 * @method ResultInterface deferGetStores(array $ids)
 * @method ResultInterface deferGetStore(string $id)
 *
 * @author birdylee <birdylee_cn@163.com>
 * @since 2018.05.03
 */
class StoreFallback implements StoreInterface
{

    /**
     * @inheritDoc
     */
    public function getStores(array $ids)
    {
        return ['fallback', 'getStores', func_get_args()];
    }

    /**
     * @inheritDoc
     */
    public function getStore(string $id)
    {
        return ['fallback', 'getStore', func_get_args()];
    }
}