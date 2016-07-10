<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.16
 * Time: 11:34
 */

namespace Application\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Model\Entity\Groups as GroupsEntity;
use Doctrine\ORM\Query\Expr\Join;

class Groups extends EntityRepository
{
    /**
     * @return array
     */
    public function getGroupsWithConsumers()
    {
        $result = array();

        $qb = $this->_em->createQueryBuilder()
            ->select('consumers', 'groups')
            ->from('Application\Model\Entity\Groups', 'groups')
            ->leftJoin(
                'Application\Model\Entity\Consumers',
                'consumers',
                Join::WITH,
                'groups.id = consumers.idGroup'
            );

        $groups = $qb->getQuery()->getArrayResult();


        foreach ($groups as $consumer) {
            if (isset($consumer['name'])) {
                $result['groups'][] = $consumer;
            } else {
                $result['customers'][] = $consumer;
            }
        }

        return $result;
    }
}