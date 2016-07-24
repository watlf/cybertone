<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 24.07.16
 * Time: 11:32
 */

namespace Application\Filter;


use Zend\InputFilter\InputFilterInterface;

class SearchGroupFilter extends AbstractExtendedFilter
{

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
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