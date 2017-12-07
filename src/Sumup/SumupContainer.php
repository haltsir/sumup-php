<?php

namespace Sumup\Api;

use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Container\ContainerInterface;
use Sumup\Api\Cache\File\FileCacheItemPool;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Container\Exception\ContainerException;
use Sumup\Api\Container\Exception\NotFoundException;
use Sumup\Api\Model\Operator\Operator;
use Sumup\Api\Model\Factory\PriceFactory;
use Sumup\Api\Model\Factory\ProductFactory;
use Sumup\Api\Model\Factory\ShelfFactory;
use Sumup\Api\Model\Factory\OperatorFactory;
use Sumup\Api\Model\Merchant\Account;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Merchant\Business;
use Sumup\Api\Model\Merchant\Merchant;
use Sumup\Api\Model\Merchant\Profile;
use Sumup\Api\Model\Product\Price;
use Sumup\Api\Model\Product\Product;
use Sumup\Api\Model\Product\Shelf;
use Sumup\Api\Security\Factory\OAuthClientFactory;
use Sumup\Api\Service\Account\AccountService;
use Sumup\Api\Service\Merchant\BusinessService;
use Sumup\Api\Service\Account\OperatorService;
use Sumup\Api\Service\Merchant\MerchantProfileService;
use Sumup\Api\Service\Account\PersonalProfileService;
use Sumup\Api\Service\Merchant\PriceService;
use Sumup\Api\Service\Merchant\ProductService;
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
        $this['shelf.factory'] = $this->factory(function ($container) {
            return new ShelfFactory($container['shelf.model'], $container['collection']);
        });
        $this['shelf.service'] = $this->factory(function ($container) {
            return new ShelfService($container['configuration'], $container['oauth.client'],
                                    $container['http.request'], $container['validator.allowed_arguments'],
                                    $container['validator.required_arguments'], $container['shelf.factory']);
        });

        /* Product */
        $this['product.model'] = $this->factory(function () {
            return new Product();
        });
        $this['product.factory'] = $this->factory(function ($container) {
            return new ProductFactory($container['product.model'], $container['collection']);
        });
        $this['product.service'] = $this->factory(function ($container) {
            return new ProductService($container['configuration'], $container['oauth.client'],
                                      $container['http.request'], $container['validator.required_arguments'],
                                      $container['product.factory']);
        });

        /* Price */
        $this['price.model'] = $this->factory(function () {
            return new Price();
        });
        $this['price.factory'] = $this->factory(function ($container) {
            return new PriceFactory($container['price.model'], $container['collection']);
        });
        $this['price.service'] = $this->factory(function ($container) {
            return new PriceService($container['configuration'], $container['oauth.client'],
                                    $container['http.request'], $container['validator.required_arguments'],
                                    $container['price.factory']);
        });

        /* Business */
        $this['business.model'] = $this->factory(function () {
            return new Business();
        });
        $this['business.service'] = $this->factory(function ($container) {
            return new BusinessService($container['configuration'], $container['oauth.client'],
                                       $container['http.request'], $container['validator.required_arguments'],
                                       $container['business.model']);
        });

        /* Operator */
        $this['operator.model'] = $this->factory(function () {
            return new Operator();
        });

        $this['operator.factory'] = $this->factory(function ($container) {
            return new OperatorFactory($container['operator.model'], $container['collection']);
        });

        $this['operator.service'] = $this->factory(function ($container) {
            return new OperatorService($container['operator.factory'],
                                         $container['collection'],
                                         $container['validator.required_arguments'],
                                         $container['http.request'],
                                         $container['configuration'], $container['oauth.client']);
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
