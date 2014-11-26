<?php

namespace Auth;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;

class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface {
    
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager()->getSharedManager();
         $em->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
    }
    /**
     * 
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function mvcPreDispatch(MvcEvent $e) {
        $controller = $e->getTarget();
		$auth = $e->getApplication()->getServiceManager()->get('Authentication');
        $auth->preDispatch($controller);
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
