<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Filter\SearchUserFilter;
use Application\Form\SearchUserForm;
use Application\View\Helper\PaginationHelper;
use Zend\Http\Request;
use Zend\Validator\InArray;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractExtendedController
{
    private $fields = array(
        'id' => 'Id',
        'login' => 'Login',
        'email' => 'Email',
        'accountExpired' => 'Account Expired',
        'groups' => 'Group Name',
    );

    public function indexAction()
    {
        $page = $this->params()->fromRoute('page', 1);

        $limit = PaginationHelper::PER_PAGE;
        $offset = $this->getOffset($page, $limit);
        $groups = $this->getGroupsRepository()->getAllGroups();
        $form = new SearchUserForm();

        $form->get('fields')->setOptions(array(
            'value_options' => array_merge($form->get('fields')->getOption('value_options'), $this->fields))
        );
        $form->get('groupId')->setOptions(array(
            'value_options' => array_merge($form->get('groupId')->getOption('value_options'), $groups))
        );

        $filters = array();
        $request = $this->getRequest();
        $query = $request->getQuery();

        if ($request->isGet() && $query->count()) {
            $filter = new SearchUserFilter();

            $form->setInputFilter($filter->getInputFilter());

            $form->getInputFilter()->get('fields')->getValidatorChain()->addValidator(
                new InArray(array('haystack' => array_keys($this->fields)))
            );

            $form->setData($query);
            if ($form->isValid()) {
                $form->populateValues($query);
                $filters = $this->getFilters($request);
            }
        }

        $dataUsers = $this->getConsumersRepository()->getConsumers($filters, $offset, $limit);
        $flashMessenger = $this->flashMessenger();
        $messages = ($flashMessenger->hasMessages()) ? $flashMessenger->getMessages() : '';

        $result = array(
            'page' => $page,
            'groups' => $groups,
            'fields' => $this->fields,
            'form' => $form,
            'request' => $query->toString(),
            'messages' => $messages,
            'countUsers' => '',
            'consumers' => '',
        );

        if ($dataUsers) {
            $result['countUsers'] = $dataUsers['count'];
            $result['consumers'] = $dataUsers['listUsers'];
        }

        return $result;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getFilters(Request $request)
    {
        $result = array();

        if ($request->getQuery()->count()) {
            $result['accountExpired'] = $request->getQuery('accountExpired');
            $result['filterByGroupId'] = $request->getQuery('groupId');
            $result['orderByField'] = $request->getQuery('fields');
            $result['order'] = $request->getQuery('order');
        }

        return $result;
    }
}
