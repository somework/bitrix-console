<?php
/**
 * @var PhpFileLoader $this
 */

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

$definition = new Definition();
$definition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(false);
$this->registerClasses(
    $definition,
    'SomeWork\\Bitrix\\Console\\Command\\',
    '../../Command/*'
);
