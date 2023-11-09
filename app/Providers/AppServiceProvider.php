<?php

namespace App\Providers;

use App\Http\Resources\PropertyResource;
use Illuminate\Support\ServiceProvider;
use Omnipay\AuthorizeNet\AIMGateway;
use Omnipay\AuthorizeNet\CIMGateway;
use Omnipay\Omnipay;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        PropertyResource::withoutWrapping();

        $this->app->bind(AIMGateway::class, function() {
            /** @var AIMGateway $gateway */
            $gateway = Omnipay::create('AuthorizeNet_AIM')
                ->initialize([
                    'apiLoginId' => config('authorize.net.api_login_id'),
                    'transactionKey' => config('authorize.net.transaction_key'),
                    'testMode' => false,
                    'developerMode' => config('authorize.net.developer_mode'),
                ]);

            return $gateway;
        });

        $this->app->bind(CIMGateway::class, function () {
            return (new CIMGateway())
                ->setApiLoginId(config('authorize.net.api_login_id'))
                ->setTransactionKey(config('authorize.net.transaction_key'))
                ->setTestMode(false)
                ->setDeveloperMode(config('authorize.net.developer_mode'));
        });
    }
}
