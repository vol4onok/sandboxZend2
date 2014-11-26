<?php
return array(
    'mygrid' => array(
        'sourcePath' => __DIR__ . '/table/',
        'setting' => array(
            'imgsPath' => 'js/dhtmlxGrid/imgs/',
            'skin'  => 'light',
        ),
    ),
    'service_manager'  => array (
        'factories' => array(
                'MyGrid' => function ($sm) {
                    $grid = new Grid\Grid($sm);
                    $grid->setAdapter(new Grid\Adapter\DoctrineGridAdapter($sm));
                    return $grid;
                }
            )
    ),
    'view_manager' => array(
		'display_not_found_reason' => DEBUG,
		'display_exceptions' => DEBUG,
		'doctype' => 'HTML5',
		'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
		'strategies' => array(
                'ViewJsonStrategy',
        ),
	),
);