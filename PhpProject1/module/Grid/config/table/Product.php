<?php
return array (
    'grid'      => array (
        'header'    => array( _('Id'), _('Slug'), _('Title'), _('Description'), _('Price'), _('Actions'), '#cspan',),
        'width'     => array(25,25,25,55,25,15,15,),
        'align'     => array('center','center','center','center','center','center','center'),
        'type'      => array('ro','ro','ro','ro','ro','ro','ro'),
        'sort'      => array('int','na','str','str','price','na','na'),
        'scheme'    => array(
            array (
                'entity'    => 'field',
                'name'      => 'id',
            ),
            array (
                'entity'    => 'field',
                'name'  => 'slug',
            ),
            array (
                'entity'    => 'field',
                'name'      => 'title',
                'filter'    => array(
                    'name'  => 'input',
                ),
            ),
            array (
                'entity'    => 'field',
                'name'      => 'description',
                'filter'    => array(
                    'name'  => 'input',
                ),
            ),
            array (
                'entity'    => 'field',
                'name'      => 'price',
                'filter'    => array(
                    'name'      => 'select',
                    'options'   => array (
                        'all'   => 'all',
                        23     =>  23,
                        32     =>  32,
                        24     =>  24,
                    ),
                ),
                'isAllowed' => array(
                    'controller' => 'Admin\Controller\Product',
                    'action'    => 'delete',
                ),
            ),
            array(
                'entity'    => 'action',
                'type'      =>  'fancybox',
                'name'      => _('Edit'),
                'action'    => 'edit',
                'controller'    => 'product',
                'class'     => 'edit',
                'param'     => array(
                    'id'    => 'id',
                ),
            ),
            array(
                'entity'    => 'action',
                'type'      =>  'confirm',
                'name'      => _('Deleted'),
                'action'    => 'delete',
                'controller'    => 'product',
                'class'     => 'del',
                'param'     => array(
                    'id'    => 'id',
                ),
                'isAllowed' => array(
                    'controller' => 'Admin\Controller\Product',
                    'action'    => 'delete',
                ),
            ),
        ),
        'default'   =>  array(
            'count' =>   10,
            'posStart' =>   0,
            'orderBy' =>   'id',
            'order' =>   'desc',
        ),
    ),
);