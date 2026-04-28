<?php

declare(strict_types=1);

namespace OCA\Protokolle\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @extends QBMapper<Mitgliedschaft>
 */
class MitgliedschaftMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'protokolle_mitgliedschaft', Mitgliedschaft::class);
    }

    /**
     * @throws DoesNotExistException
     * @throws Exception
     */
    public function find(int $id): Mitgliedschaft {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT)));

        /** @var Mitgliedschaft $mitgliedschaft */
        $mitgliedschaft = $this->findEntity($qb);
        return $mitgliedschaft;
    }

    /**
     * @return Mitgliedschaft[]
     * @throws Exception
     */
    public function findAll(): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->orderBy('person_id', 'ASC')
            ->addOrderBy('rolle_id', 'ASC');

        /** @var Mitgliedschaft[] $mitgliedschaften */
        $mitgliedschaften = $this->findEntities($qb);
        return $mitgliedschaften;
    }

    /**
     * @return Mitgliedschaft[]
     * @throws Exception
     */
    public function findByPerson(int $personId): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('person_id', $qb->createNamedParameter($personId, IQueryBuilder::PARAM_INT)));

        /** @var Mitgliedschaft[] $mitgliedschaften */
        $mitgliedschaften = $this->findEntities($qb);
        return $mitgliedschaften;
    }

    /**
     * @return Mitgliedschaft[]
     * @throws Exception
     */
    public function findByRolle(int $rolleId): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where($qb->expr()->eq('rolle_id', $qb->createNamedParameter($rolleId, IQueryBuilder::PARAM_INT)));

        /** @var Mitgliedschaft[] $mitgliedschaften */
        $mitgliedschaften = $this->findEntities($qb);
        return $mitgliedschaften;
    }

    /**
     * @return Mitgliedschaft[]
     * @throws Exception
     */
    public function findByGremium(int $gremiumId): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('m.*')
            ->from($this->getTableName(), 'm')
            ->innerJoin('m', 'protokolle_rolle', 'r', $qb->expr()->eq('m.rolle_id', 'r.id'))
            ->where($qb->expr()->eq('r.gremium_id', $qb->createNamedParameter($gremiumId, IQueryBuilder::PARAM_INT)));

        /** @var Mitgliedschaft[] $mitgliedschaften */
        $mitgliedschaften = $this->findEntities($qb);
        return $mitgliedschaften;
    }

    /**
     * @return Mitgliedschaft[]
     * @throws Exception
     */
    public function findByPersonInGremium(int $personId, int $gremiumId): array {
        $qb = $this->db->getQueryBuilder();
        $qb->select('m.*')
            ->from($this->getTableName(), 'm')
            ->innerJoin('m', 'protokolle_rolle', 'r', $qb->expr()->eq('m.rolle_id', 'r.id'))
            ->where($qb->expr()->eq('m.person_id', $qb->createNamedParameter($personId, IQueryBuilder::PARAM_INT)))
            ->andWhere($qb->expr()->eq('r.gremium_id', $qb->createNamedParameter($gremiumId, IQueryBuilder::PARAM_INT)));

        /** @var Mitgliedschaft[] $mitgliedschaften */
        $mitgliedschaften = $this->findEntities($qb);
        return $mitgliedschaften;
    }
}
