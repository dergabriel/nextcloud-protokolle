<?php

declare(strict_types=1);

namespace OCA\Protokolle\Command;

use OC\Core\Command\Base;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

abstract class ProtokolleCommand extends Base {
    protected function handleError(OutputInterface $output, Throwable $exception): int {
        $output->writeln('<error>Fehler: ' . $exception->getMessage() . '</error>');
        return 1;
    }

    protected function boolText(bool $value): string {
        return $value ? 'ja' : 'nein';
    }
}
