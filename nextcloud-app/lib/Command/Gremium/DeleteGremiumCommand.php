<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Gremium;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\GremiumService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class DeleteGremiumCommand extends ProtokolleCommand {
    public function __construct(
        private GremiumService $gremiumService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:gremium:delete')
            ->setDescription('Löscht ein Gremium.')
            ->setHelp('Löscht ein Gremium anhand seiner ID.')
            ->addArgument('id', InputArgument::REQUIRED, 'ID des Gremiums');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $id = (int)$input->getArgument('id');
            $this->gremiumService->delete($id);
            $output->writeln('<info>Gremium gelöscht: #' . $id . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
