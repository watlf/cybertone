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

    private $listSelect = array(
        'consumers.login',
        'consumers.id',
        'consumers.email',
        'consumers.accountExpired',
        'consumers.avatarExtension',
        'groups.id as groupId',
        'groups.name as groupName',
    );

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getConsumers($offset = 0, $limit = 10)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select($this->listSelect)
            ->from('Application\Model\Entity\Consumers', 'consumers')
            ->leftJoin(
                'Application\Model\Entity\Groups',
                'groups',
                Join::WITH,
                'groups.id = consumers.groupId'
            )
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $result = array(
            'count' => $this->getTotal(),
            'listUsers' => $qb->getQuery()->getArrayResult()
        );

        return $result;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('COUNT(c)')
            ->from('Application\Model\Entity\Consumers', 'c');

        return $qb->getQuery()->getSingleScalarResult();
    }
}