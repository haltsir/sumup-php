<?php

namespace Tests\Unit\Model\Merchant;

use PHPUnit\Framework\TestCase;
use Sumup\Api\Model\Merchant\Profile;
use Sumup\Api\Model\Merchant\Address;
use Sumup\Api\Model\Merchant\Country;
use Sumup\Api\Model\Merchant\Timeoffset;

class PersonalProfileTest extends TestCase
{
    public function testShouldHydrateAddressModelWithMapArrayGiven()
    {
        $data = [
            'first_name' => 'testFirst',
            'last_name' => 'testLast',
            'date_of_birth' => '01-01-2011',
            'mobile_phone' => '0879803300',
            'national_id' => 'BG',
            'address' => [
                'address_line1' => 'addressLine1',
                'address_line2' => 'addressLine2',
                'city' => 'test_city',
                'country' => 'US',
                'region_id' => 1,
                'region_code' => 3000,
                'post_code' => 3001,
                'landline' => 1,
                'first_name' => 'testFirst',
                'last_name' => 'testLast',
                'company' => 'testC',
                'country_details' => [
                    'currency' => 'lev',
                    'iso_code' => 'BG',
                    'en_name' => 'en_name_test',
                    'native_name' => 'native_test'
                ],
                'timeoffset_details' => [
                    'post_code' => '1000',
                    'offset' => 1,
                    'dst' => true
                ],
                'state_id' => '10',
            ]
        ];

        $personalProfileModel = new Profile();
        $personalProfileModel->hydrate($data);

        $this->assertEquals('testFirst', $personalProfileModel->firstName);
        $this->assertEquals('testLast', $personalProfileModel->lastName);
        $this->assertEquals('01-01-2011', $personalProfileModel->dateOfBirth);
        $this->assertEquals('0879803300', $personalProfileModel->mobilePhone);
        $this->assertEquals('BG', $personalProfileModel->nationalId);
        $this->assertEquals('10', $personalProfileModel->address->stateId);

        $this->assertInstanceOf(Address::class, $personalProfileModel->address);
        $this->assertEquals('addressLine1', $personalProfileModel->address->addressLine1);
        $this->assertEquals('addressLine2', $personalProfileModel->address->addressLine2);
        $this->assertEquals('test_city', $personalProfileModel->address->city);
        $this->assertEquals('US', $personalProfileModel->address->country);
        $this->assertEquals(1, $personalProfileModel->address->regionId);
        $this->assertEquals(3000, $personalProfileModel->address->regionCode);
        $this->assertEquals(3001, $personalProfileModel->address->postCode);
        $this->assertEquals(1, $personalProfileModel->address->landline);
        $this->assertEquals('testFirst', $personalProfileModel->address->firstName);
        $this->assertEquals('testLast', $personalProfileModel->address->lastName);
        $this->assertEquals('testC', $personalProfileModel->address->company);

        $this->assertInstanceOf(Country::class, $personalProfileModel->address->countryDetails);
        $this->assertEquals('lev', $personalProfileModel->address->countryDetails->currency);
        $this->assertEquals('BG', $personalProfileModel->address->countryDetails->isoCode);
        $this->assertEquals('en_name_test', $personalProfileModel->address->countryDetails->enName);
        $this->assertEquals('native_test', $personalProfileModel->address->countryDetails->nativeName);


        $this->assertInstanceOf(Timeoffset::class, $personalProfileModel->address->timeoffsetDetails);
        $this->assertEquals( '1000', $personalProfileModel->address->timeoffsetDetails->postCode);
        $this->assertEquals(1, $personalProfileModel->address->timeoffsetDetails->offset);
        $this->assertEquals(true, $personalProfileModel->address->timeoffsetDetails->dst);
    }
}
