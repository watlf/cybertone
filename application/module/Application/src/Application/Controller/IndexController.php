<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\View\Helper\PaginationHelper;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractExtendedController
{
    public function indexAction()
    {
        $page = $this->params()->fromRoute('page', 1);

        $limit = PaginationHelper::PER_PAGE;
        $offset = $this->getOffset($page);

        $dataUsers = $this->getConsumersRepository()->getConsumers($offset, $limit);

        return array(
            'page' => $page,
            'countUsers' => $dataUsers['count'],
            'consumers' => $dataUsers['listUsers'],
            'groups' => $this->extractGroup($dataUsers['listUsers']),
        );
    }

    /**
     * @param int $page
     * @return int
     */
    private function getOffset($page)
    {
        return (0 === (int)$page) ? 0 : ($page - 1) * PaginationHelper::PER_PAGE;
    }

    /**
     * @param array $listUsers
     * @return array
     */
    private function extractGroup(array $listUsers)
    {
        $result = array();

        foreach ($listUsers as $user) {
            $result[$user['groupId']] = $user['groupName'];
        }


        return $result;
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
