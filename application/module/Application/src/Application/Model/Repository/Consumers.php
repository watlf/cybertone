<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.16
 * Time: 11:34
 */

namespace Application\Model\Repository;

use Application\Model\Entity\Groups as EntityGroups;
use Application\View\Helper\PaginationHelper;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Application\Model\Entity\Consumers as EntityConsumers;

class Consumers extends EntityRepository
{
    /**
     * @param array $filters
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getConsumers($filters = array(), $offset = 0, $limit = PaginationHelper::PER_PAGE)
    {
        try {
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
        } catch (QueryException $ex) {
            $result = array();
        }

        return $result;
    }

    /**
     * @param string $email
     * @return array
     */
    public function getConsumerByEmail($email)
    {
        $db = $this->_em->createQueryBuilder()
            ->select('consumers')
            ->from('Application\Model\Entity\Consumers', 'consumers')
            ->where('consumers.email = :email')
            ->setMaxResults(1);

        $db->setParameters(array(
            'email' => $email
        ));

        return $db->getQuery()->getArrayResult();
    }

    /**
     * @param array $formData
     * @return int
     */
    public function addConsumer(array $formData)
    {
        $group = null;

        if ($formData['groupId']) {
            $group = new EntityGroups();
        }

        $consumers = new EntityConsumers($group);
        
        $consumers->setLogin($formData['login'])
            ->setEmail($formData['email'])
            ->setPassword($formData['password'])
            ->setAccountExpired(new \DateTime($formData['accountExpired']));

        if (isset($formData['extension'])) {
            $consumers->setAvatarExtension($formData['extension']);
        }

        $this->_em->persist($consumers);

        $this->_em->flush();

        return $consumers->getId();
    }

    /**
     * @param array $formData
     * @param int $id
     * @return int
     */
    public function editConsumer(array $formData, $id)
    {
        $group = null;

        if ($formData['groupId']) {
            $group = new EntityGroups();
        }

        $consumers = new EntityConsumers($group);

        $consumers->setId($id)
            ->setLogin($formData['login'])
            ->setEmail($formData['email'])
            ->setPassword($formData['password'])
            ->setAccountExpired(new \DateTime($formData['accountExpired']));

        if (isset($formData['extension'])) {
            $consumers->setAvatarExtension($formData['extension']);
        }

        $this->_em->merge($consumers);

        $this->_em->flush();

        return $consumers->getId();
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteConsumer($id)
    {
        $result = true;

        try {
            $user = $this->_em->getReference('Application\Model\Entity\Consumers', array('id' => $id));

            $this->_em->remove($user);

            $this->_em->flush();
        } catch (\Exception $ex) {
            $result = false;
        }

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