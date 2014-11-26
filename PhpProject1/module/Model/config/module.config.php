<?php
return array(
    'doctrine' => array(
        'cache' => array(
            'redis' => array(
                'class'     => 'Doctrine\Common\Cache\RedisCache',
                'instance'  => 'Redis',
                'namespace' => 'shoptest-aveselov',
            ),
        ),
        'driver' => array(
            'Annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Model\Entity' =>  'Annotation_driver',
                ),
            ),
        ),
        'configuration' => array (
            'orm_default' => array(
                'types' => array(
                    'enum' => '\Doctrine\DBAL\Types\StringType'
                 ),
                'proxy_dir' => __DIR__ . '/../src/Model/Proxy',
                'proxy_namespace' => 'Model\Proxy',
//                'metadata_cache'    => 'redis',
//                'query_cache'       => 'redis',
//                'result_cache'      => 'redis',
//                'hydration_cache'   => 'redis',
            ),
        ),
        'connection' => array(
            'orm_default' => array(
                 'doctrineTypeMappings' => array(
                    'enum'  =>  'string',
                ),
                'params' => array(
                    'host'     => '',
                    'user'     => 'ifix',
                    'password' => 'ifix',
                    'dbname'   => 'shoptest_veselov',
                ),
            ),
        ),
    ),
    'service_manager'  => array (
        'factories' => array(
                'doctrine.cache.redis' => new DoctrineModule\Service\CacheFactory('redis'),
                'Redis' => 'Model\Cache\RedisCache',
            )
    )
);