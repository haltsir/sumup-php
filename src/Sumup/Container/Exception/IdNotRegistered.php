<?php

namespace Sumup\Api\Container\Exception;

class IdNotRegistered extends \Exception
{
    /**
     * IdNotRegistered constructor.
     * @param mixed $id
     */
    public function __construct($id)
    {
        parent::__construct(sprintf('Id %s not registered.', $id));
    }
}
