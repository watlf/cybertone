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
            ->select(['consumers', 'groups.id', 'groups.name'])
            ->from('Application\Model\Entity\Consumers', 'consumers')
            ->leftJoin(
                'Application\Model\Entity\Groups',
                'groups',
                Join::WITH,
                'groups.id = consumers.groupId'
            );

        $data = $qb->getQuery()->getArrayResult();


        foreach ($data as $values) {
            $needle = current($values);
            $needle['groupName'] = $values['name'];
            $needle['groupId'] = $values['id'];
            $result[(int)$values['id']][] = $needle;
        }

        return $result;
    }
}