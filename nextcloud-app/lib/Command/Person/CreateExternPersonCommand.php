<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Person;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\PersonService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateExternPersonCommand extends ProtokolleCommand {
    public function __construct(
        private PersonService $personService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:person:create-extern')
            ->setDescription('Legt eine externe Person an.')
            ->setHelp('Legt eine manuell gepflegte externe Person für Gäste oder beratende Teilnehmende an.')
            ->addOption('vorname', null, InputOption::VALUE_REQUIRED, 'Vorname der Person')
            ->addOption('nachname', null, InputOption::VALUE_REQUIRED, 'Nachname der Person')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Optionale E-Mail-Adresse')
            ->addOption('notizen', null, InputOption::VALUE_REQUIRED, 'Optionale Notizen');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $person = $this->personService->createExtern(
                (string)$input->getOption('vorname'),
                (string)$input->getOption('nachname'),
                $input->getOption('email'),
                $input->getOption('notizen'),
            );
            $output->writeln('<info>Externe Person angelegt: #' . $person->getId() . ' ' . $this->personService->getAnzeigename($person) . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
