<?php

use PHPUnit\Framework\TestCase;

class HydratorTraitTest extends TestCase
{
    /**
     * @dataProvider accountDataProvider
     */
    public function testFirstLevelHydrator($data)
    {
//        $address = (new Address())->hydrate();
        $mock = $this->getMockForTrait(\Sumup\Api\Traits\HydratorTrait::class);
        $mock->expects($this->any())
             ->method('hydrate')
             ->with([$data])
             ->will($this->returnValue('dummy'));
    }

    public function accountDataProvider()
    {
        return ['address_line1' => 'Test address line 1', 'city' => 'Test City', 'country' => 'BG',
                'post_code' => '1000'];
    }
}
