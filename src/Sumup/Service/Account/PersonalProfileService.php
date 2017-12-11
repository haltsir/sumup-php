<?php

namespace Sumup\Api\Service\Account;

use Psr\Http\Message\ResponseInterface;
use Sumup\Api\Configuration\ConfigurationInterface;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Merchant\Profile;
use Sumup\Api\Security\OAuth2\OAuthClientInterface;
use Sumup\Api\Service\Exception\InvalidArgumentException;
use Sumup\Api\Service\SumupService;

class PersonalProfileService extends SumupService
{

    /**
     * @var Profile
     */
    private $profileModel;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @var OAuthClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $requiredArgumentsValidator;

    const REQUIRED_ARGS = ["first_name", "last_name", "date_of_birth", "address"];

    /**
     * PersonalProfileService constructor.
     * @param Profile $profile
     * @param string $requiredArgumentsValidator
     * @param Request $request
     * @param ConfigurationInterface $configuration
     * @param OAuthClientInterface $client
     */
    public function __construct(Profile $profile, string $requiredArgumentsValidator,
                                Request $request,
                                ConfigurationInterface $configuration,
                                OAuthClientInterface $client)
    {

        $this->profileModel = $profile;
        $this->requiredArgumentsValidator = $requiredArgumentsValidator;
        $this->request = $request;
        $this->configuration = $configuration;
        $this->client = $client;
    }

    /**
     * Get Personal Profile
     * @return mixed
     */
    public function get()
    {
        $request = $this->request->setMethod('GET')
                                 ->setUri($this->configuration->getFullEndpoint() . '/me');

        /** @var ResponseInterface $response */
        $response = $this->client->request($request);

        return $this->profileModel->hydrate(json_decode((string)$response->getBody(), true));
    }

    /**
     * Create Personal Profile
     *
     * @param array $body
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function create(array $body)
    {
        $profile = $this->profileModel
            ->hydrate($body);

        if (false === $this->requiredArgumentsValidator::validate($body, self::REQUIRED_ARGS)) {
            throw new InvalidArgumentException('Missing required data provided to ' . __CLASS__);
        }

        $request = $this->request->setMethod('PUT')
                                 ->setUri($this->configuration->getFullEndpoint() .
                                          '/me/personal-profile')
                                 ->setJson($profile->serialize());

        $response = $this->client->request($request);
        return $profile->hydrate(json_decode((string)$response->getBody(), true));

    }

}
