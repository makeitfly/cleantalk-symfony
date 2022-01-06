<?php

use Cleantalk\Cleantalk;
use MakeItFly\CleanTalkBundle\CleanTalk\CleanTalkFactory;
use MakeItFly\CleanTalkBundle\CleanTalk\CleanTalkRequestBuilder;
use MakeItFly\CleanTalkBundle\Validator\Constraints\CleanTalkValidator;
use MakeItFly\CleanTalkBundle\Validator\PropertyResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(CleanTalkValidator::class)
        ->arg('$enabled', '%makeitfly_cleantalk.enabled%')
        ->arg('$cleanTalkApi', service(Cleantalk::class))
        ->arg('$cleanTalkRequestBuilder', service(CleanTalkRequestBuilder::class))
        ->arg('$propertyResolver', service(PropertyResolver::class))
        ->tag(
            'validator.constraint_validator',
            ['alias' => 'makeitfly.validator.cleantalk']
        );

    $services->set(Cleantalk::class)
        ->factory([CleanTalkFactory::class, 'create'])
        ->arg('$serverUrl', '%makeitfly_cleantalk.server_url%');

    $services->set(CleanTalkRequestBuilder::class)
        ->arg('$authKey', '%makeitfly_cleantalk.auth_key%')
        ->arg('$agent', '%makeitfly_cleantalk.agent%')
        ->arg('$requestStack', service(RequestStack::class));

    $services->set(PropertyResolver::class)
        ->arg('$propertyAccessor', service(PropertyAccessorInterface::class));
};
