<?php

/*
 * @copyright Copyright (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 */

namespace SomeWork\Bitrix\Console\Brindge\Symfony;

use SomeWork\Bitrix\Console\Command\CacheClear;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class SomeWorkBitrixConsoleExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $container->addDefinitions($this->getDefinitions());
    }

    /**
     * @return Definition[]
     */
    protected function getDefinitions()
    {
        return [
            new Definition(CacheClear::class),
        ];
    }
}
