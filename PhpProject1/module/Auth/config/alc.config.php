<?php
return array(
    'acl' => array(
        'roles' => array(
            'guest'     => null,
            'user'      => 'guest',
            'manager'   => 'user',
            'admin'     => 'manager',
        ),
        'resources' => array(
            'allow' => array(
                // Application Controller
                'Application\Controller\Index'  => array(
                    'all' => 'guest',
                ),
                'Application\Controller\Product'=> array(
                    'all' => 'guest',
                ),
                'Application\Controller\User'   => array(
                    'all' => 'guest',
                ),
                
                'Application\Controller\ShoppingCart'   => array (
                    'all' => 'guest',
                    'edit-shopping-cart-product'    =>  'user',
                ),
                'Application\Controller\Payment' => array (
                    'all'   =>  'user',
                ),
                // Admin Controller
                'Admin\Controller\Product'  =>  array (
                    'all'   =>  'manager',
                ),
                'Admin\Controller\Uploader'  =>  array (
                    'all'   =>  'manager',
                ),
                'Admin\Controller\Category'  =>  array (
                    'all'   =>  'manager',
                ),
                'Admin\Controller\User' =>  array (
                    'all'   =>  'admin',
                ),
                'Admin\Controller\Template' =>  array (
                    'all'   =>  'admin',
                ),
            ),
            'deny'  => array (
                // Application Controller
                'Application\Controller\ShoppingCart' => array(
                    'edit-shopping-cart-product'    =>  'guest',
                ),
                'Admin\Controller\Category' => array(
                    'delete'    =>  'admin',
                ),
                // Admin Controller
                
            )
        )
    )
);