<?php

namespace Dystcz\LunarProductNotification\Domain\ProductNotifications\Models;

use Lunar\Base\BaseModel;

class ProductNotification extends BaseModel
{
    protected $fillable = [
        'email_verified_at',
        'sent_at',
        'email',
    ];

    protected $casts = [
        'sent_at' => 'timestamp',
        'email_verified_at' => 'timestamp',
    ];
}
