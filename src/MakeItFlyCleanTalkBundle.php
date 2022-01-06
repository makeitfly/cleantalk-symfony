<?php

namespace MakeItFly\CleanTalkBundle;

use MakeItFly\CleanTalkBundle\DependencyInjection\MakeItFlyCleanTalkExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MakeItFlyCleanTalkBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new MakeItFlyCleanTalkExtension();
    }
}
