<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends AbstractExtendedController
{
    public function indexAction()
    {
        $consumersRepository = $this->getConsumersRepository();

        $groupsRepository = $this->getGroupsRepository();

        return array(
            'consumers' => $groupsRepository->getGroupsWithConsumers()
        );
    }

    /**
     * @return \Application\Model\Repository\Consumers
     */
    private function getConsumersRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Model\Entity\Consumers');
    }

    /**
     * @return \Application\Model\Repository\Groups
     */
    private function getGroupsRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Model\Entity\Groups');
    }
}
