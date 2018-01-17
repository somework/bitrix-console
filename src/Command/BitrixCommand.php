<?php

/*
 * @copyright Copyright (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 */

namespace SomeWork\Bitrix\Console\Command;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BitrixCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const BITRIX_DOCUMENT_ROOT_ARG = 'document_root';
    const INCLUDE_FILE = '/bitrix/modules/main/include.php';

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if (null === $this->logger) {
            return;
        }
        $this->logger->log($level, $message, $context);
    }

    public function configure()
    {
        $this
            ->addArgument(
                self::BITRIX_DOCUMENT_ROOT_ARG,
                InputArgument::OPTIONAL,
                'Bitrix Document Root',
                ''
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === $this->logger) {
            $this->setLogger(new ConsoleLogger($output, [
                LogLevel::INFO => OutputInterface::OUTPUT_NORMAL,
            ]));
        }

        $documentRoot = rtrim(
            $input->getArgument(self::BITRIX_DOCUMENT_ROOT_ARG) ?: $this->getDefaultDocumentRoot(),
            DIRECTORY_SEPARATOR
        );
        $this->log(LogLevel::INFO, 'Bitrix Document Root: ' . $documentRoot);
        $this->includeBitrix($documentRoot);
        $this->executeInternal($input, $output);
    }

    /**
     * @param string $documentRoot
     *
     * @throws \RuntimeException
     */
    protected function includeBitrix($documentRoot)
    {
        if ($documentRoot && file_exists($documentRoot . static::INCLUDE_FILE)) {
            $_SERVER['DOCUMENT_ROOT'] = $documentRoot;
            require_once $_SERVER['DOCUMENT_ROOT'] . static::INCLUDE_FILE;

            return;
        }

        throw new \RuntimeException('No such document root was found');
    }

    protected function getDefaultDocumentRoot()
    {
        $defaultDirs = [
            getcwd(),
            dirname(__DIR__, 2),
            dirname(__DIR__),
        ];
        foreach ($defaultDirs as $dir) {
            if (file_exists($dir) . static::INCLUDE_FILE) {
                return $dir;
            }
        }
    }

    abstract protected function executeInternal(InputInterface $input, OutputInterface $output);
}
