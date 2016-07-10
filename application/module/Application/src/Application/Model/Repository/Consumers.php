<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.16
 * Time: 11:34
 */

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Consumers as ConsumersEntity;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Consumers extends EntityRepository
{

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getConsumers($offset = 0, $limit = 10)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('consumers')
            ->from('Application\Model\Entity\Consumers', 'consumers')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $query = $qb->getQuery();

        $pagination = new Paginator($query);

        $result = array(
            'count' => $pagination->count(),
            'listUsers' => $pagination->getQuery()->getArrayResult()
        );

        return $result;
    }
}