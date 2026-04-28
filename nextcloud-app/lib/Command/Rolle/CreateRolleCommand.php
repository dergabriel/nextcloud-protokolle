<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Rolle;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\RolleService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateRolleCommand extends ProtokolleCommand {
    public function __construct(
        private RolleService $rolleService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:rolle:create')
            ->setDescription('Legt eine Rolle an.')
            ->setHelp('Legt eine Rolle innerhalb eines Gremiums an.')
            ->addOption('gremium', null, InputOption::VALUE_REQUIRED, 'ID des Gremiums')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name der Rolle')
            ->addOption('no-stimmberechtigt', null, InputOption::VALUE_NONE, 'Rolle standardmäßig nicht stimmberechtigt')
            ->addOption('beschreibung', null, InputOption::VALUE_REQUIRED, 'Optionale Beschreibung der Rolle');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $rolle = $this->rolleService->create(
                (int)$input->getOption('gremium'),
                (string)$input->getOption('name'),
                !$input->getOption('no-stimmberechtigt'),
                $input->getOption('beschreibung'),
            );
            $output->writeln('<info>Rolle angelegt: #' . $rolle->getId() . ' ' . $rolle->getName() . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
