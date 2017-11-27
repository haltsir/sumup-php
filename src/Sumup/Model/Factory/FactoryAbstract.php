<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Repository\Collection;

class FactoryAbstract implements FactoryInterface
{
    /**
     * @var
     */
    private $model;

    /**
     * @var
     */
    private $collection;

    public function create()
    {
        return clone $this->model;
    }

    public function collect(array $data)
    {
        /** @var Collection $collection */
        $collection = clone $this->collection;
        foreach ($data as $item) {
            $collection->attach($this->create()->hydrate($item));
        }

        return $collection;
    }
}
