<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 09.07.16
 * Time: 17:34
 */

namespace Application\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\Adapter\Digest as DigestAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as Storage;

class AuthenticationAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $authConfig = $config['auth_adapter'];

        $authAdapter = new DigestAdapter($authConfig['digest_file'], $authConfig['realm']);
        $authService = new AuthenticationService();
        $authStorage = new Storage();

        $authService->setAdapter($authAdapter);
        $authService->setStorage($authStorage);

        return $authService;
    }
}