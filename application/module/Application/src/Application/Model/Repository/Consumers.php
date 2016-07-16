<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.16
 * Time: 11:34
 */

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Consumers extends EntityRepository
{
    /**
     * @param array $filters
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getConsumers($filters = array(), $offset = 0, $limit = 10)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('consumers')
            ->from('Application\Model\Entity\Consumers', 'consumers')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if ($filters) {
            $qb = $this->setFilters($qb, $filters);
        }

        $pagination = new Paginator($qb);

        $result = array(
            'count' => $pagination->count(),
            'listUsers' => $pagination->getQuery()->getArrayResult()
        );

        return $result;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $filters
     * @return QueryBuilder
     */
    private function setFilters(QueryBuilder $qb, array $filters)
    {
        if (isset($filters['filterByGroupId']) && '' !== $filters['filterByGroupId']) {
            switch ($filters['filterByGroupId']) {
                case 0:
                    $qb->where('consumers.groupId is NULL');
                    break;
                default:
                    $qb->where('consumers.groupId = :groupId');
                    $qb->setParameter('groupId', $filters['filterByGroupId']);
            }
        }

        if (!empty($filters['accountExpired'])) {
            $qb->where('consumers.accountExpired <= :accountExpired');
            $qb->setParameter('accountExpired', $filters['accountExpired']);
        }

        if (!empty($filters['orderByField'])) {
            $qb->orderBy('consumers.' . $filters['orderByField'], $filters['order']);
        }

        return $qb;
    }
}