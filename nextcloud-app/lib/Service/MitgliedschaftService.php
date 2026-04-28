<?php

declare(strict_types=1);

namespace OCA\Protokolle\Service;

use DateTimeImmutable;
use DateTimeZone;
use OCA\Protokolle\Db\Mitgliedschaft;
use OCA\Protokolle\Db\MitgliedschaftMapper;
use OCA\Protokolle\Db\RolleMapper;
use OCA\Protokolle\Exception\MitgliedschaftAlreadyExistsException;
use OCA\Protokolle\Exception\MitgliedschaftNotFoundException;
use OCA\Protokolle\Exception\RolleNotFoundException;
use OCP\AppFramework\Db\DoesNotExistException;

class MitgliedschaftService {
    public function __construct(
        private MitgliedschaftMapper $mitgliedschaftMapper,
        private RolleMapper $rolleMapper,
    ) {
    }

    public function create(int $personId, int $rolleId, ?bool $stimmberechtigtOverride): Mitgliedschaft {
        foreach ($this->mitgliedschaftMapper->findByPerson($personId) as $mitgliedschaft) {
            if ($mitgliedschaft->getRolleId() === $rolleId) {
                throw new MitgliedschaftAlreadyExistsException('Diese Mitgliedschaft existiert bereits.');
            }
        }

        $mitgliedschaft = new Mitgliedschaft();
        $mitgliedschaft->setPersonId($personId);
        $mitgliedschaft->setRolleId($rolleId);
        $mitgliedschaft->setStimmberechtigtOverride($stimmberechtigtOverride);
        $mitgliedschaft->setCreatedAt($this->now());
        $mitgliedschaft->setUpdatedAt($this->now());

        /** @var Mitgliedschaft $created */
        $created = $this->mitgliedschaftMapper->insert($mitgliedschaft);
        return $created;
    }

    public function update(int $id, ?bool $stimmberechtigtOverride): Mitgliedschaft {
        $mitgliedschaft = $this->find($id);
        $mitgliedschaft->setStimmberechtigtOverride($stimmberechtigtOverride);
        $mitgliedschaft->setUpdatedAt($this->now());

        /** @var Mitgliedschaft $updated */
        $updated = $this->mitgliedschaftMapper->update($mitgliedschaft);
        return $updated;
    }

    public function delete(int $id): void {
        $this->mitgliedschaftMapper->delete($this->find($id));
    }

    /**
     * @return Mitgliedschaft[]
     */
    public function findByPerson(int $personId): array {
        return $this->mitgliedschaftMapper->findByPerson($personId);
    }

    /**
     * @return Mitgliedschaft[]
     */
    public function findByGremium(int $gremiumId): array {
        return $this->mitgliedschaftMapper->findByGremium($gremiumId);
    }

    public function find(int $id): Mitgliedschaft {
        try {
            return $this->mitgliedschaftMapper->find($id);
        } catch (DoesNotExistException) {
            throw new MitgliedschaftNotFoundException('Mitgliedschaft nicht gefunden.');
        }
    }

    public function istStimmberechtigt(Mitgliedschaft $mitgliedschaft): bool {
        $override = $mitgliedschaft->getStimmberechtigtOverride();

        if ($override !== null) {
            return $override;
        }

        try {
            return $this->rolleMapper->find($mitgliedschaft->getRolleId())->getStimmberechtigtDefault();
        } catch (DoesNotExistException) {
            throw new RolleNotFoundException('Rolle nicht gefunden.');
        }
    }

    private function now(): DateTimeImmutable {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
