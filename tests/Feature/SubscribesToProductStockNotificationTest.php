<?php

use Dystcz\LunarProductNotification\Domain\ProductNotifications\Factories\ProductNotificationFactory;
use Dystcz\LunarProductNotification\Domain\ProductNotifications\Models\ProductNotification;
use Dystcz\LunarProductNotification\Tests\Stubs\Users\User;
use Dystcz\LunarProductNotification\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lunar\Database\Factories\ProductVariantFactory;

use function Pest\Faker\faker;

uses(TestCase::class, RefreshDatabase::class);

test('user can subscribe to product stock notification', function () {
    $productVariant = ProductVariantFactory::new()->create();

    $this->actingAs(User::factory()->create());

    $data = [
        'type' => 'product-notifications',
        'attributes' => [
            'email' => $email = faker()->email,
            'purchasable_id' => $productVariant->id,
            'purchasable_type' => $productVariant::class,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('product-notifications')
        ->withData($data)
        ->post('/api/v1/product-notifications');

    $id = $response
        ->assertCreatedWithServerId('http://localhost/api/v1/product-notifications', $data)
        ->id();

    $this->assertDatabaseHas(
        (new ProductNotification())->getTable(),
        [
            'id' => $id,
            'purchasable_id' => $productVariant->id,
            'purchasable_type' => $productVariant::class,
            'email' => $email,
        ]
    );
});

it('doesnt accept duplicate emails', function () {
    $this->actingAs(User::factory()->create());

    $notification = ProductNotificationFactory::new()
        ->for(
            ProductVariantFactory::new()->state(['stock' => 0]),
            'purchasable'
        )
        ->create();

    $data = [
        'type' => 'product-notifications',
        'attributes' => [
            'email' => $notification->email,
            'purchasable_id' => $notification->purchasable_id,
            'purchasable_type' => $notification->purchasable_type,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('product-notifications')
        ->withData($data)
        ->post('/api/v1/product-notifications');

    $expected = [
        'source' => ['pointer' => '/data/attributes/email'],
        'status' => '422',
        'detail' => 'Already subscribed to this product',
    ];

    $response->assertError(422, $expected);
});

test('user can subscribe again to the same product when previously notified', function () {

    $this->actingAs(User::factory()->create());

    $notification = ProductNotificationFactory::new()
        ->for(
            ProductVariantFactory::new()->state(['stock' => 0]),
            'purchasable'
        )
        ->isSent()
        ->create();

    $data = [
        'type' => 'product-notifications',
        'attributes' => [
            'email' => $notification->email,
            'purchasable_id' => $notification->purchasable_id,
            'purchasable_type' => $notification->purchasable_type,
        ],
    ];

    $response = $this
        ->jsonApi()
        ->expects('product-notifications')
        ->withData($data)
        ->post('/api/v1/product-notifications');

    $response
        ->assertCreatedWithServerId('http://localhost/api/v1/product-notifications', $data)
        ->id();
});
