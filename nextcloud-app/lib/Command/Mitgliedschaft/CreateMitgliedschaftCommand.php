<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Mitgliedschaft;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\MitgliedschaftService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateMitgliedschaftCommand extends ProtokolleCommand {
    public function __construct(
        private MitgliedschaftService $mitgliedschaftService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:mitgliedschaft:create')
            ->setDescription('Legt eine Mitgliedschaft an.')
            ->setHelp('Verknüpft eine Person mit einer Rolle. Optional kann das Stimmrecht überschrieben werden.')
            ->addOption('person', null, InputOption::VALUE_REQUIRED, 'ID der Person')
            ->addOption('rolle', null, InputOption::VALUE_REQUIRED, 'ID der Rolle')
            ->addOption('stimmberechtigt', null, InputOption::VALUE_REQUIRED, 'Optionaler Override: true oder false');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $mitgliedschaft = $this->mitgliedschaftService->create(
                (int)$input->getOption('person'),
                (int)$input->getOption('rolle'),
                $this->parseNullableBool($input->getOption('stimmberechtigt')),
            );
            $output->writeln('<info>Mitgliedschaft angelegt: #' . $mitgliedschaft->getId() . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }

    private function parseNullableBool(mixed $value): ?bool {
        if ($value === null) {
            return null;
        }

        return match (strtolower((string)$value)) {
            'true', '1', 'ja' => true,
            'false', '0', 'nein' => false,
            default => throw new \InvalidArgumentException('stimmberechtigt muss true oder false sein.'),
        };
    }
}
