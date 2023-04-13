<?php

namespace SCBWoocommerce;

use SCBWoocommerce\Engine\Checkout\GatewaySubscriber;
use SCBWoocommerce\Engine\Checkout\Subscriber;

class ServiceProvider extends Dependencies\LaunchpadAutoresolver\ServiceProvider
{
    public function get_common_subscribers(): array
    {
        return [
            GatewaySubscriber::class,
            Subscriber::class,
        ];
    }
}
