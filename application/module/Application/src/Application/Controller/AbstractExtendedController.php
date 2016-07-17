<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.16
 * Time: 11:16
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;

abstract class AbstractExtendedController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $em;

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
}