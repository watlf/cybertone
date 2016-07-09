<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 09.07.16
 * Time: 13:56
 */

namespace Application\Filter;

class AuthFilter extends AbstractExtendedFilter
{
    /**
     * Retrieve input filter
     *
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public function getInputFilter()
    {
        $this->inputFilter->add(array(
            'name'     => 'name',
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
                        'min'      => 1,
                        'max'      => 100,
                    ),
                ),
            ),
        ));

        $this->inputFilter->add(array(
            'name' => 'password',
            'required' => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 8,
                    ),
                ),
            )
        ));

        return $this->inputFilter;
    }
}