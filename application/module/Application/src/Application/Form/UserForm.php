<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 16.07.16
 * Time: 19:25
 */

namespace Application\Form;


class UserForm extends AbstractExtendedForm
{

    public function create()
    {
        $this->setAttributes(array(
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'action' => '/user/add'
        ));

        $this->add(array(
            'name' => 'login',
            'attributes' => array(
                'type' => 'text',
                'autofocus' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Login',
            )
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Email',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Password',
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
            'name' => 'groupId',
            'options' => array(
                'label' => 'Group'
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'logo',
            'attributes' => array(
                'type' => 'file'
            ),
            'options' => array(
                'label' => 'User Logo',
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