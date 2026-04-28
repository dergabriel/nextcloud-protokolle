<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Rolle;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\GremiumService;
use OCA\Protokolle\Service\RolleService;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ListRolleCommand extends ProtokolleCommand {
    public function __construct(
        private RolleService $rolleService,
        private GremiumService $gremiumService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:rolle:list')
            ->setDescription('Listet Rollen.')
            ->setHelp('Zeigt Rollen, optional gefiltert nach Gremium.')
            ->addOption('gremium', null, InputOption::VALUE_REQUIRED, 'Optional: ID des Gremiums');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $gremiumId = $input->getOption('gremium');
            $rollen = $gremiumId === null ? $this->rolleService->findAll() : $this->rolleService->findByGremium((int)$gremiumId);
            $rows = [];

            foreach ($rollen as $rolle) {
                $gremium = $this->gremiumService->find($rolle->getGremiumId());
                $rows[] = [
                    $rolle->getId(),
                    $gremium->getName(),
                    $rolle->getName(),
                    $this->boolText($rolle->getStimmberechtigtDefault()),
                ];
            }

            (new Table($output))
                ->setHeaders(['ID', 'Gremium', 'Name', 'Stimmberechtigt-Default'])
                ->setRows($rows)
                ->render();
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
