<?php

namespace Convoy;

use Convoy\Api\Application;
use Convoy\Api\DeliveryAttempt;
use Convoy\Api\Endpoint;
use Convoy\Api\Event;
use Convoy\Api\EventDelivery;
use Convoy\Api\Group;
use Convoy\HttpClient\ClientBuilder;
use Convoy\HttpClient\Config;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;

class Convoy
{
    private ClientBuilder $clientBuilder;

    public function __construct(array $config = [])
    {
        $config = new Config($config);

        $this->clientBuilder = $config->getClientBuilder();

        $this->clientBuilder->addPlugin(
            new BaseUriPlugin($config->getUri())
        );
        $this->clientBuilder->addPlugin(
            new HeaderDefaultsPlugin(
                [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ]
            )
        );

        $this->clientBuilder->addPlugin(new AuthenticationPlugin($config->getAuthenticationPlugin()));
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->clientBuilder->getHttpClient();
    }

    public function groups(): Group
    {
        return new Group($this->getHttpClient());
    }

    public function applications(): Application
    {
        return new Application($this->getHttpClient());
    }

    public function events(): Event
    {
        return new Event($this->getHttpClient());
    }

    public function eventDeliveries(): EventDelivery
    {
        return new EventDelivery($this->getHttpClient());
    }

    public function endpoints(): Endpoint
    {
        return new Endpoint($this->getHttpClient());
    }

    public function deliveryAttempts(): DeliveryAttempt
    {
        return new DeliveryAttempt($this->getHttpClient());
    }
}
