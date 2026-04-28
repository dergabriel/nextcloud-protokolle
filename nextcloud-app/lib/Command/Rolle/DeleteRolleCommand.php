<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Rolle;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\RolleService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class DeleteRolleCommand extends ProtokolleCommand {
    public function __construct(
        private RolleService $rolleService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:rolle:delete')
            ->setDescription('Löscht eine Rolle.')
            ->setHelp('Löscht eine Rolle anhand ihrer ID.')
            ->addArgument('id', InputArgument::REQUIRED, 'ID der Rolle');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $id = (int)$input->getArgument('id');
            $this->rolleService->delete($id);
            $output->writeln('<info>Rolle gelöscht: #' . $id . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
