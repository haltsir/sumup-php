<?php

use Sumup\Api\Model\Merchant\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testShouldHydrateAddressModelWithMapArrayGiven()
    {
        $data = [
            'address_line1' => 'addressLine1',
            'address_line2' => 'addressLine2',
            'city' => 'test_city',
            'country' => 'US',
            'region_id' => 1,
            'region_code' => 3000,
            'post_code' => 3001,
            'landline' => 1,
            'firstName' => 'testFirst',
            'lastName' => 'testLast',
            'company' => 'testC',
            'countryDetails' => 'testDetail',
            'timeoffsetDetails' => 1,
            'stateId' => 10
        ];

        $addressModel = new Address();
        $addressModel->hydrate($data);

        $this->assertEquals($addressModel->addressLine1, 'addressLine1');
        $this->assertEquals($addressModel->addressLine2, 'addressLine2');
        $this->assertEquals($addressModel->city, 'test_city');
        $this->assertEquals($addressModel->country, 'US');
        $this->assertEquals($addressModel->regionId, 1);
        $this->assertEquals($addressModel->regionCode, 3000);
        $this->assertEquals($addressModel->postCode, 3001);
        $this->assertEquals($addressModel->landline, 1);
        $this->assertEquals($addressModel->firstName, 'testFirst');
        $this->assertEquals($addressModel->lastName, 'testLast');
        $this->assertEquals($addressModel->company, 'testC');
        $this->assertEquals($addressModel->countryDetails, 'testDetail');
        $this->assertEquals($addressModel->timeoffsetDetails, 1);
        $this->assertEquals($addressModel->stateId, 10);
    }
}
