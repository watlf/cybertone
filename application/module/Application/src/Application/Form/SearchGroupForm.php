<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 24.07.16
 * Time: 11:13
 */

namespace Application\Form;

class SearchGroupForm extends AbstractExtendedForm
{

    public function create()
    {
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'fields',
            'options' => array(
                'label' => 'Order by column',
            ),
            'attributes' => array(
                'class' => 'form-control'
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
            'method' => 'get',
        ));
    }
}