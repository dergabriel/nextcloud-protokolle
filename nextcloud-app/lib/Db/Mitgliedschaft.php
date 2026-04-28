<?php

declare(strict_types=1);

namespace OCA\Protokolle\Db;

use DateTimeInterface;
use OCP\AppFramework\Db\Entity;
use OCP\DB\Types;

/**
 * @method int getPersonId()
 * @method void setPersonId(int $personId)
 * @method int getRolleId()
 * @method void setRolleId(int $rolleId)
 * @method ?bool getStimmberechtigtOverride()
 * @method void setStimmberechtigtOverride(?bool $stimmberechtigtOverride)
 * @method ?DateTimeInterface getCreatedAt()
 * @method void setCreatedAt(DateTimeInterface $createdAt)
 * @method ?DateTimeInterface getUpdatedAt()
 * @method void setUpdatedAt(DateTimeInterface $updatedAt)
 */
class Mitgliedschaft extends Entity {
    protected int $personId = 0;
    protected int $rolleId = 0;
    protected ?bool $stimmberechtigtOverride = null;
    protected ?DateTimeInterface $createdAt = null;
    protected ?DateTimeInterface $updatedAt = null;

    public function __construct() {
        $this->addType('personId', Types::INTEGER);
        $this->addType('rolleId', Types::INTEGER);
        $this->addType('stimmberechtigtOverride', Types::BOOLEAN);
        $this->addType('createdAt', 'datetime');
        $this->addType('updatedAt', 'datetime');
    }
}
