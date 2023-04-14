<?php

namespace SCBWoocommerce;

use SCBWoocommerce\Dependencies\HttpSoft\Message\RequestFactory;
use SCBWoocommerce\Dependencies\HttpSoft\Message\ResponseFactory;
use SCBWoocommerce\Dependencies\HttpSoft\Message\StreamFactory;
use SCBWoocommerce\Dependencies\LaunchpadHTTPClient\Client;
use SCBWoocommerce\Dependencies\Psr\Http\Client\ClientInterface;
use SCBWoocommerce\Dependencies\Psr\Http\Message\RequestFactoryInterface;
use SCBWoocommerce\Dependencies\Psr\Http\Message\ResponseFactoryInterface;
use SCBWoocommerce\Dependencies\Psr\Http\Message\StreamFactoryInterface;
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

    public function define()
    {
        $this->bind(ClientInterface::class, Client::class);
        $this->bind(RequestFactoryInterface::class, RequestFactory::class);
        $this->bind(ResponseFactoryInterface::class, ResponseFactory::class);
        $this->bind(StreamFactoryInterface::class, StreamFactory::class);

        parent::define();
    }
}
