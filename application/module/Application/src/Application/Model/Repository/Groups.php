<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.16
 * Time: 11:34
 */

namespace Application\Model\Repository;

use Application\View\Helper\PaginationHelper;
use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Groups as GroupsEntity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Groups extends EntityRepository
{
    /**
     * @return array
     */
    public function getAllGroups()
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('groups')
            ->from('Application\Model\Entity\Groups', 'groups');

        $result = array();

        $resultQuery = $qb->getQuery()->getArrayResult();

        foreach ($resultQuery as $group) {
            $result[$group['id']] = $group['name'];
        }

        return $result;
    }

    /**
     * @param array $filters
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getGroups($filters = array(), $offset = 0, $limit = PaginationHelper::PER_PAGE)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('groups')
            ->from('Application\Model\Entity\Groups', 'groups')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if ($filters) {
            $qb->orderBy('groups.' . $filters['fields'], $filters['order']);
        }

        $pagination = new Paginator($qb);

        $result = array(
            'count' => $pagination->count(),
            'listGroups' => $pagination->getQuery()->getArrayResult()
        );

        return $result;
    }
}