<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Command;

use Search\Service\ConsoleService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
final class UpdateIndex extends Command
{
    /** @var string */
    protected static $defaultName = 'search:update-index';
    private ConsoleService $consoleService;

    public function __construct(ConsoleService $consoleService)
    {
        parent::__construct(self::$defaultName);

        $this->consoleService = $consoleService;
    }

    protected function configure(): void
    {
        $this->setName(self::$defaultName);
        $this->addOption('reset', 'r', InputOption::VALUE_NONE, 'Reset index');
        $this->addArgument(
            'index',
            InputOption::VALUE_REQUIRED,
            'contact, profile, registration, idea, roadmap, project, version, version-document, workpackage-document, result, achievement, exploitable-result, action, publication, invoice, calendar, news, blog, press, organisation, city, tender, solution, country, all',
            'all'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reset = $input->getOption('reset');
        $index = $input->getArgument('index');

        $startMessage = sprintf("%s the index of %s", $reset ? 'Reset' : 'Update', $index);
        $endMessage   = sprintf("%s the index of %s completed", $reset ? 'Reset' : 'Update', $index);

        $output->writeln($startMessage);

        $this->consoleService->resetIndex($index, $reset);

        $output->writeln($endMessage);

        return Command::SUCCESS;
    }
}
