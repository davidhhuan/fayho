<?php
/**
 * Created by PhpStorm.
 * User: birdylee
 * Date: 2018/5/2
 * Time: 23:52
 */

namespace Fayho\Base;

use Fayho\Util\Response;
use Swoft\Contract\Arrayable;

/**
 * Class Result
 * @package Fayho\Base
 *
 * @author birdylee <birdylee_cn@163.com>
 * @since 2018.05.02
 */
class Result implements \JsonSerializable, \Serializable, \ArrayAccess, Arrayable
{
    /**
     * 状态码
     *
     * @var string
     */
    private $status;

    /**
     * 状态提示语
     *
     * @var string
     */
    private $msg;

    /**
     * 结果
     *
     * @var mixed
     */
    private $result;

    /**
     * Result constructor.
     * @param $status
     * @param $msg 当$status不为null，$msg为null时，直接获取StatusCode对应的msg，也就是说,可以不传$msg这个参数
     * @param $result
     */
    public function __construct($status = null, $msg = null, $result = null)
    {
        !is_null($status) && $this->status = $status;
        !is_null($msg) && $this->msg = $msg;
        !is_null($result) && $this->result = $result;
        if (!is_null($status) && is_null($msg)) {
            $this->msg = StatusCode::getMsg($status);
        }
    }

    /**
     * @param array $rs
     * @return $this
     *
     * @author birdylee <birdylee_cn@163.com>
     * @since 2018.05.03
     */
    public function setData(array $rs)
    {
        isset($rs['status']) && $this->status = $rs['status'];
        isset($rs['msg']) && $this->msg = $rs['msg'];
        isset($rs['result']) && $this->result = $rs['result'];
        if (isset($rs['status']) && !isset($rs['msg'])) {
            $this->msg = StatusCode::getMsg($rs['status']);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     *
     * @author birdylee <birdylee_cn@163.com>
     * @since 2018.05.03
     */
    public function isSucceed()
    {
        return $this->status == StatusCode::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string
    {
        return $this->toJson();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @inheritDoc
     */
    public function serialize(): string
    {
//        return \serialize([$this->status, $this->msg, $this->result]);
        return $this->toJson();
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
//        list(
//            $this->status,
//            $this->msg,
//            $this->result
//            ) = \unserialize($serialized, ['allowed_classes' => false]);
        list(
            $this->status,
            $this->msg,
            $this->result
            ) = json_decode($serialized, true);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        $data = $this->toArray();

        return isset($data[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        $data  = $this->toArray();

        return $data[$offset]??null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->fill([$offset => $value]);
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return Response::returnJson($this->status, $this->msg, $this->result);
    }

    /**
     * @param array $attributes
     * $attributes = [
     *     'name' => $value
     * ]
     * @return Result
     *
     * @author birdylee <birdylee_cn@163.com>
     * @since 2018.05.03
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $name => $value) {
            $methodName = sprintf('set%s', ucfirst($name));
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        $this->fill($offset, null);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return compact($this->status, $this->msg, $this->result);
    }
}