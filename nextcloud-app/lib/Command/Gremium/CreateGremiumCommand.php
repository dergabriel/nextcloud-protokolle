<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Gremium;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\GremiumService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateGremiumCommand extends ProtokolleCommand {
    public function __construct(
        private GremiumService $gremiumService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:gremium:create')
            ->setDescription('Legt ein Gremium an.')
            ->setHelp('Legt ein neues Gremium für die Protokolle-Stammdaten an.')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name des Gremiums')
            ->addOption('kuerzel', null, InputOption::VALUE_REQUIRED, 'Optionales Kürzel des Gremiums')
            ->addOption('beschreibung', null, InputOption::VALUE_REQUIRED, 'Optionale Beschreibung des Gremiums');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $gremium = $this->gremiumService->create(
                (string)$input->getOption('name'),
                $input->getOption('kuerzel'),
                $input->getOption('beschreibung'),
            );
            $output->writeln('<info>Gremium angelegt: #' . $gremium->getId() . ' ' . $gremium->getName() . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
