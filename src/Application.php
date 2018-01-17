<?php

/*
 * @copyright Copyright (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 */

namespace SomeWork\Bitrix\Console;

use SomeWork\Bitrix\Console\Command\CacheClear;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;

final class Application extends BaseApplication
{
    const VERSION = '1.0.0';

    public function __construct()
    {
        error_reporting(-1);

        parent::__construct('Bitrix Console Commands', self::VERSION);

        $this->add(new CacheClear());
    }

    /**
     * {@inheritdoc}
     */
    public function getLongVersion()
    {
        $version = parent::getLongVersion();
        $version .= ' by <comment>Igor Pinchuk</comment>';

        $commit = '@git-commit@';

        if ('@' . 'git-commit@' !== $commit) {
            $version .= ' (' . substr($commit, 0, 7) . ')';
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return [new HelpCommand(), new ListCommand()];
    }
}
