<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 24.07.16
 * Time: 12:44
 */

namespace Application\Form;


class GroupForm extends AbstractExtendedForm
{

    protected function create()
    {
        $this->setAttributes(array(
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'autofocus' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Group Name',
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-lg btn-primary btn-block',
                'value' => 'Submit',
            ),
        ));
    }
}