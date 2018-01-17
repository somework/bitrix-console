<?php

/*
 * @copyright Copyright (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 */

namespace SomeWork\Bitrix\Console\Brindge\Symfony;

use SomeWork\Bitrix\Console\Command\CacheClear;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

class SomeWorkBitrixConsoleExtension extends Extension
{
    /**
     * @return Definition[]
     */
    protected function getDefinitions()
    {
        return [
            new Definition(CacheClear::class),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->addDefinitions($this->getDefinitions());
    }
}
