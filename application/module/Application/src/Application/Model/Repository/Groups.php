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

class Groups extends EntityRepository
{
    /**
     * @return array
     */
    public function getGroups()
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
}