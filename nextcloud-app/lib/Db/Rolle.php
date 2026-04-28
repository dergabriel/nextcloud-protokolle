<?php

declare(strict_types=1);

namespace OCA\Protokolle\Db;

use DateTimeInterface;
use OCP\AppFramework\Db\Entity;
use OCP\DB\Types;

/**
 * @method int getGremiumId()
 * @method void setGremiumId(int $gremiumId)
 * @method string getName()
 * @method void setName(string $name)
 * @method bool getStimmberechtigtDefault()
 * @method void setStimmberechtigtDefault(bool $stimmberechtigtDefault)
 * @method ?string getBeschreibung()
 * @method void setBeschreibung(?string $beschreibung)
 * @method ?DateTimeInterface getCreatedAt()
 * @method void setCreatedAt(DateTimeInterface $createdAt)
 * @method ?DateTimeInterface getUpdatedAt()
 * @method void setUpdatedAt(DateTimeInterface $updatedAt)
 */
class Rolle extends Entity {
    protected int $gremiumId = 0;
    protected string $name = '';
    protected bool $stimmberechtigtDefault = true;
    protected ?string $beschreibung = null;
    protected ?DateTimeInterface $createdAt = null;
    protected ?DateTimeInterface $updatedAt = null;

    public function __construct() {
        $this->addType('gremiumId', Types::INTEGER);
        $this->addType('name', Types::STRING);
        $this->addType('stimmberechtigtDefault', Types::BOOLEAN);
        $this->addType('beschreibung', Types::TEXT);
        $this->addType('createdAt', 'datetime');
        $this->addType('updatedAt', 'datetime');
    }
}
