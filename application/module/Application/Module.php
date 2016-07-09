<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /**
         * @var $authService \Zend\Authentication\AuthenticationService
         */
        $authService = $e->getApplication()->getServiceManager()->get('Factory\AuthenticationAdapter');

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) use($authService) {
            $routeMatch = $e->getRouteMatch();

            if (!$authService->hasIdentity() && $routeMatch->getMatchedRouteName() !== 'auth') {
                $response = $e->getResponse();
                $url = $e->getRouter()->assemble(array(), array('name' => 'auth'));
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302);
                return $response;
            }
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
