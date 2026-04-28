<?php

declare(strict_types=1);

namespace OCA\Protokolle\Db;

use DateTimeInterface;
use OCP\AppFramework\Db\Entity;
use OCP\DB\Types;

/**
 * @method ?string getNextcloudUserId()
 * @method void setNextcloudUserId(?string $nextcloudUserId)
 * @method ?string getVorname()
 * @method void setVorname(?string $vorname)
 * @method ?string getNachname()
 * @method void setNachname(?string $nachname)
 * @method ?string getEmail()
 * @method void setEmail(?string $email)
 * @method bool getExtern()
 * @method void setExtern(bool $extern)
 * @method ?string getNotizen()
 * @method void setNotizen(?string $notizen)
 * @method ?DateTimeInterface getCreatedAt()
 * @method void setCreatedAt(DateTimeInterface $createdAt)
 * @method ?DateTimeInterface getUpdatedAt()
 * @method void setUpdatedAt(DateTimeInterface $updatedAt)
 */
class Person extends Entity {
    protected ?string $nextcloudUserId = null;
    protected ?string $vorname = null;
    protected ?string $nachname = null;
    protected ?string $email = null;
    protected bool $extern = false;
    protected ?string $notizen = null;
    protected ?DateTimeInterface $createdAt = null;
    protected ?DateTimeInterface $updatedAt = null;

    public function __construct() {
        $this->addType('nextcloudUserId', Types::STRING);
        $this->addType('vorname', Types::STRING);
        $this->addType('nachname', Types::STRING);
        $this->addType('email', Types::STRING);
        $this->addType('extern', Types::BOOLEAN);
        $this->addType('notizen', Types::TEXT);
        $this->addType('createdAt', Types::DATETIME);
        $this->addType('updatedAt', Types::DATETIME);
    }

    public function getAnzeigename(): string {
        if ($this->getExtern()) {
            return trim(($this->getVorname() ?? '') . ' ' . ($this->getNachname() ?? ''));
        }

        return $this->getNextcloudUserId() ?? '';
    }
}
