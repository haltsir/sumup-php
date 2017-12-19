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
                'firstName' => 'testFirst',
                'lastName' => 'testLast',
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

        $this->assertEquals($personalProfileModel->firstName, 'testFirst');
        $this->assertEquals($personalProfileModel->lastName, 'testLast');
        $this->assertEquals($personalProfileModel->dateOfBirth, '01-01-2011');
        $this->assertEquals($personalProfileModel->mobilePhone, '0879803300');
        $this->assertEquals($personalProfileModel->nationalId, 'BG');
        $this->assertEquals($personalProfileModel->address->stateId, '10');

        $this->assertInstanceOf(Address::class, $personalProfileModel->address);
        $this->assertEquals($personalProfileModel->address->addressLine1, 'addressLine1');
        $this->assertEquals($personalProfileModel->address->addressLine2, 'addressLine2');
        $this->assertEquals($personalProfileModel->address->city, 'test_city');
        $this->assertEquals($personalProfileModel->address->country, 'US');
        $this->assertEquals($personalProfileModel->address->regionId, 1);
        $this->assertEquals($personalProfileModel->address->regionCode, 3000);
        $this->assertEquals($personalProfileModel->address->postCode, 3001);
        $this->assertEquals($personalProfileModel->address->landline, 1);
        $this->assertEquals($personalProfileModel->address->firstName, 'testFirst');
        $this->assertEquals($personalProfileModel->address->lastName, 'testLast');
        $this->assertEquals($personalProfileModel->address->company, 'testC');

        $this->assertInstanceOf(Country::class, $personalProfileModel->address->countryDetails);
        $this->assertEquals($personalProfileModel->address->countryDetails->currency, 'lev');
        $this->assertEquals($personalProfileModel->address->countryDetails->isoCode, 'BG');
        $this->assertEquals($personalProfileModel->address->countryDetails->enName, 'en_name_test');
        $this->assertEquals($personalProfileModel->address->countryDetails->nativeName, 'native_test');


        $this->assertInstanceOf(Timeoffset::class, $personalProfileModel->address->timeoffsetDetails);
        $this->assertEquals($personalProfileModel->address->timeoffsetDetails->postCode, '1000');
        $this->assertEquals($personalProfileModel->address->timeoffsetDetails->offset, 1);
        $this->assertEquals($personalProfileModel->address->timeoffsetDetails->dst, true);
    }
}
