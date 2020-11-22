<?php

namespace Blood72\Iamport;

use Blood72\Iamport\Contracts\IamportClientContract as IamportClient;
use Blood72\Iamport\Contracts\IamportServiceContract as IamportService;
use Blood72\Iamport\IamportClient as Client;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class IamportServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /** @var string */
    protected const CONFIG_PATH = __DIR__ . '/../config/iamport.php';

    /** @var string */
    protected string $config = 'iamport';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH => config_path("$this->config.php"),
            ], 'config');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, $this->config);

        $this->app->singleton(IamportClient::class, function ($app) {
            return new Client($app['config']->get($this->config));
        });

        $this->app->singleton(IamportService::class, function ($app) {
            return new Iamport($app[IamportClient::class]);
        });

        $this->app->alias(IamportService::class, 'iamport');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [IamportClient::class, IamportService::class];
    }
}
