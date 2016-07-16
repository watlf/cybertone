<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 14.07.16
 * Time: 19:55
 */

namespace Application\Filter;

use Zend\InputFilter\InputFilterInterface;

class SearchFilter extends AbstractExtendedFilter
{

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        $this->inputFilter->add(array(
            'name'     => 'groupId',
            'required' => false,
            'filters'  => array(
                array('name' => 'Int'),
            )
        ));

        $this->inputFilter->add(array(
            'name' => 'fields',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $this->inputFilter->add(array(
            'name' => 'accountExpired',
            'required' => false,
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

        $this->inputFilter->add(array(
            'name' => 'order',
            'required' => false,
            'validators'  => array(
                array(
                    'name' => 'inArray',
                    'options' => array(
                        'haystack' => array('desc', 'asc')
                    )
                )
            ),
        ));

        return $this->inputFilter;
    }
}