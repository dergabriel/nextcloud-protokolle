<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Person;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\PersonService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class DeletePersonCommand extends ProtokolleCommand {
    public function __construct(
        private PersonService $personService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:person:delete')
            ->setDescription('Löscht eine Person.')
            ->setHelp('Löscht eine Person anhand ihrer ID.')
            ->addArgument('id', InputArgument::REQUIRED, 'ID der Person');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $id = (int)$input->getArgument('id');
            $this->personService->delete($id);
            $output->writeln('<info>Person gelöscht: #' . $id . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
