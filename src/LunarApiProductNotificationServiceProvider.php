<?php

namespace Dystcz\LunarApiProductNotification;

use Dystcz\LunarApi\Base\Facades\SchemaManifestFacade;
use Dystcz\LunarApi\Support\Config\Collections\DomainConfigCollection;
use Dystcz\LunarApiProductNotification\Domain\ProductNotifications\JsonApi\V1\ProductNotificationSchema;
use Dystcz\LunarApiProductNotification\Domain\ProductNotifications\Models\ProductNotification;
use Dystcz\LunarApiProductNotification\Domain\ProductNotifications\Observers\ProductVariantObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lunar\Models\ProductVariant;

class LunarApiProductNotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->registerSchemas();
        $this->registerObservers();
        $this->registerDynamicRelations();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lunar-api-product-notifications.php' => config_path('lunar-api-product-notifications.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/lunar-api-product-notifications.php', 'lunar-api-product-notifications');
    }

    /**
     * Register schemas.
     */
    public function registerObservers(): void
    {
        ProductVariant::observe(ProductVariantObserver::class);
    }

    /**
     * Register schemas.
     */
    public function registerSchemas(): void
    {
        SchemaManifestFacade::registerSchema(ProductNotificationSchema::class);
    }

    /**
     * Register dynamic relations.
     */
    protected function registerDynamicRelations(): void
    {
        ProductVariant::resolveRelationUsing('notifications', function ($model) {
            return $model->morphMany(ProductNotification::class, 'purchasable');
        });
    }

    /**
     * Register the application's policies.
     */
    public function registerPolicies(): void
    {
        DomainConfigCollection::fromConfig('lunar-api-product-notifications.domains')
            ->getPolicies()
            ->each(
                fn (string $policy, string $model) => Gate::policy($model, $policy),
            );

    }
}
