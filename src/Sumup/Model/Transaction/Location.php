<?php

namespace Sumup\Api\Model\Transaction;

use Sumup\Api\Traits\HydratorTrait;

class Location
{
    use HydratorTrait;
    const MAP_JSON_TO_ENTITY = [
        'horizontalAccuracy' => ['path' => 'horizontal_accuracy']
        ];
    /**
     * @var float
     */
    public $lat;

    /**
     * @var float
     */
    public $lon;

    /**
     * @var float
     */
    public $horizontalAccuracy;
}
