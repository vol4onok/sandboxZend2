<?php

namespace Model\Cache;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class RedisCache implements FactoryInterface {
    public function createService(ServiceLocatorInterface $serviceLocator) {
        
        $redis = new \Redis();
        /**
        * This fetches the configuration array we created above
        */
        $config = $serviceLocator->get('Config');
        $config = $config['redis'];
        $redis->pconnect($config["host"]);
        $redis->select($config["database"]);
        
        return $redis;
    }

}
