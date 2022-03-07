<?php

namespace Convoy\HttpClient;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\Authentication\BasicAuth;
use Http\Message\Authentication\Bearer;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Config
{
    private array $config;

    public const URI = 'https://cloud.getconvoy.io/api/v1';

    public function __construct(array $config = [])
    {
        $resolver = new OptionsResolver();

        $this->configureDefaultOptions($resolver);

        $this->config = $resolver->resolve($config);
    }

    private function configureDefaultOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'client_builder' => new ClientBuilder(),
            'uri_factory' => Psr17FactoryDiscovery::findUriFactory(),
            'uri' => self::URI,
            'api_key' => '',
            'username' => '',
            'password' => '',
        ]);


        $this->setAllowedTypes($resolver);
    }

    private function setAllowedTypes(OptionsResolver $resolver): void
    {
        $resolver->setAllowedTypes('uri', 'string');
        $resolver->setAllowedTypes('api_key', 'string');
        $resolver->setAllowedTypes('username', 'string');
        $resolver->setAllowedTypes('password', 'string');
        $resolver->setAllowedTypes('client_builder', ClientBuilder::class);
        $resolver->setAllowedTypes('uri_factory', UriFactoryInterface::class);
    }

    public function getClientBuilder(): ClientBuilder
    {
        return $this->config['client_builder'];
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->config['uri_factory'];
    }

    public function getUri(): UriInterface
    {
        return $this->getUriFactory()->createUri($this->config['uri']);
    }

    public function getApiKey(): string
    {
        return $this->config['api_key'];
    }

    public function getUsername(): string
    {
        return $this->config['username'];
    }

    public function getPassword(): string
    {
        return $this->config['password'];
    }

    public function isBearerTokenAuthentication(): bool
    {
        return array_key_exists('api_key', $this->config) && ! empty($this->config['api_key']);
    }

    public function getAuthenticationPlugin(): Authentication
    {
        if ($this->isBearerTokenAuthentication()) {
            return new Bearer($this->getApiKey());
        }

        return new BasicAuth($this->getUsername(), $this->getPassword());
    }
}
