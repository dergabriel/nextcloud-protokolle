<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Gremium;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\GremiumService;
use OCA\Protokolle\Service\MitgliedschaftService;
use OCA\Protokolle\Service\RolleService;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ListGremiumCommand extends ProtokolleCommand {
    public function __construct(
        private GremiumService $gremiumService,
        private RolleService $rolleService,
        private MitgliedschaftService $mitgliedschaftService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:gremium:list')
            ->setDescription('Listet alle Gremien.')
            ->setHelp('Zeigt alle gepflegten Gremien mit Anzahl Rollen und Mitgliedschaften.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $rows = [];
            foreach ($this->gremiumService->findAll() as $gremium) {
                $rows[] = [
                    $gremium->getId(),
                    $gremium->getName(),
                    $gremium->getKuerzel() ?? '',
                    count($this->rolleService->findByGremium($gremium->getId())),
                    count($this->mitgliedschaftService->findByGremium($gremium->getId())),
                ];
            }

            (new Table($output))
                ->setHeaders(['ID', 'Name', 'Kürzel', 'Anzahl Rollen', 'Anzahl Mitglieder'])
                ->setRows($rows)
                ->render();
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
