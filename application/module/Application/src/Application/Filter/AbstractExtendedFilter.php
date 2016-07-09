<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 09.07.16
 * Time: 14:32
 */

namespace Application\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

abstract class AbstractExtendedFilter implements InputFilterAwareInterface
{
    /**
     * @var InputFilterInterface
     */
    protected $inputFilter;

    public function __construct()
    {
        $this->setInputFilter(new InputFilter());
    }

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return void
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    abstract public function getInputFilter();
}