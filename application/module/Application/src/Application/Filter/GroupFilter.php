<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 24.07.16
 * Time: 12:54
 */

namespace Application\Filter;

use Zend\InputFilter\InputFilterInterface;

class GroupFilter extends AbstractExtendedFilter
{

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        $this->inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ]
            ],
        ]);

        return $this->inputFilter;
    }
}