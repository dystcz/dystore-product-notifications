<?php

namespace Dystore\ProductNotifications\Domain\ProductNotifications\JsonApi\V1;

use Dystore\Api\Domain\JsonApi\Resources\JsonApiResource;
use Illuminate\Http\Request;

class ProductNotificationResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param  Request|null  $request
     */
    public function attributes($request): iterable
    {
        return parent::attributes($request);
    }
}
