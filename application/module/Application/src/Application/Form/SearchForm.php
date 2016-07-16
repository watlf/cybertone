<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 11.07.16
 * Time: 20:40
 */

namespace Application\Form;

class SearchForm extends AbstractExtendedForm
{
    public $formName = 'filter';

    /**
     * @var array
     */
    public $defaultGroupValues = array(
        '' => 'Select',
        0 => 'Users without group',
    );

    /**
     * @var array
     */
    public $defaultFieldsValues = array(
        '' => 'Select'
    );

    public function create()
    {
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'groupId',
            'options' => array(
                'label' => 'Filter by group',
                'value_options' => $this->defaultGroupValues
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'fields',
            'options' => array(
                'label' => 'Filter by field',
                'value_options' => $this->defaultFieldsValues,
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'accountExpired',
            'attributes' => array(
                'class' => 'datepicker form-control',
                'type' => 'text',
                'value' => ''
            ),
            'options' => array(
                'label' => 'Account Expired',
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'order',
            'options' => array(
                'label' => 'Type order',
                'value_options' => array('asc' => 'ASC', 'desc' => 'DESC'),
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'class' => 'form-control btn btn-info',
                'type' => 'submit',
                'value' => 'Submit'
            ),
            'options' => array(
                'label' => '&nbsp;',
            )
        ));

        $this->setAttributes(array(
            'class' =>'form-signin',
            'method' => 'get',
        ));
    }
}