<?php

declare(strict_types=1);

namespace OCA\Protokolle\Service;

use OCA\Protokolle\Db\Gremium;
use OCA\Protokolle\Db\GremiumMapper;
use OCA\Protokolle\Exception\GremiumNameAlreadyExistsException;
use OCA\Protokolle\Exception\GremiumNotFoundException;
use OCP\AppFramework\Db\DoesNotExistException;

class GremiumService {
    public function __construct(
        private GremiumMapper $gremiumMapper,
    ) {
    }

    public function create(string $name, ?string $kuerzel, ?string $beschreibung): Gremium {
        $this->assertNameIsUnique($name);

        $gremium = new Gremium();
        $gremium->setName($name);
        $gremium->setKuerzel($kuerzel);
        $gremium->setBeschreibung($beschreibung);
        $now = new \DateTime();
        $gremium->setCreatedAt($now);
        $gremium->setUpdatedAt($now);

        /** @var Gremium $created */
        $created = $this->gremiumMapper->insert($gremium);
        return $created;
    }

    public function update(int $id, string $name, ?string $kuerzel, ?string $beschreibung): Gremium {
        $gremium = $this->find($id);
        $existing = $this->gremiumMapper->findByName($name);

        if ($existing !== null && $existing->getId() !== $id) {
            throw new GremiumNameAlreadyExistsException('Ein Gremium mit diesem Namen existiert bereits.');
        }

        $gremium->setName($name);
        $gremium->setKuerzel($kuerzel);
        $gremium->setBeschreibung($beschreibung);
        $gremium->setUpdatedAt(new \DateTime());

        /** @var Gremium $updated */
        $updated = $this->gremiumMapper->update($gremium);
        return $updated;
    }

    public function delete(int $id): void {
        $this->gremiumMapper->delete($this->find($id));
    }

    /**
     * @return Gremium[]
     */
    public function findAll(): array {
        return $this->gremiumMapper->findAll();
    }

    public function find(int $id): Gremium {
        try {
            return $this->gremiumMapper->find($id);
        } catch (DoesNotExistException) {
            throw new GremiumNotFoundException('Gremium nicht gefunden.');
        }
    }

    private function assertNameIsUnique(string $name): void {
        if ($this->gremiumMapper->findByName($name) !== null) {
            throw new GremiumNameAlreadyExistsException('Ein Gremium mit diesem Namen existiert bereits.');
        }
    }

}
