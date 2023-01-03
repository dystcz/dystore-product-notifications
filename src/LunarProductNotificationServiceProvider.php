<?php

namespace Dystcz\LunarProductNotification;

use Dystcz\LunarProductNotification\Domain\ProductNotifications\Models\ProductNotification;
use Dystcz\LunarProductNotification\Domain\ProductNotifications\Policies\ProductNotificationPolicy;
use Illuminate\Support\ServiceProvider;

class LunarProductNotificationServiceProvider extends ServiceProvider
{
    protected $policies = [
        ProductNotification::class => ProductNotificationPolicy::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lunar-product-notifications.php' => config_path('lunar-product-notifications.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/lunar-product-notifications.php', 'lunar-product-notifications');
    }
}
