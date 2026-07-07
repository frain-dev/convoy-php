<?php

namespace Convoy\HttpClient;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\Authentication\Bearer;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Config
{
    private array $config;

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
        ]);

        // uri is the instance API base, e.g. https://us.getconvoy.cloud/api/v1;
        // there is no default host, callers must state where their project lives.
        $resolver->setRequired(['uri', 'api_key', 'project_id']);

        $this->setAllowedTypes($resolver);
    }

    private function setAllowedTypes(OptionsResolver $resolver): void
    {
        $resolver->setAllowedTypes('uri', 'string');
        $resolver->setAllowedTypes('api_key', 'string');
        $resolver->setAllowedTypes('project_id', 'string');
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
        $uri = sprintf('%s/projects/%s', $this->config['uri'], $this->config['project_id']);

        return $this->getUriFactory()->createUri($uri);
    }

    public function getApiKey(): string
    {
        return $this->config['api_key'];
    }

    public function getAuthenticationPlugin(): Authentication
    {
        return new Bearer($this->getApiKey());
    }
}
