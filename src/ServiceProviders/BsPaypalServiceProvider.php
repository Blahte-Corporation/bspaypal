<?php

namespace BlahteSoftware\BsPaypal\ServiceProviders;

use BlahteSoftware\BsPaypal\Contracts\PaypalCoreInterface;
use BlahteSoftware\BsPaypal\Contracts\PaypalInterface;
use BlahteSoftware\BsPaypal\Paypal;
use BlahteSoftware\BsPaypal\PaypalCore;
use Illuminate\Support\ServiceProvider;

class BsPaypalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bspaypal.php';
        $this->mergeConfigFrom($configPath, 'bspaypal');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bspaypal.php';
        $this->publishes([$configPath => config_path('bspaypal.php')], 'config');
        $this->app->bind(PaypalCoreInterface::class, function($app) {
            return new PaypalCore(
                $app['config']->get('bspaypal.env') == 'live',
                $app['config']->get('bspaypal.account'),
                $app['config']->get('bspaypal.client_id'),
                $app['config']->get('bspaypal.secret')
            );
        });

        $this->app->bind(PaypalInterface::class, function($app) {
            return Paypal::getInstance($app->make(PaypalCoreInterface::class));
        });
    }

    public function provides() {
        return array(PaypalCoreInterface::class, PaypalInterface::class);
    }
}
