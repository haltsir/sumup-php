<?php

namespace Sumup\Api;

use GuzzleHttp\Client;
use Pimple\Container;
use Psr\Container\ContainerInterface;
use Sumup\Api\Cache\File\FileCacheItemPool;
use Sumup\Api\Configuration\Configuration;
use Sumup\Api\Container\Exception\ContainerException;
use Sumup\Api\Container\Exception\NotFoundException;
use Sumup\Api\Exception\ApiError;
use Sumup\Api\Exception\ApiErrorContainer;
use Sumup\Api\Http\Exception\Factory\RequestExceptionFactory;
use Sumup\Api\Model\Customer\PaymentInstrument;
use Sumup\Api\Model\Factory\CompletedCheckoutFactory;
use Sumup\Api\Model\Factory\CustomerFactory;
use Sumup\Api\Model\Factory\RefundFactory;
use Sumup\Api\Model\Factory\TransactionFactory;
use Sumup\Api\Model\Factory\TransactionHistoryFactory;
use Sumup\Api\Model\Factory\TransactionItemFactory;
use Sumup\Api\Model\Factory\PaymentInstrumentFactory;
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
use Sumup\Api\Model\Transaction\Receipt;
use Sumup\Api\Model\Transaction\Refund;
use Sumup\Api\Model\Transaction\Transaction;
use Sumup\Api\Model\Transaction\TransactionHistory;
use Sumup\Api\Model\Transaction\TransactionItem;
use Sumup\Api\Security\Factory\OAuthClientFactory;
use Sumup\Api\Service\Account\AccountService;
use Sumup\Api\Service\App\AppSettingsService;
use Sumup\Api\Service\Checkout\CheckoutService;
use Sumup\Api\Service\Customer\CustomerService;
use Sumup\Api\Service\Customer\PaymentInstrumentService;
use Sumup\Api\Service\Payout\BankAccountService;
use Sumup\Api\Service\Merchant\BusinessService;
use Sumup\Api\Service\Account\OperatorService;
use Sumup\Api\Service\Merchant\MerchantProfileService;
use Sumup\Api\Service\Account\PersonalProfileService;
use Sumup\Api\Service\Merchant\PriceService;
use Sumup\Api\Service\Merchant\ProductService;
use Sumup\Api\Service\Merchant\ShelfService;
use Sumup\Api\Service\Payout\SettingsService;
use Sumup\Api\Service\Transaction\ReceiptService;
use Sumup\Api\Service\Transaction\RefundService;
use Sumup\Api\Service\Transaction\TransactionService;
use Sumup\Api\Validator\AllowedArgumentsValidator;
use Sumup\Api\Validator\RequiredArgumentsValidator;
use Sumup\Api\Model\Mobile\Settings as AppSettings;
use Sumup\Api\Model\Customer\Customer;

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

        $this['request_exception_factory'] = $this->factory(function (ContainerInterface $container) {
            return new RequestExceptionFactory($container->get('api_error'), $container->get('api_error_container'));
        });

        $this['http.request'] = $this->factory(function (ContainerInterface $container) {
            return new Request($container->get('http.client'), $container->get('request_exception_factory'));
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

        $this['cache.pool'] = function (ContainerInterface $container) {
            return new FileCacheItemPool($container->get('cache.file.path'));
        };

        $this['oauth.factory.client'] = $this->factory(function () {
            return new OAuthClientFactory();
        });
        $this['oauth.client'] = $this->factory(function (ContainerInterface $container) {
            return $this['oauth.factory.client']->create(
                $container->get('configuration'),
                $container->get('http.client'),
                $container->get('cache.pool')
            );
        });

        $this['collection'] = $this->factory(function () {
            return new Repository\Collection();
        });

        /* Account */
        $this['me.model'] = $this->factory(function () {
            return new Me();
        });
        $this['account.service'] = $this->factory(function (ContainerInterface $container) {
            return new AccountService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('me.model'),
                $container->get('validator.allowed_arguments')
            );
        });

        /* Merchant Profile */
        $this['merchant.model'] = $this->factory(function () {
            return new Merchant;
        });
        $this['merchant.profile.service'] = $this->factory(function (ContainerInterface $container) {
            return new MerchantProfileService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('merchant.model')
            );
        });

        /* Personal Profile */
        $this['profile.model'] = $this->factory(function () {
            return new Profile();
        });
        $this['profile.service'] = $this->factory(function (ContainerInterface $container) {
            return new PersonalProfileService(
                $container->get('profile.model'),
                $container->get('validator.required_arguments'),
                $container->get('http.request'),
                $container->get('configuration'),
                $container->get('oauth.client')
            );
        });

        /* Shelves */
        $this['shelf.model'] = $this->factory(function () {
            return new Shelf;
        });
        $this['shelf.factory'] = $this->factory(function (ContainerInterface $container) {
            return new ShelfFactory($container->get('shelf.model'), $container->get('collection'));
        });
        $this['shelf.service'] = $this->factory(function (ContainerInterface $container) {
            return new ShelfService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('validator.allowed_arguments'),
                $container->get('validator.required_arguments'),
                $container->get('shelf.factory'));
        });

        /* Product */
        $this['product.model'] = $this->factory(function () {
            return new Product();
        });
        $this['product.factory'] = $this->factory(function (ContainerInterface $container) {
            return new ProductFactory($container->get('product.model'), $container->get('collection'));
        });
        $this['product.service'] = $this->factory(function (ContainerInterface $container) {
            return new ProductService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('validator.required_arguments'),
                $container->get('product.factory')
            );
        });

        /* Price */
        $this['price.model'] = $this->factory(function () {
            return new Price();
        });
        $this['price.factory'] = $this->factory(function (ContainerInterface $container) {
            return new PriceFactory($container->get('price.model'), $container->get('collection'));
        });
        $this['price.service'] = $this->factory(function (ContainerInterface $container) {
            return new PriceService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('validator.required_arguments'),
                $container->get('price.factory')
            );
        });

        /* Business */
        $this['business.model'] = $this->factory(function () {
            return new Business();
        });
        $this['business.service'] = $this->factory(function (ContainerInterface $container) {
            return new BusinessService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('validator.required_arguments'),
                $container->get('business.model')
            );
        });

        /* Operator */
        $this['operator.model'] = $this->factory(function () {
            return new Operator();
        });

        $this['operator.factory'] = $this->factory(function (ContainerInterface $container) {
            return new OperatorFactory($container->get('operator.model'), $container->get('collection'));
        });

        $this['operator.service'] = $this->factory(function (ContainerInterface $container) {
            return new OperatorService(
                $container->get('operator.factory'),
                $container->get('collection'),
                $container->get('validator.required_arguments'),
                $container->get('http.request'),
                $container->get('configuration'),
                $container->get('oauth.client')
            );
        });

        /* Bank Account */
        $this['bank_account.model'] = $this->factory(function () {
            return new BankAccount();
        });
        $this['bank_account.factory'] = $this->factory(function (ContainerInterface $container) {
            return new BankAccountFactory($container->get('bank_account.model'), $container->get('collection'));
        });
        $this['bank_account.service'] = $this->factory(function (ContainerInterface $container) {
            return new BankAccountService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('bank_account.factory'),
                $container->get('validator.required_arguments')
            );
        });

        /* Payout Settings */
        $this['payout.settings.model'] = $this->factory(function () {
            return new Settings();
        });
        $this['payout.settings.service'] = $this->factory(function (ContainerInterface $container) {
            return new SettingsService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('payout.settings.model')
            );
        });

        /* App Settings */
        $this['app.settings.model'] = $this->factory(function () {
            return new AppSettings();

        });
        $this['app.settings.service'] = $this->factory(function (ContainerInterface $container) {
            return new AppSettingsService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('app.settings.model')
            );
        });

        /* Checkout */
        $this['checkout.model'] = $this->factory(function () {
            return new Checkout();
        });
        $this['checkout.completed_checkout.model'] = $this->factory(function () {
            return new CompletedCheckout();
        });
        $this['checkout.factory'] = $this->factory(function (ContainerInterface $container) {
            return new CheckoutFactory($container->get('checkout.model'), $container->get('collection'));
        });
        $this['checkout.completed_checkout.factory'] = $this->factory(function (ContainerInterface $container) {
            return new CompletedCheckoutFactory(
                $container->get('checkout.completed_checkout.model'),
                $container->get('collection')
            );
        });
        $this['checkout.service'] = $this->factory(function (ContainerInterface $container) {
            return new CheckoutService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('checkout.factory'),
                $container->get('checkout.completed_checkout.factory'),
                $container->get('validator.required_arguments')
            );
        });

        /* Customer */
        $this['customer.model'] = $this->factory(function () {
            return new Customer();
        });

        $this['customer.factory'] = $this->factory(function (ContainerInterface $container) {
            return new CustomerFactory($container->get('customer.model'), $container->get('collection'));
        });

        $this['customer.service'] = $this->factory(function (ContainerInterface $container) {
            return new CustomerService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('customer.factory')
            );
        });

        /* Transaction */
        $this['transaction.history.model'] = $this->factory(function () {
            return new TransactionHistory();
        });
        $this['transaction.history.factory'] = function (ContainerInterface $container) {
            return new TransactionHistoryFactory(
                $container->get('transaction.history.model'),
                $container->get('collection')
            );
        };
        $this['transaction.item.model'] = $this->factory(function () {
            return new TransactionItem();
        });
        $this['transaction.item.factory'] = function (ContainerInterface $container) {
            return new TransactionItemFactory($container->get('transaction.item.model'), $container->get('collection'));
        };
        $this['transaction.model'] = $this->factory(function () {
            return new Transaction();
        });
        $this['transaction.factory'] = function (ContainerInterface $container) {
            return new TransactionFactory($container->get('transaction.model'), $container->get('collection'));
        };
        $this['transaction.service'] = $this->factory(function (ContainerInterface $container) {
            return new TransactionService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('transaction.factory'),
                $container->get('transaction.history.factory'),
                $container->get('validator.allowed_arguments')
            );
        });

        /* Payment Instrument */
        $this['payment_instrument.model'] = $this->factory(function () {
            return new PaymentInstrument();
        });
        $this['payment_instrument.factory'] = $this->factory(function (ContainerInterface $container) {
            return new PaymentInstrumentFactory(
                $container->get('payment_instrument.model'),
                $container->get('collection')
            );
        });
        $this['payment_instrument.service'] = $this->factory(function (ContainerInterface $container) {
            return new PaymentInstrumentService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('payment_instrument.factory')
            );
        });

        /* Receipt */
        $this['receipt.model'] = $this->factory(function () {
            return new Receipt();
        });

        $this['receipt.service'] = $this->factory(function (ContainerInterface $container) {
            return new ReceiptService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('http.request'),
                $container->get('receipt.model'),
                $container->get('validator.allowed_arguments'),
                $container->get('validator.required_arguments')
            );
        });

        /* Refund */
        $this['refund.model'] = $this->factory(function () {
            return new Refund();
        });

        $this['refund.factory'] = $this->factory(function (ContainerInterface $container) {
            return new RefundFactory($container->get('refund.model'), $container->get('collection'));
        });

        $this['refund.service'] = $this->factory(function (ContainerInterface $container) {
            return new RefundService(
                $container->get('configuration'),
                $container->get('oauth.client'),
                $container->get('refund.factory'),
                $container->get('http.request'),
                $container->get('validator.allowed_arguments')
            );
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
