<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 09.07.16
 * Time: 17:34
 */

namespace Services;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\Adapter\Http as HttpAdapter;
use Zend\Authentication\Adapter\Http\FileResolver;

class AuthenticationAdapterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $authConfig = $config['auth_adapter'];
        $authAdapter = new HttpAdapter($authConfig['config']);
        $basicResolver = new FileResolver();
        $digestResolver = new FileResolver();

        $basicResolver->setFile($authConfig['basic_file']);
        $digestResolver->setFile($authConfig['digest_file']);
        $authAdapter->setBasicResolver($basicResolver);
        $authAdapter->setDigestResolver($digestResolver);

        return $authAdapter;
    }
}