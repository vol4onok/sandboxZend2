<?php
return array(
    'service_manager'  => array (
        'factories' => array (
            'Authentication' => function ($sm) {
                $auth = new Auth\Authentication();
                $alc = Zend\Cache\PatternFactory::factory('object', array(
                        'object'   => new \Auth\Acl(require __DIR__ . '/alc.config.php'),
                        'storage' => 'memcached',
                        'object_key' => '.objectCache.\Auth\Acl',
                        'cache_by_default' => false,
                        // the output don't need to be catched and cached
                        'cache_output' => false,
                      ));
                $auth->setUserAuthenticationPlugin($sm->get('User'))
                    ->setAclClass($alc);
                return $auth;
            },
            'User'  => function ($sm) {
                $user = new \Auth\Plugin\UserPlugin($sm);
                $user->auth();
                return $user;
            },
        ),
        'services' => array(
            'Auth' => new Zend\Authentication\AuthenticationService(),
        )
    ),
);