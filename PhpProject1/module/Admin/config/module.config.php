<?php

return array(
	'router' => array(
		'routes' => array(
			// The following is a route to simplify getting started creating
			// new controllers and actions without needing to create a new
			// module. Simply drop new controllers in, and you can access them
			// using the path /application/:controller/:action
			'admin' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/admin[/:controller[/:action[/:id]]][/]',
					'defaults' => array(
						'__NAMESPACE__' => 'Admin\Controller',
						'controller' => 'Product',
						'action' => 'index',
						'lang' => 'en',
						'id' => 0,
					),
					'constraints' => array(
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
				),
				'may_terminate' => true,
			),
		),
	),
	//Router for controllers which will be executed only via command line
	// or like cron tasks
	/*
	'console' => array(
    'router' => array(
      'routes' => array(
      	// Console routes go here
      	'cron' => array(
      		'options' => array(
      			'route' => 'cron daily',
      			'defaults' => array(
      				'controller' => 'Admin\Controller\CronController',
      				'action' => 'daily',
      			),
      		),
      	),
      ),
    ),
	),*/
	'controllers' => array(
		'invokables' => array(
			'Admin\Controller\User' => 'Admin\Controller\UserController',
			'Admin\Controller\Product' => 'Admin\Controller\ProductController',
            'Admin\Controller\Template' => 'Admin\Controller\TemplateController',
            'Admin\Controller\Uploader' => 'Admin\Controller\UploaderController',
            'Admin\Controller\Category' => 'Admin\Controller\CategoryController',
        ),
	),
    'service_manager'   =>  array(
        'factories' => array(
            'Admin\Form\ProductForm' => function($sm) {
                return  new \Admin\Form\ProductForm('product', $sm);
            },
            'Admin\Form\CategoryForm' => function($sm) {
                return  new \Admin\Form\CategoryForm('category', $sm);
            },
            'Application\Lib\Tools\Uploader\FileUpload' => function($sm) {
                return  new \Application\Lib\Tools\Uploader\FileUpload($sm);
            },
            'Application\Lib\Tools\Uploader\Bilder\FileUploadPOSTForm' => function($sm) {
                return new \Application\Lib\Tools\Uploader\Bilder\FileUploadPOSTForm($sm);
            },
            'Application\Lib\Tools\Uploader\Bilder\FileUploadXHR' => function($sm) {
                return new \Application\Lib\Tools\Uploader\Bilder\FileUploadXHR($sm);
            },
        ),
    ),
	'view_manager' => array(
		'display_not_found_reason' => DEBUG,
		'display_exceptions' => DEBUG,
		'doctype' => 'HTML5',
		'not_found_template' => 'admin/error/404',
		'exception_template' => 'admin/error/index',
		'template_map' => array(
			'admin/layout'           => __DIR__ . '/../view/layout/layout.phtml',
			'admin/iframe'           => __DIR__ . '/../view/layout/iframe.phtml',
			'admin/error/404'               => __DIR__ . '/../view/error/404.phtml',
			'admin/error/index'             => __DIR__ . '/../view/error/index.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);
