<?php

namespace Sumup\Api;

use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Container\ContainerInterface;
use Sumup\Api\Cache\File\FileCacheItemPool;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Container\Exception\ContainerException;
use Sumup\Api\Container\Exception\NotFoundException;
use Sumup\Api\Model\Factory\ShelfFactory;
use Sumup\Api\Model\Merchant\Account;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Merchant\Merchant;
use Sumup\Api\Model\Merchant\Profile;
use Sumup\Api\Model\Product\Shelf;
use Sumup\Api\Security\Factory\OAuthClientFactory;
use Sumup\Api\Service\Account\AccountService;
use Sumup\Api\Service\Merchant\MerchantProfileService;
use Sumup\Api\Service\Account\PersonalProfileService;
use Sumup\Api\Service\Merchant\ShelfService;
use Sumup\Api\Validator\AllowedArgumentsValidator;
use Sumup\Api\Validator\RequiredArgumentsValidator;

class SumupContainer extends Container implements ContainerInterface
{
    public function __construct()
    {
        parent::__construct();

        $this['api.endpoint'] = 'https://api.sumup.com';
        $this['api.endpoint'] = 'v0.1';
        $this['cache.file.path'] = sys_get_temp_dir();

        /* HTTP */
        $this['http.client'] = $this->factory(function () {
            return new Client();
        });
        $this['http.request'] = $this->factory(function ($container) {
            return new Request($container['http.client']);
        });

        /* Validators */
        $this['validator.allowed_arguments'] = function () {
            return AllowedArgumentsValidator::class;
        };
        $this['validator.required_arguments'] = function () {
            return RequiredArgumentsValidator::class;
        };

        $this['configuration'] = function () {
            return new Configuration();
        };

        $this['cache.pool'] = function ($container) {
            return new FileCacheItemPool($container['cache.file.path']);
        };

        $this['oauth.factory.client'] = $this->factory(function () {
            return new OAuthClientFactory();
        });
        $this['oauth.client'] = $this->factory(function ($container) {
            return $this['oauth.factory.client']->create($container['configuration'], $container['http.client'],
                                                         $container['cache.pool']);
        });

        $this['collection'] = $this->factory(function () {
            return new Repository\Collection();
        });

        /* Account */
        $this['account.model'] = $this->factory(function () {
            return new Account();
        });
        $this['account.service'] = $this->factory(function ($container) {
            return new AccountService($container['configuration'], $container['oauth.client'],
                                      $container['http.request'], $container['account.model'],
                                      $container['validator.allowed_arguments']);
        });

        /* Merchant Profile */
        $this['merchant.model'] = $this->factory(function () {
            return new Merchant;
        });
        $this['merchant.profile.service'] = $this->factory(function ($container) {
            return new MerchantProfileService($container['configuration'], $container['oauth.client'],
                                              $container['http.request'], $container['merchant.model']);
        });

        /* Personal Profile */
        $this['profile.model'] = $this->factory(function () {
            return new Profile();
        });
        $this['personal_profile.service'] = $this->factory(function ($container) {
            return new PersonalProfileService($container['profile.model'], $container['http.request'],
                                              $container['configuration'], $container['oauth.client']);
        });

        /* Shelves */
        $this['shelf.model'] = $this->factory(function () {
            return new Shelf;
        });
        $this['shelf.factory'] = $this->factory(function($container) {
            return new ShelfFactory($container['shelf.model'], $container['collection']);
        });
        $this['shelf.service'] = $this->factory(function ($container) {
            return new ShelfService($container['configuration'], $container['oauth.client'],
                                    $container['http.request'], $container['validator.allowed_arguments'],
                                    $container['collection'], $container['shelf.factory']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!isset($this[$id])) {
            throw new NotFoundException();
        }

        try {
            return $this[$id];
        } catch (\Exception $e) {
            throw new ContainerException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return isset($this[$id]);
    }
}
