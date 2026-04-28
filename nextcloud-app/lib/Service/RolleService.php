<?php

declare(strict_types=1);

namespace OCA\Protokolle\Service;

use OCA\Protokolle\Db\Rolle;
use OCA\Protokolle\Db\RolleMapper;
use OCA\Protokolle\Exception\RolleNameInGremiumAlreadyExistsException;
use OCA\Protokolle\Exception\RolleNotFoundException;
use OCP\AppFramework\Db\DoesNotExistException;

class RolleService {
    public function __construct(
        private RolleMapper $rolleMapper,
    ) {
    }

    public function create(int $gremiumId, string $name, bool $stimmberechtigtDefault, ?string $beschreibung): Rolle {
        $this->assertNameIsUniqueInGremium($gremiumId, $name);

        $rolle = new Rolle();
        $rolle->setGremiumId($gremiumId);
        $rolle->setName($name);
        $rolle->setStimmberechtigtDefault($stimmberechtigtDefault);
        $rolle->setBeschreibung($beschreibung);
        $now = new \DateTime();
        $rolle->setCreatedAt($now);
        $rolle->setUpdatedAt($now);

        /** @var Rolle $created */
        $created = $this->rolleMapper->insert($rolle);
        return $created;
    }

    public function update(int $id, string $name, bool $stimmberechtigtDefault, ?string $beschreibung): Rolle {
        $rolle = $this->find($id);
        $existing = $this->rolleMapper->findByGremiumAndName($rolle->getGremiumId(), $name);

        if ($existing !== null && $existing->getId() !== $id) {
            throw new RolleNameInGremiumAlreadyExistsException('Eine Rolle mit diesem Namen existiert in diesem Gremium bereits.');
        }

        $rolle->setName($name);
        $rolle->setStimmberechtigtDefault($stimmberechtigtDefault);
        $rolle->setBeschreibung($beschreibung);
        $rolle->setUpdatedAt(new \DateTime());

        /** @var Rolle $updated */
        $updated = $this->rolleMapper->update($rolle);
        return $updated;
    }

    public function delete(int $id): void {
        $this->rolleMapper->delete($this->find($id));
    }

    /**
     * @return Rolle[]
     */
    public function findAll(): array {
        return $this->rolleMapper->findAll();
    }

    /**
     * @return Rolle[]
     */
    public function findByGremium(int $gremiumId): array {
        return $this->rolleMapper->findByGremium($gremiumId);
    }

    public function find(int $id): Rolle {
        try {
            return $this->rolleMapper->find($id);
        } catch (DoesNotExistException) {
            throw new RolleNotFoundException('Rolle nicht gefunden.');
        }
    }

    private function assertNameIsUniqueInGremium(int $gremiumId, string $name): void {
        if ($this->rolleMapper->findByGremiumAndName($gremiumId, $name) !== null) {
            throw new RolleNameInGremiumAlreadyExistsException('Eine Rolle mit diesem Namen existiert in diesem Gremium bereits.');
        }
    }

}
