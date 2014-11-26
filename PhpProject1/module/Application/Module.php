<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $app = $e->getApplication();
        $serviceManager = $app->getServiceManager();
        $serviceManager->get('viewhelpermanager')->setFactory('myviewalias', function($sm) use ($e) {
            return new \Application\View\AppViewHelper($e->getRouteMatch());
        });
    }
     
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
   
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
