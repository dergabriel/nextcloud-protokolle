<?php

declare(strict_types=1);

namespace OCA\Protokolle\Service;

use DateTimeImmutable;
use DateTimeZone;
use OCA\Protokolle\Db\Person;
use OCA\Protokolle\Db\PersonMapper;
use OCA\Protokolle\Exception\InvalidPersonDataException;
use OCA\Protokolle\Exception\PersonAlreadyExistsException;
use OCA\Protokolle\Exception\PersonNotFoundException;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\IUserManager;

class PersonService {
    public function __construct(
        private PersonMapper $personMapper,
        private IUserManager $userManager,
    ) {
    }

    public function createExtern(string $vorname, string $nachname, ?string $email, ?string $notizen): Person {
        $this->validateExternalPersonData($vorname, $nachname);

        $person = new Person();
        $person->setNextcloudUserId(null);
        $person->setVorname($vorname);
        $person->setNachname($nachname);
        $person->setEmail($email);
        $person->setExtern(true);
        $person->setNotizen($notizen);
        $person->setCreatedAt($this->now());
        $person->setUpdatedAt($this->now());

        /** @var Person $created */
        $created = $this->personMapper->insert($person);
        return $created;
    }

    public function createFromNextcloudUser(string $userId): Person {
        if ($this->personMapper->findByNextcloudUserId($userId) !== null) {
            throw new PersonAlreadyExistsException('Diese Nextcloud-Person existiert bereits.');
        }

        if ($this->userManager->get($userId) === null) {
            throw new PersonNotFoundException('Nextcloud-User nicht gefunden.');
        }

        $person = new Person();
        $person->setNextcloudUserId($userId);
        $person->setVorname(null);
        $person->setNachname(null);
        $person->setEmail(null);
        $person->setExtern(false);
        $person->setNotizen(null);
        $person->setCreatedAt($this->now());
        $person->setUpdatedAt($this->now());

        /** @var Person $created */
        $created = $this->personMapper->insert($person);
        return $created;
    }

    public function update(int $id, ?string $vorname, ?string $nachname, ?string $email, ?string $notizen): Person {
        $person = $this->find($id);

        if ($person->getExtern()) {
            $this->validateExternalPersonData($vorname ?? '', $nachname ?? '');
            $person->setVorname($vorname);
            $person->setNachname($nachname);
        } else {
            $person->setVorname(null);
            $person->setNachname(null);
        }

        $person->setEmail($email);
        $person->setNotizen($notizen);
        $person->setUpdatedAt($this->now());

        /** @var Person $updated */
        $updated = $this->personMapper->update($person);
        return $updated;
    }

    public function delete(int $id): void {
        $this->personMapper->delete($this->find($id));
    }

    /**
     * @return Person[]
     */
    public function findAll(): array {
        return $this->personMapper->findAll();
    }

    public function find(int $id): Person {
        try {
            return $this->personMapper->find($id);
        } catch (DoesNotExistException) {
            throw new PersonNotFoundException('Person nicht gefunden.');
        }
    }

    public function findByNextcloudUserId(string $userId): ?Person {
        return $this->personMapper->findByNextcloudUserId($userId);
    }

    public function getAnzeigename(Person $person): string {
        if ($person->getExtern()) {
            return $person->getAnzeigename();
        }

        $userId = $person->getNextcloudUserId();
        if ($userId === null) {
            return '';
        }

        $user = $this->userManager->get($userId);
        if ($user === null || $user->getDisplayName() === '') {
            return $person->getAnzeigename();
        }

        return $user->getDisplayName();
    }

    private function validateExternalPersonData(string $vorname, string $nachname): void {
        if (trim($vorname) === '' || trim($nachname) === '') {
            throw new InvalidPersonDataException('Externe Personen brauchen Vorname und Nachname.');
        }
    }

    private function now(): DateTimeImmutable {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
