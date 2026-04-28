<?php

declare(strict_types=1);

namespace OCA\Protokolle\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @extends QBMapper<Person>
 */
class PersonMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'protokolle_person', Person::class);
    }

    /**
     * @throws DoesNotExistException
     * @throws Exception
     */
    public function find(int $id): Person {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT)));

        /** @var Person $person */
        $person = $this->findEntity($qb);
        return $person;
    }

    /**
     * @return Person[]
     * @throws Exception
     */
    public function findAll(): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->orderBy('nachname', 'ASC')
            ->addOrderBy('vorname', 'ASC');

        /** @var Person[] $personen */
        $personen = $this->findEntities($qb);
        return $personen;
    }

    /**
     * @throws Exception
     */
    public function findByNextcloudUserId(string $userId): ?Person {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('nextcloud_user_id', $qb->createNamedParameter($userId)));

        try {
            /** @var Person $person */
            $person = $this->findEntity($qb);
            return $person;
        } catch (DoesNotExistException) {
            return null;
        }
    }

    /**
     * @return Person[]
     * @throws Exception
     */
    public function findExterne(): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('extern', $qb->createNamedParameter(true, IQueryBuilder::PARAM_BOOL)))
            ->orderBy('nachname', 'ASC')
            ->addOrderBy('vorname', 'ASC');

        /** @var Person[] $personen */
        $personen = $this->findEntities($qb);
        return $personen;
    }
}
