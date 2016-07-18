<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 16.07.16
 * Time: 20:57
 */

namespace Application\Filter;


use Zend\Db\Adapter\Adapter;
use Zend\InputFilter\InputFilterInterface;

class UserFilter extends AbstractExtendedFilter
{
    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        $this->inputFilter->add(array(
            'name' => 'login',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ),
                )
            ),
        ));

        $this->inputFilter->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ),
                )
            )
        ));

        $this->inputFilter->add(array(
            'name' => 'password',
            'required' => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 8,
                        'max' => 100,
                    ),
                ),
            )
        ));

        $this->inputFilter->add(array(
            'name' => 'logo',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'File\Size',
                    'options' => array(
                        'max' => '4Mb',
                    )
                ),
                array(
                    'name' => 'File\MimeType',
                    'options' => array('image')
                ),
                array(
                    'name' => 'File\Extension',
                    'options' => array('png', 'jpg', 'jpeg', 'gif')
                )
            )
        ));

        $this->inputFilter->add(array(
            'name'     => 'groupId',
            'required' => false,
            'filters'  => array(
                array('name' => 'Int'),
            )
        ));

        $this->inputFilter->add(array(
            'name' => 'accountExpired',
            'required' => true,
            'filters' => array (
                array ('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'Y-m-d',
                    )
                )
            )
        ));

        return $this->inputFilter;
    }
}