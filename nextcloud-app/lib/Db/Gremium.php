<?php

declare(strict_types=1);

namespace OCA\Protokolle\Db;

use DateTimeInterface;
use OCP\AppFramework\Db\Entity;
use OCP\DB\Types;

/**
 * @method string getName()
 * @method void setName(string $name)
 * @method ?string getKuerzel()
 * @method void setKuerzel(?string $kuerzel)
 * @method ?string getBeschreibung()
 * @method void setBeschreibung(?string $beschreibung)
 * @method ?DateTimeInterface getCreatedAt()
 * @method void setCreatedAt(DateTimeInterface $createdAt)
 * @method ?DateTimeInterface getUpdatedAt()
 * @method void setUpdatedAt(DateTimeInterface $updatedAt)
 */
class Gremium extends Entity {
    protected string $name = '';
    protected ?string $kuerzel = null;
    protected ?string $beschreibung = null;
    protected ?DateTimeInterface $createdAt = null;
    protected ?DateTimeInterface $updatedAt = null;

    public function __construct() {
        $this->addType('name', Types::STRING);
        $this->addType('kuerzel', Types::STRING);
        $this->addType('beschreibung', Types::TEXT);
        $this->addType('createdAt', 'datetime');
        $this->addType('updatedAt', 'datetime');
    }
}
