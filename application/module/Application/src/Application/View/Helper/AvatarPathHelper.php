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
    const DEFAULT_IMG_LOGO = 'zf2-logo.png';

    /**
     * @param int $userId
     * @param string $extension
     * @return string
     */
    public function __invoke($userId, $extension)
    {
        $image = $this->getImagePath($userId, $extension);

        if (file_exists($image)) {
            $result  = str_replace(APP_PUBLIC, '', $image);
        } else {
            $result = sprintf('/img/%s', self::DEFAULT_IMG_LOGO);
        }

        return $result;
    }

    /**
     * @param int $userId
     * @param string $extension
     * @return string
     */
    private function getImagePath($userId, $extension)
    {
        return sprintf('%s/uploads/consumers/avatar/%d.%s', APP_PUBLIC, $userId, $extension);
    }
}