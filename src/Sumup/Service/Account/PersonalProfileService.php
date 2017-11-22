<?php

namespace Sumup\Api\Service\Account;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Merchant\Profile;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\SumupService;

class PersonalProfileService extends SumupService
{

    /**
     * @var Profile
     */
    protected $profileModel;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var OAuthClientInterface
     */
    protected $client;

    /**
     * PersonalProfileService constructor.
     * @param profile $profile
     * @param Request $request
     * @param ConfigurationInterface $configuration
     * @param OAuthClientInterface $client
     */
    public function __construct(Profile $profile, Request $request,
                                ConfigurationInterface $configuration, OAuthClientInterface $client)
    {
        $this->profileModel = $profile;
        $this->request = $request;
        $this->configuration = $configuration;
        $this->client = $client;
    }


    public function get()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me/personal-profile');

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->profileModel->hydrate(json_decode((string)$response->getBody(), true));
    }

//    public function update($body)
//    {
//        $request = (new Request())->setMethod('PUT')
//                                  ->setUri($this->configuration->getFullEndpoint() . '/me/personal-profile')
//                                  ->setBody($body);
//
//        $response = $this->client->request($request);
//    }
}
