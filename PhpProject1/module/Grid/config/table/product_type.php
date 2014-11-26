<?php
return array (
    'grid'      => array (
        'header'    => array( _('Id'), _('Title'), _('Status'), _('Actions'), '#cspan',),
        'width'     => array(25,25,25,15,15,),
        'align'     => array('center','center','center','center','center'),
        'type'      => array('ro','ro','ro','ro','ro'),
        'sort'      => array('int','na','int','na','na'),
        'scheme'    => array(
            array (
                'entity'    => 'field',
                'name'      => 'id',
                'filter'    => array(
                    'name'  => 'input',
                ),
            ),
            
            array (
                'entity'    => 'field',
                'name'  => 'title',
                'filter'    => array(
                    'name'  => 'input',
                ),
            ),
            array (
                'entity'    => 'field',
                'name'      => 'status',
                'filter'    => array(
                    'name'  => 'select',
                    'options'   =>  array (
                        'all'   => 'all',
                        1   => 'enebled',
                        0   => 'disabled',
                    )
                )
            ),
            array(
                'entity'    => 'action',
                'type'      =>  'fancybox',
                'name'      => _('Edit'),
                'action'    => 'edit',
                'class'     => 'edit',
                'param'     => array(
                    'id'    => 'id',
                )
                
            ),
            array(
                'entity'    => 'action',
                'type'      =>  'confirm',
                'name'      => _('Deleted'),
                'action'    => 'delete',
                'class'     => 'del',
                'param'     => array(
                    'id'    => 'id',
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