<?php

namespace Sumup\Api\Model\Factory;

use Sumup\Api\Repository\Collection;

class FactoryAbstract implements FactoryInterface
{
    /**
     * @var
     */
    protected $model;

    /**
     * @var
     */
    protected $collection;

    /**
     * Create new model.
     *
     * @return mixed
     */
    public function create()
    {
        return clone $this->model;
    }

    /**
     * Create and hydrate multiple entities.
     *
     * @param array $data
     * @return Collection
     */
    public function collect(array $data)
    {
        /** @var Collection $collection */
        $collection = clone $this->collection;
        foreach ($data as $key => $item) {
            $collection[$key] = $this->create()->hydrate($item);
        }

        return $collection;
    }
}
