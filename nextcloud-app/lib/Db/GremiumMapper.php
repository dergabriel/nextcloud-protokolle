<?php

declare(strict_types=1);

namespace OCA\Protokolle\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @extends QBMapper<Gremium>
 */
class GremiumMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'protokolle_gremium', Gremium::class);
    }

    /**
     * @throws DoesNotExistException
     * @throws Exception
     */
    public function find(int $id): Gremium {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT)));

        /** @var Gremium $gremium */
        $gremium = $this->findEntity($qb);
        return $gremium;
    }

    /**
     * @return Gremium[]
     * @throws Exception
     */
    public function findAll(): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->orderBy('name', 'ASC');

        /** @var Gremium[] $gremien */
        $gremien = $this->findEntities($qb);
        return $gremien;
    }

    /**
     * @throws Exception
     */
    public function findByName(string $name): ?Gremium {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('name', $qb->createNamedParameter($name)));

        try {
            /** @var Gremium $gremium */
            $gremium = $this->findEntity($qb);
            return $gremium;
        } catch (DoesNotExistException) {
            return null;
        }
    }
}
