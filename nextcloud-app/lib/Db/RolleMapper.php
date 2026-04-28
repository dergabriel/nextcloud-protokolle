<?php

declare(strict_types=1);

namespace OCA\Protokolle\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @extends QBMapper<Rolle>
 */
class RolleMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'protokolle_rolle', Rolle::class);
    }

    /**
     * @throws DoesNotExistException
     * @throws Exception
     */
    public function find(int $id): Rolle {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT)));

        /** @var Rolle $rolle */
        $rolle = $this->findEntity($qb);
        return $rolle;
    }

    /**
     * @return Rolle[]
     * @throws Exception
     */
    public function findAll(): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->orderBy('gremium_id', 'ASC')
            ->addOrderBy('name', 'ASC');

        /** @var Rolle[] $rollen */
        $rollen = $this->findEntities($qb);
        return $rollen;
    }

    /**
     * @return Rolle[]
     * @throws Exception
     */
    public function findByGremium(int $gremiumId): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('gremium_id', $qb->createNamedParameter($gremiumId, IQueryBuilder::PARAM_INT)))
            ->orderBy('name', 'ASC');

        /** @var Rolle[] $rollen */
        $rollen = $this->findEntities($qb);
        return $rollen;
    }

    /**
     * @throws Exception
     */
    public function findByGremiumAndName(int $gremiumId, string $name): ?Rolle {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('gremium_id', $qb->createNamedParameter($gremiumId, IQueryBuilder::PARAM_INT)))
            ->andWhere($qb->expr()->eq('name', $qb->createNamedParameter($name)));

        try {
            /** @var Rolle $rolle */
            $rolle = $this->findEntity($qb);
            return $rolle;
        } catch (DoesNotExistException) {
            return null;
        }
    }
}
