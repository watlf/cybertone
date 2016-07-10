<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 09.07.16
 * Time: 14:51
 */

namespace Application\Form;

use Zend\Form\Form;

abstract class AbstractExtendedForm extends Form
{
    public $formName;

    public function __construct()
    {
        parent::__construct($this->formName);

        $this->create();
    }

    abstract public function create();
}