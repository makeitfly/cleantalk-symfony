<?php

namespace MakeItFly\CleanTalkBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class MakeItFlyCleanTalkExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        foreach ($config as $key => $value) {
            $container->setParameter('makeitfly_cleantalk.' . $key, $value);
        }

        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.php');

        $this->registerFormWidget($container);
    }

    public function getAlias()
    {
        return 'makeitfly_cleantalk';
    }

    /**
     * Register the form template.
     */
    protected function registerFormWidget(ContainerBuilder $container): void
    {
        $formResource = '@MakeItFlyCleanTalk/cleantalk-js-on.html.twig';
        $existingResources = $this->getTwigFormResources($container);

        $container->setParameter(
            'twig.form.resources',
            array_merge($existingResources, [$formResource])
        );
    }

    private function getTwigFormResources(ContainerBuilder $container)
    {
        if (!$container->hasParameter('twig.form.resources')) {
            return [];
        }

        return $container->getParameter('twig.form.resources');
    }
}
