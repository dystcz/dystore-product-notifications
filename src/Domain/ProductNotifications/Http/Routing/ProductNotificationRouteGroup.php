<?php

namespace Dystcz\LunarProductNotification\Domain\ProductNotifications\Http\Routing;

use Dystcz\LunarApi\Routing\RouteGroup;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;

class ProductNotificationRouteGroup extends RouteGroup
{
    /** @var string */
    public string $prefix = 'product-notifications';

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
        JsonApiRoute::server('v1')
            ->prefix('v1')
            ->resources(function ($server) {
                //
            });
    }
}
