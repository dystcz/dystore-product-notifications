<?php

namespace Dystcz\LunarProductNotification\Tests\Stubs\ProductVariants;

use Dystcz\LunarApi\Routing\RouteGroup;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class ProductVariantRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'variants';

    /** @var array */
    public array $middleware = [];

    /**
     * Register routes.
     *
     * @param  null|string  $prefix
     * @param  array|string  $middleware
     * @return void
     */
    public function routes(?string $prefix = null, array|string $middleware = []): void
    {
        Route::group([
            'prefix' => Config::get('lunar-product-notifications.route_prefix'),
            'middleware' => Config::get('lunar-product-notifications.route_middleware'),
        ], function () {
            JsonApiRoute::server('v1')
                ->prefix('v1')
                ->resources(function ($server) {
                    $server->resource($this->getPrefix(), VariantsController::class)
                        ->only('index', 'show')
                        ->relationships(function ($relations) {
                            $relations->hasMany('reviews');
                        });
                });
        });
    }
}
