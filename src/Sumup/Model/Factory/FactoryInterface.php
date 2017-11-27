<?php

namespace Sumup\Api\Model\Factory;

interface FactoryInterface
{
    public function create();

    public function collect(array $data);
}
