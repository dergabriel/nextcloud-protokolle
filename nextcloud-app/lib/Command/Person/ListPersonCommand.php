<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Person;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\PersonService;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ListPersonCommand extends ProtokolleCommand {
    public function __construct(
        private PersonService $personService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:person:list')
            ->setDescription('Listet Personen.')
            ->setHelp('Zeigt alle gepflegten Personen mit Anzeigename, Typ und E-Mail-Adresse.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $rows = [];
            foreach ($this->personService->findAll() as $person) {
                $rows[] = [
                    $person->getId(),
                    $this->personService->getAnzeigename($person),
                    $person->getExtern() ? 'extern' : 'Nextcloud-User',
                    $person->getEmail() ?? '',
                ];
            }

            (new Table($output))
                ->setHeaders(['ID', 'Anzeigename', 'Typ', 'E-Mail'])
                ->setRows($rows)
                ->render();
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
