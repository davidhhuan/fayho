<?php
/**
 * This file is part of Fayho.
 *
 * @link http://www.fayho.com
 */

namespace Fayho\Services\Store\Interfaces;

use Swoft\Core\ResultInterface;

/**
 * The interface of store service
 *
 * @method ResultInterface deferGetStores(array $ids)
 * @method ResultInterface deferGetStore(string $id)
 */
interface StoreInterface
{
    /**
     * @param array $ids
     *
     * @return array
     *
     * <pre>
     * [
     *    'uid' => [],
     *    'uid2' => [],
     *    ......
     * ]
     * <pre>
     */
    public function getStores(array $ids);

    /**
     * @param string $id
     *
     * @return array
     */
    public function getStore(string $id);

}