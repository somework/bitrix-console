<?php

/*
 * @copyright Copyright (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 */

namespace SomeWork\Bitrix\Console\Brindge\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SomeWorkBitrixConsoleBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new SomeWorkBitrixConsoleExtension();
    }
}
