<?php

/*
 * @copyright Copyright (c) Pinchuk Igor <i.pinchuk.work@gmail.com>
 */

namespace SomeWork\Bitrix\Console\Command;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use SomeWork\Bitrix\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BitrixCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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
                Application::BITRIX_DOCUMENT_ROOT_ARG,
                InputArgument::OPTIONAL,
                'Bitrix Document Root',
                ''
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === $this->logger) {
            $this->setLogger(new ConsoleLogger($output));
        }

        $documentRoot = $input->getOption(Application::BITRIX_DOCUMENT_ROOT_ARG);
        $this->log(LogLevel::INFO, 'Bitrix Document Root: '.$documentRoot);
        $this->executeInternal($input, $output);
    }

    /**
     * @param string $documentRoot
     *
     * @throws \RuntimeException
     */
    protected function defineServerDocumentRoot($documentRoot)
    {
        if ($documentRoot && file_exists($documentRoot.'/bitrix/modules/main/include.php')) {
            $_SERVER['DOCUMENT_ROOT'] = $documentRoot;
            require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include.php';

            return;
        }

        throw new \RuntimeException('No such document root was found');
    }

    abstract protected function executeInternal(InputInterface $input, OutputInterface $output);
}
