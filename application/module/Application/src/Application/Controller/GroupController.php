<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 23.07.16
 * Time: 17:57
 */

namespace Application\Controller;

use Application\Filter\GroupFilter;
use Application\Filter\SearchGroupFilter;
use Application\Form\GroupForm;
use Application\Form\SearchGroupForm;
use Application\View\Helper\PaginationHelper;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\InArray;

class GroupController extends AbstractExtendedController
{
    /**
     * @var array
     */
    private $fields =  [
        'id' => 'Group Id',
        'name' => 'Group Name'
    ];

    public function indexAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $limit = PaginationHelper::PER_PAGE;
        $offset = $this->getOffset($page, $limit);
        $flashMessenger = $this->flashMessenger();
        $messages = ($flashMessenger->hasMessages()) ? $flashMessenger->getMessages() : [];
        $form = new SearchGroupForm();

        $form->get('fields')->setOptions([
            'value_options' => $this->fields
        ]);

        $filters = [];
        $request = $this->getRequest();
        $query = $request->getQuery();

        if ($request->isGet() && $query->count()) {
            $filter = new SearchGroupFilter();

            $form->setInputFilter($filter->getInputFilter());

            $form->getInputFilter()->get('fields')->getValidatorChain()->addValidator(
                new InArray(['haystack' => array_keys($this->fields)])
            );

            $form->setData($query);
            if ($form->isValid()) {
                $form->populateValues($query);
                $filters = $query->toArray();
            }
        }

        $groups = $this->getGroupsRepository()->getGroups($filters, $offset, $limit);
        $request = $this->getRequest();
        $query = $request->getQuery();

        return [
            'form' => $form,
            'groups' => $groups['listGroups'],
            'countGroups' => $groups['count'],
            'page' => $page,
            'request' =>$query->toString(),
            'messages' => $messages
        ];
    }

    public function addAction()
    {
        $form = new GroupForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $dataForm = $request->getPost()->toArray();

            /**
             * @var GroupFilter $filter
             */
            $filter = new GroupFilter();
            $form->setInputFilter($filter->getInputFilter());
            $options = [
                'table' => 'groups',
                'field' => 'name',
                'adapter' => $this->getDbAdapter()
            ];

            $form->getInputFilter()->get('name')->getValidatorChain()->addValidator(
                new NoRecordExists($options)
            );

            $form->setData($dataForm);

            if ($form->isValid()) {
                $groupId = $this->getGroupsRepository()->addGroup($dataForm);

                if ($groupId) {
                    $this->flashMessenger()->addMessage('Group was successfully added');
                } else {
                    $this->flashMessenger()->addMessage('Something went wrong, try again');
                }

                $this->redirect()->toRoute('group');
            }
        }

        return [
            'form' => $form
        ];
    }

    public function editAction()
    {
        $form = new GroupForm();

        $id = $this->params('id');
        $group = $this->getGroupsRepository()->findOneBy(['id' => $id]);
        if ($group) {
            $form->setData(['name' => $group->getName()]);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $filter = new GroupFilter();
                $dataForm = $request->getPost()->toArray();

                $form->setInputFilter($filter->getInputFilter());
                $options = [
                    'table' => 'groups',
                    'field' => 'name',
                    'adapter' => $this->getDbAdapter(),
                    'exclude' => [
                        'field' => 'id',
                        'value' => $id
                    ]
                ];

                $form->getInputFilter()->get('name')->getValidatorChain()->addValidator(
                    new NoRecordExists($options)
                );

                $form->setData($dataForm);

                if ($form->isValid()) {
                    $groupId = $this->getGroupsRepository()->editGroup($dataForm, $id);

                    if ($groupId) {
                        $this->flashMessenger()->addMessage('Group was successfully updated');
                    } else {
                        $this->flashMessenger()->addMessage('Something went wrong, try again');
                    }
                    $this->redirect()->toRoute('group');
                }
            }
        } else {
            $this->flashMessenger()->addMessage('Group not found');
            $this->redirect()->toRoute('group');
        }

        return [
            'form' => $form
        ];
    }

    public function deleteAction()
    {
        $result = $this->getGroupsRepository()->deleteGroup(
            $this->params('id')
        );

        if ($result) {
            $this->flashMessenger()->addMessage('Group was successfully deleted');
        } else {
            $this->flashMessenger()->addMessage('Something went wrong, try again');
        }

        $this->redirect()->toRoute('group');
    }
}