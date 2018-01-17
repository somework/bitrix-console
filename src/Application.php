<?php

/*
 * @copyright Copyright (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 */

namespace SomeWork\Bitrix\Console;

use SomeWork\Bitrix\Console\Command\CacheClear;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Application extends BaseApplication
{
    const VERSION = '1.0.0';
    const BITRIX_DOCUMENT_ROOT_ARG = 'document_root';

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
     * @inheritdoc
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasArgument(static::BITRIX_DOCUMENT_ROOT_ARG)) {
            $documentRoot = $input->getArgument(static::BITRIX_DOCUMENT_ROOT_ARG) ?: $this->getDefaultDocumentRoot();
            if ($documentRoot) {
                $input->setArgument(static::BITRIX_DOCUMENT_ROOT_ARG, $documentRoot);
            }
        }
        return parent::doRun($input, $output);
    }

    protected function getDefaultDocumentRoot()
    {
        $defaultDirs = [
            getcwd(),
            dirname(__DIR__, 2),
            dirname(__DIR__),
        ];
        foreach ($defaultDirs as $dir) {
            if (file_exists($dir) . '/bitrix/modules/main/include.php') {
                return $dir;
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return [new HelpCommand(), new ListCommand()];
    }
}
