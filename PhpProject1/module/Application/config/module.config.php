<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/catalog/:category][/page/:page]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:controller[/:action]][/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                ),
                'may_terminate' => true,
            ),
            'product' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/product[/:action][[/:slug].html]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Product',
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug' => '[a-zA-Z%0-9_-]+',
                    ),
                ),
                'may_terminate' => true,
            ),
            'shopping-cart' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/shopping-cart[/:action][[/:slug].html]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'ShoppingCart',
                        'action' => 'index'
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'slug' => '[a-zA-Z0-9_-]*',
                    ),
                ),
                'may_terminate' => true,
            ),
            'activeforgot' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user/activeforgot/:id/:code',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'User',
                        'action' => 'activeforgot',
                    ),
                    'constraints' => array(
                        'code' => '[a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ProductTable' => 'Application\Model\ProductTable',
            'TemplateTable' => 'Application\Model\TemplateTable',
            'TypeTable' => 'Application\Model\TypeTable',
            'ShoppingCartTable' => 'Application\Model\ShoppingCartTable',
            'UserTable' => 'Application\Model\UserTable',
            'AttachmentTable' => 'Application\Model\AttachmentTable',
            'ProductAttachmentTable' => 'Application\Model\ProductAttachmentTable',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'resizeImage' => 'Application\View\Helper\ResizeProductHelper',
            'getShoppingCartHelper' => 'Application\View\Helper\ShoppingCartHelper',
            'getCategoryList' => 'Application\View\Helper\Category',
            'getLang' => 'Application\View\Helper\Lang',
            'wrappedElement' => 'Application\View\WrappedElement',
            'wrappedUploadElement' => 'Application\View\WrappedUploadElement',
            'wrappedForm' => 'Application\View\WrappedForm',
            'getUser' => 'Application\View\Helper\User',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\Product' => 'Application\Controller\ProductController',
            'Application\Controller\ShoppingCart' => 'Application\Controller\ShoppingCartController',
            'Application\Controller\Payment' => 'Application\Controller\PaymentController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => DEBUG,
        'display_exceptions' => DEBUG,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
