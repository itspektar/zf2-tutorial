<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeleton{$Module} for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

$Module = 'Generic';
$module = strtolower($Module);

return array(
	'router'          => array(
		'routes' => array(
			"{$module}" => array(
				'type'    => 'segment',
				'options' => array(
					'route'       => "/{$module}[/][:action][/:id]",
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+',
					),
					'defaults'    => array(
						'controller' => "{$Module}\\Controller\\{$Module}",
						'action'     => 'index',
					),
				),
			),
		),
	),
	'controllers'     => array(
		'invokables' => array(
			"{$Module}\\Controller\\{$Module}" => "{$Module}\\Controller\\{$Module}Controller"
		),
	),
	'view_manager'    => array(
		'template_path_stack' => array(
			"{$module}" => __DIR__ . '/../view',
		),
	),
	// Placeholder for console routes
	'console'         => array(
		'router' => array(
			'routes' => array(),
		),
	),
);
