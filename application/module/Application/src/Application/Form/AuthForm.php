<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 09.07.16
 * Time: 12:54
 */

namespace Application\Form;

class AuthForm extends AbstractExtendedForm
{
    public $formName = 'auth';

    /**
     * @return void
     */
    protected function create()
    {
        $this->setAttributes(array(
            'class' =>'form-signin form-group',
            'method' => 'post',
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'text',
                'autofocus' => true,
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-lg btn-primary btn-block',
                'value' => 'Sign In',
            ),
        ));
    }
}