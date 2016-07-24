<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 23.07.16
 * Time: 17:57
 */

namespace Application\Controller;


use Application\Filter\SearchGroupFilter;
use Application\Form\SearchGroupForm;
use Application\View\Helper\PaginationHelper;
use Zend\Validator\InArray;

class GroupController extends AbstractExtendedController
{
    /**
     * @var array
     */
    private $fields =  array(
        'id' => 'Group Id',
        'name' => 'Group Name'
    );

    public function indexAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $limit = PaginationHelper::PER_PAGE;
        $offset = $this->getOffset($page, $limit);

        $form = new SearchGroupForm();

        $form->get('fields')->setOptions(array(
            'value_options' => $this->fields
        ));

        $filters = array();
        $request = $this->getRequest();
        $query = $request->getQuery();

        if ($request->isGet() && $query->count()) {
            $filter = new SearchGroupFilter();

            $form->setInputFilter($filter->getInputFilter());

            $form->getInputFilter()->get('fields')->getValidatorChain()->addValidator(
                new InArray(array('haystack' => array_keys($this->fields)))
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

        return array(
            'form' => $form,
            'groups' => $groups['listGroups'],
            'countGroups' => $groups['count'],
            'page' => $page,
            'request' =>$query->toString()
        );
    }
}