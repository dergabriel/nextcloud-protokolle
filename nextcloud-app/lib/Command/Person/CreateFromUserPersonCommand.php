<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command\Person;

use OCA\Protokolle\Command\ProtokolleCommand;
use OCA\Protokolle\Service\PersonService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateFromUserPersonCommand extends ProtokolleCommand {
    public function __construct(
        private PersonService $personService,
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        parent::configure();
        $this->setName('protokolle:person:create-from-user')
            ->setDescription('Legt eine Person aus einem Nextcloud-User an.')
            ->setHelp('Verknüpft einen vorhandenen Nextcloud-User mit einer Protokolle-Person.')
            ->addOption('user', null, InputOption::VALUE_REQUIRED, 'Nextcloud-User-ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $person = $this->personService->createFromNextcloudUser((string)$input->getOption('user'));
            $output->writeln('<info>Nextcloud-Person angelegt: #' . $person->getId() . ' ' . $this->personService->getAnzeigename($person) . '</info>');
            return 0;
        } catch (Throwable $exception) {
            return $this->handleError($output, $exception);
        }
    }
}
