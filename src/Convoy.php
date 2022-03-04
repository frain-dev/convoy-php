<?php

namespace Convoy;

use Convoy\Api\Application;
use Convoy\Api\Group;
use Convoy\HttpClient\ClientBuilder;
use Convoy\HttpClient\Config;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Message\Authentication\Bearer;

class Convoy
{
    private ClientBuilder $clientBuilder;

    public function __construct(Config $config = null)
    {
        $config = $config ?? new Config();

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

        $this->clientBuilder->addPlugin(new AuthenticationPlugin(new Bearer($config->getApiKey())));
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
}
