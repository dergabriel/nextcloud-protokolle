<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Mitgliedschaft;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Db\Mitgliedschaft;
use OCA\Protokolle\Service\GremiumService;
use OCA\Protokolle\Service\MitgliedschaftService;
use OCA\Protokolle\Service\PersonService;
use OCA\Protokolle\Service\RolleService;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ListMitgliedschaftCommand extends ProtokolleCommand {
    public function __construct(
        private MitgliedschaftService $mitgliedschaftService,
        private PersonService $personService,
        private RolleService $rolleService,
        private GremiumService $gremiumService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:mitgliedschaft:list')
            ->setDescription('Listet Mitgliedschaften.')
            ->setHelp('Zeigt Mitgliedschaften, optional gefiltert nach Gremium oder Person.')
            ->addOption('gremium', null, InputOption::VALUE_REQUIRED, 'Optional: ID des Gremiums')
            ->addOption('person', null, InputOption::VALUE_REQUIRED, 'Optional: ID der Person');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $mitgliedschaften = $this->filterMitgliedschaften($input);
            $rows = [];

            foreach ($mitgliedschaften as $mitgliedschaft) {
                $person = $this->personService->find($mitgliedschaft->getPersonId());
                $rolle = $this->rolleService->find($mitgliedschaft->getRolleId());
                $gremium = $this->gremiumService->find($rolle->getGremiumId());

                $rows[] = [
                    $mitgliedschaft->getId(),
                    $this->personService->getAnzeigename($person),
                    $gremium->getName(),
                    $rolle->getName(),
                    $this->boolText($this->mitgliedschaftService->istStimmberechtigt($mitgliedschaft)),
                ];
            }

            (new Table($output))
                ->setHeaders(['ID', 'Person', 'Gremium', 'Rolle', 'Stimmberechtigt'])
                ->setRows($rows)
                ->render();
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }

    /**
     * @return Mitgliedschaft[]
     */
    private function filterMitgliedschaften(InputInterface $input): array {
        $gremiumId = $input->getOption('gremium');
        $personId = $input->getOption('person');

        if ($gremiumId !== null && $personId !== null) {
            return array_values(array_filter(
                $this->mitgliedschaftService->findByGremium((int)$gremiumId),
                static fn (Mitgliedschaft $mitgliedschaft): bool => $mitgliedschaft->getPersonId() === (int)$personId,
            ));
        }

        if ($gremiumId !== null) {
            return $this->mitgliedschaftService->findByGremium((int)$gremiumId);
        }

        if ($personId !== null) {
            return $this->mitgliedschaftService->findByPerson((int)$personId);
        }

        return $this->mitgliedschaftService->findAll();
    }
}
