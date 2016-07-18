<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 18.07.16
 * Time: 20:16
 */

namespace Application\View\Helper;


use Zend\View\Helper\AbstractHelper;

class AvatarPathHelper extends AbstractHelper
{
    /**
     * @param int $userId
     * @param string $extension
     * @return string
     */
    public function __invoke($userId, $extension)
    {
        return sprintf('/uploads/consumers/avatar/%d.%s', $userId, $extension);
    }
}