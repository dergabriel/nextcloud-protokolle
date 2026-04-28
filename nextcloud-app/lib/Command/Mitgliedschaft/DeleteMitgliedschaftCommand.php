<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Mitgliedschaft;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\MitgliedschaftService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class DeleteMitgliedschaftCommand extends ProtokolleCommand {
    public function __construct(
        private MitgliedschaftService $mitgliedschaftService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:mitgliedschaft:delete')
            ->setDescription('Löscht eine Mitgliedschaft.')
            ->setHelp('Löscht eine Mitgliedschaft anhand ihrer ID.')
            ->addArgument('id', InputArgument::REQUIRED, 'ID der Mitgliedschaft');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $id = (int)$input->getArgument('id');
            $this->mitgliedschaftService->delete($id);
            $output->writeln('<info>Mitgliedschaft gelöscht: #' . $id . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
