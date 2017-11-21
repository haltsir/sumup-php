<?php

namespace Sumup\Api\Container;

use Sumup\Api\Container\Exception\IdNotRegistered;

class Container implements \ArrayAccess
{
    protected $register = [];

    /**
     * {@inheritdoc}
     */
    public function offsetExists($id)
    {
        return isset($this->register[$id]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($id)
    {
        if (false === $this->offsetExists($id)) {
            throw new IdNotRegistered($id);
        }

        return method_exists($this, $id) ? $this->register[$id]() : $this->register[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($id, $value)
    {
        $this->register[$id] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($id)
    {
        if (false === $this->offsetExists($id)) {
            throw new IdNotRegistered($id);
        }
        unset($this->register[$id]);
    }
}
