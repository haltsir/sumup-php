<?php

namespace Sumup\Api;

use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Container\ContainerInterface;
use Sumup\Api\Cache\File\FileCacheItemPool;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Container\Exception\ContainerException;
use Sumup\Api\Container\Exception\NotFoundException;
use Sumup\Api\Error\ApiError;
use Sumup\Api\Error\ApiErrorContainer;
use Sumup\Api\Http\Exception\Factory\RequestExceptionFactory;
use Sumup\Api\Model\Factory\CompletedCheckoutFactory;
use Sumup\Api\Model\Factory\TransactionFactory;
use Sumup\Api\Model\Factory\TransactionHistoryFactory;
use Sumup\Api\Model\Factory\TransactionItemFactory;
use Sumup\Api\Model\Merchant\Me;
use Sumup\Api\Model\Checkout\Checkout;
use Sumup\Api\Model\Checkout\CompletedCheckout;
use Sumup\Api\Model\Factory\CheckoutFactory;
use Sumup\Api\Model\Operator\Operator;
use Sumup\Api\Model\Factory\BankAccountFactory;
use Sumup\Api\Model\Factory\PriceFactory;
use Sumup\Api\Model\Factory\ProductFactory;
use Sumup\Api\Model\Factory\ShelfFactory;
use Sumup\Api\Model\Factory\OperatorFactory;
use Sumup\Api\Http\Request;
use Sumup\Api\Model\Payout\BankAccount;
use Sumup\Api\Model\Merchant\Business;
use Sumup\Api\Model\Merchant\Merchant;
use Sumup\Api\Model\Merchant\Profile;
use Sumup\Api\Model\Payout\Settings;
use Sumup\Api\Model\Product\Price;
use Sumup\Api\Model\Product\Product;
use Sumup\Api\Model\Product\Shelf;
use Sumup\Api\Model\Transaction\Transaction;
use Sumup\Api\Model\Transaction\TransactionHistory;
use Sumup\Api\Model\Transaction\TransactionItem;
use Sumup\Api\Security\Factory\OAuthClientFactory;
use Sumup\Api\Service\Account\AccountService;
use Sumup\Api\Service\App\AppSettingsService;
use Sumup\Api\Service\Checkout\CheckoutService;
use Sumup\Api\Service\Payout\BankAccountService;
use Sumup\Api\Service\Merchant\BusinessService;
use Sumup\Api\Service\Account\OperatorService;
use Sumup\Api\Service\Merchant\MerchantProfileService;
use Sumup\Api\Service\Account\PersonalProfileService;
use Sumup\Api\Service\Merchant\PriceService;
use Sumup\Api\Service\Merchant\ProductService;
use Sumup\Api\Service\Merchant\ShelfService;
use Sumup\Api\Service\Payout\SettingsService;
use Sumup\Api\Service\Transaction\TransactionService;
use Sumup\Api\Validator\AllowedArgumentsValidator;
use Sumup\Api\Validator\RequiredArgumentsValidator;
use Sumup\Api\Model\Mobile\Settings as AppSettings;

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

        $this['api_error'] = $this->factory(function () {
            return new ApiError();
        });

        $this['api_error_container'] = $this->factory(function () {
            return new ApiErrorContainer();
        });

        $this['request_exception_factory'] = $this->factory(function ($container) {
            return new RequestExceptionFactory($container['api_error'], $container['api_error_container']);
        });

        $this['http.request'] = $this->factory(function ($container) {
            return new Request($container['http.client'], $container['request_exception_factory']);
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
        $this['me.model'] = $this->factory(function () {
            return new Me();
        });
        $this['account.service'] = $this->factory(function ($container) {
            return new AccountService($container['configuration'], $container['oauth.client'],
                                      $container['http.request'], $container['me.model'],
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
            return new PersonalProfileService($container['profile.model'], $container['validator.required_arguments'],
                                              $container['http.request'],
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
            return new OperatorService($container['operator.factory'], $container['collection'],
                                       $container['validator.required_arguments'], $container['http.request'],
                                       $container['configuration'], $container['oauth.client']);
        });

        /* Bank Account */
        $this['bank_account.model'] = $this->factory(function () {
            return new BankAccount();
        });
        $this['bank_account.factory'] = $this->factory(function ($container) {
            return new BankAccountFactory($container['bank_account.model'], $container['collection']);
        });
        $this['bank_account.service'] = $this->factory(function ($container) {
            return new BankAccountService($container['configuration'], $container['oauth.client'],
                                          $container['http.request'], $container['bank_account.factory'],
                                          $container['validator.required_arguments']);
        });

        /* Payout Settings */
        $this['payout.settings.model'] = $this->factory(function () {
            return new Settings();
        });
        $this['payout.settings.service'] = $this->factory(function ($container) {
            return new SettingsService($container['configuration'], $container['oauth.client'],
                                       $container['http.request'], $container['payout.settings.model']);
        });

        /* App Settings */
        $this['app.settings.model'] = $this->factory(function () {
            return new AppSettings();

        });
        $this['app.settings.service'] = $this->factory(function ($container) {
            return new AppSettingsService($container['configuration'], $container['oauth.client'],
                                          $container['http.request'], $container['app.settings.model']);
        });

        /* Checkout */
        $this['checkout.model'] = $this->factory(function () {
            return new Checkout();
        });
        $this['checkout.completed_checkout.model'] = $this->factory(function () {
            return new CompletedCheckout();
        });
        $this['checkout.factory'] = $this->factory(function ($container) {
            return new CheckoutFactory($container['checkout.model'], $container['collection']);
        });
        $this['checkout.completed_checkout.factory'] = $this->factory(function ($container) {
            return new CompletedCheckoutFactory($container['checkout.completed_checkout.model'],
                                                $container['collection']);
        });
        $this['checkout.service'] = $this->factory(function ($container) {
            return new CheckoutService($container['configuration'], $container['oauth.client'],
                                       $container['http.request'], $container['checkout.factory'],
                                       $container['checkout.completed_checkout.factory'],
                                       $container['validator.required_arguments']);
        });

        /* Transaction */
        $this['transaction.history.model'] = $this->factory(function () {
            return new TransactionHistory();
        });
        $this['transaction.history.factory'] = function ($container) {
            return new TransactionHistoryFactory($container['transaction.history.model'], $container['collection']);
        };
        $this['transaction.item.model'] = $this->factory(function () {
            return new TransactionItem();
        });
        $this['transaction.item.factory'] = function ($container) {
            return new TransactionItemFactory($container['transaction.item.model'], $container['collection']);
        };
        $this['transaction.model'] = $this->factory(function () {
            return new Transaction();
        });
        $this['transaction.factory'] = function ($container) {
            return new TransactionFactory($container['transaction.model'], $container['collection']);
        };
        $this['transaction.service'] = $this->factory(function ($container) {
            return new TransactionService($container['configuration'], $container['oauth.client'],
                                          $container['http.request'], $container['transaction.factory'],
                                          $container['transaction.history.factory'],
                                          $container['validator.allowed_arguments']);
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
