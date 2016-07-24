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

    /**
     * @param array $formData
     * @return int
     */
    public function addGroup(array $formData)
    {
        try {
            $group = new GroupsEntity();

            $group->setName($formData['name']);

            $this->_em->persist($group);

            $this->_em->flush();
            $result = $group->getId();
        } catch (\InvalidArgumentException $e) {
            $result = 0;
        }


        return $result;
    }

    /**
     * @param array $formData
     * @param int $id
     * @return int
     */
    public function editGroup(array $formData, $id)
    {
        try {
            $group = new GroupsEntity();

            $group->setId($id);
            $group->setName($formData['name']);

            $this->_em->merge($group);

            $this->_em->flush();
            $result = $group->getId();
        } catch (\InvalidArgumentException $ex) {
            $result = 0;
        }

        return $result;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function deleteGroup($id)
    {
        $result = true;

        try {
            $user = $this->_em->getReference(GroupsEntity::class, ['id' => $id]);

            $this->_em->remove($user);

            $this->_em->flush();
        } catch (\InvalidArgumentException $ex) {
            $result = false;
        }

        return $result;
    }
}