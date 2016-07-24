<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.16
 * Time: 11:16
 */

namespace Application\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

abstract class AbstractExtendedController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Adapter
     */
    protected $dbAdapter;

    /**
     * @return array|EntityManager|object
     */
    protected function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function getAuthService()
    {
        return $this->getServiceLocator()->get('Factory\AuthenticationAdapter');
    }

    /**
     * @return \Application\Model\Repository\Consumers
     */
    protected function getConsumersRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Model\Entity\Consumers');
    }

    /**
     * @return \Application\Model\Repository\Groups
     */
    protected function getGroupsRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Model\Entity\Groups');
    }

    /**
     * @return Adapter
     */
    protected function getDbAdapter()
    {
        if (null === $this->dbAdapter) {
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        }
        return $this->dbAdapter;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return int
     */
    protected function getOffset($page, $limit)
    {
        return (0 === (int)$page) ? 0 : ($page - 1) * $limit;
    }
}